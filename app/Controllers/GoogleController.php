<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use Google\Client;
use Google\Service\Oauth2;

class GoogleController
{
    private Client $client;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/google.php';

        $this->client = new Client();
        $this->client->setClientId((string) ($config['client_id'] ?? ''));
        $this->client->setClientSecret((string) ($config['client_secret'] ?? ''));
        $this->client->setRedirectUri((string) ($config['redirect_uri'] ?? ''));
        $this->client->addScope('email');
        $this->client->addScope('profile');
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

        $_SESSION['user'] = [
            'name' => $data->name,
            'email' => $data->email,
            'picture' => $data->picture,
        ];

        header('Location: ' . url(''));
        exit;
    }
}
