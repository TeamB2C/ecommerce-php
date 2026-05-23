<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;
use App\Models\User;
use PDO;

class AuthController extends Controller
{
    private User $userModel;
    private Cart $cartModel;

    public function __construct(private PDO $pdo)
    {
        $this->userModel = new User($pdo);
        $this->cartModel = new Cart($pdo);
    }

    public function loginRegister(): void
    {
        $expectsJson = $this->expectsJson();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'login') {
            require_csrf($_POST['_csrf'] ?? null);

            $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
            $senha = trim((string) ($_POST['senha'] ?? ''));

            if (!$email || $senha === '') {
                $message = 'Informe e-mail e senha válidos.';
                $_SESSION['erro_login'] = $message;
                if ($expectsJson) {
                    $this->jsonResponse(['ok' => false, 'message' => $message], 422);
                    return;
                }

                $this->redirect('login');
                return;
            }

            $usuario = $this->userModel->findByEmail((string) $email);

            if ($usuario && isset($usuario['senha']) && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['is_admin'] = $usuario['is_admin'];
                $_SESSION['imagem'] = $usuario['imagem'];

                $this->cartModel->syncSessionToDatabase();

                if ((int) $usuario['is_admin'] === 1) {
                    if ($expectsJson) {
                        $this->jsonResponse(['ok' => true, 'redirect' => url('admin')]);
                        return;
                    }

                    $this->redirect('admin');
                    return;
                }

                if ($expectsJson) {
                    $this->jsonResponse(['ok' => true, 'redirect' => url('')]);
                    return;
                }

                $this->redirect('');
                return;
            }

            $message = 'E-mail ou senha incorretos.';
            $_SESSION['erro_login'] = $message;
            if ($expectsJson) {
                $this->jsonResponse(['ok' => false, 'message' => $message], 401);
                return;
            }

            $this->redirect('login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'registrar') {
            require_csrf($_POST['_csrf'] ?? null);

            $nome = trim((string) ($_POST['nome'] ?? ''));
            $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
            $senha = trim((string) ($_POST['senha'] ?? ''));

            $senhaRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/';

            if ($nome === '' || !$email || !preg_match($senhaRegex, $senha)) {
                $erro = 'A senha deve conter no mínimo 8 caracteres, letra maiúscula, minúscula, número e caractere especial.';
                if ($expectsJson) {
                    $this->jsonResponse(['ok' => false, 'message' => $erro], 422);
                    return;
                }

                $this->view('auth.login_register', ['erroRegistro' => $erro]);
                return;
            }

            $usuarioExistente = $this->userModel->findByEmail((string) $email);

            if ($usuarioExistente) {
                $erro = 'Este e-mail já está cadastrado.';
                if ($expectsJson) {
                    $this->jsonResponse(['ok' => false, 'message' => $erro], 409);
                    return;
                }

                $this->view('auth.login_register', ['erroRegistro' => $erro]);
                return;
            }

            $resultado = $this->userModel->createCustomer($nome, (string) $email, $senha);

            if ($resultado === true) {
                $_SESSION['sucesso'] = 'Cadastro realizado com sucesso!';
                if ($expectsJson) {
                    $this->jsonResponse([
                        'ok' => true,
                        'message' => 'Cadastro realizado com sucesso! Faça login para continuar.',
                    ]);
                    return;
                }

                $this->redirect('login');
                return;
            }

            $erro = is_string($resultado) ? $resultado : 'Não foi possível concluir o cadastro.';
            if ($expectsJson) {
                $this->jsonResponse(['ok' => false, 'message' => $erro], 500);
                return;
            }

            $this->view('auth.login_register', ['erroRegistro' => $erro]);
            return;
        }

        $this->view('auth.login_register', ['erroRegistro' => null]);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        $this->redirect('');
    }

    public function forgotPassword(): void
    {
        $this->view('auth.forgot_password');
    }

    public function sendReset(): void
    {
        require_csrf($_POST['_csrf'] ?? null);

        $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);

        if (!$email) {
            $_SESSION['erro_reset'] = 'Informe um e-mail válido.';
            $this->redirect('forgot-password');
            return;
        }

        $usuario = $this->userModel->findByEmail($email);

        if (!$usuario) {
            $_SESSION['erro_reset'] = 'E-mail não encontrado.';
            $this->redirect('forgot-password');
            return;
        }

        $token = bin2hex(random_bytes(32));
        $this->userModel->saveResetToken((int) $usuario['id'], $token);

        $resetLink = url('reset-password') . '?token=' . urlencode($token);

        $this->view('auth.reset_link', ['resetLink' => $resetLink]);
    }

    public function resetPassword(): void
    {
        $token = trim($_GET['token'] ?? '');

        if ($token === '') {
            die('Token inválido.');
        }

        $usuario = $this->userModel->findByResetToken($token);

        if (!$usuario) {
            die('Token expirado ou inválido.');
        }

        $this->view('auth.reset_password', ['token' => $token]);
    }

    public function updatePassword(): void
    {
        require_csrf($_POST['_csrf'] ?? null);

        $token = trim($_POST['token'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        $senhaRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/';

        if (!preg_match($senhaRegex, $senha)) {
            $_SESSION['erro_reset'] = 'Senha inválida.';
            header('Location: ' . url('reset-password') . '?token=' . urlencode($token));
            exit;
        }

        $usuario = $this->userModel->findByResetToken($token);

        if (!$usuario) {
            die('Token inválido ou expirado.');
        }

        $this->userModel->updatePassword((int) $usuario['id'], $senha);

        $_SESSION['auth_success'] = 'Senha alterada com sucesso! Faça login com sua nova senha.';
        header('Location: ' . url('') . '?auth=login');
        exit;
    }

    private function expectsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';

        return stripos((string) $accept, 'application/json') !== false
            || strtolower((string) $requestedWith) === 'xmlhttprequest';
    }

    private function jsonResponse(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
