<?php

namespace App\Controllers;

$autoloadCandidates = [
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../../../vendor/autoload.php',
];

$autoloadLoaded = false;
foreach ($autoloadCandidates as $autoloadPath) {
    if (is_file($autoloadPath)) {
        require_once $autoloadPath;
        $autoloadLoaded = true;
        break;
    }
}

if (!$autoloadLoaded) {
    throw new \RuntimeException('Arquivo vendor/autoload.php não encontrado.');
}

use Google\Client;
use Google\Service\Oauth2;
use App\Models\User;
use App\Models\Cart;
use PDO;

class GoogleController
{
    private Client $client;
    private User $userModel;
    private Cart $cartModel;

    public function __construct(private PDO $pdo)
    {
        $config = require __DIR__ . '/../config/google.php';

        $this->client = new Client();
        $this->client->setClientId((string) ($config['client_id'] ?? ''));
        $this->client->setClientSecret((string) ($config['client_secret'] ?? ''));
        $this->client->setRedirectUri((string) ($config['redirect_uri'] ?? ''));
        $this->client->addScope('email');
        $this->client->addScope('profile');

        $this->userModel = new User($pdo);
        $this->cartModel = new Cart($pdo);
    }

    public function login()
    {
        header('Location: ' . filter_var($this->client->createAuthUrl(), FILTER_SANITIZE_URL));
        exit;
    }

    public function callback()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_GET['code'])) {
            echo 'Erro ao autenticar com Google.';
            return;
        }

        $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (isset($token['error'])) {
            echo 'Erro ao autenticar com Google.';
            return;
        }

        $this->client->setAccessToken($token);
        $googleService = new Oauth2($this->client);
        $data = $googleService->userinfo->get();

        $email = trim((string) ($data->email ?? ''));
        $nome = trim((string) ($data->name ?? 'Usuário Google'));

        if ($email === '') {
            echo 'Erro ao autenticar com Google.';
            return;
        }

        $usuario = $this->userModel->findByEmail($email);

        if (!$usuario) {
            $senhaAleatoria = bin2hex(random_bytes(16)) . 'Aa1!';
            $this->userModel->createCustomer($nome, $email, $senhaAleatoria);
            $usuario = $this->userModel->findByEmail($email);
        }

        if (!$usuario) {
            echo 'Erro ao autenticar com Google.';
            return;
        }

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['is_admin'] = $usuario['is_admin'] ?? 0;
        $_SESSION['imagem'] = $usuario['imagem'] ?? null;

        $this->cartModel->syncSessionToDatabase();

        header('Location: ' . url(''));
        exit;
    }
}
