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

    /*
    |--------------------------------------------------------------------------
    | LOGIN + REGISTRO
    |--------------------------------------------------------------------------
    */

    public function loginRegister(): void
    {
        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && ($_POST['acao'] ?? '') === 'login'
        ) {

            require_csrf($_POST['_csrf'] ?? null);

            $email = filter_var(
                trim((string)($_POST['email'] ?? '')),
                FILTER_VALIDATE_EMAIL
            );

            $senha = trim((string)($_POST['senha'] ?? ''));

            if (!$email || $senha === '') {

                $_SESSION['erro_login'] =
                    'Informe e-mail e senha válidos.';

                $this->redirect('login');
                return;
            }

            $usuario = $this->userModel->findByEmail((string)$email);

            if (
                $usuario &&
                isset($usuario['senha']) &&
                password_verify($senha, $usuario['senha'])
            ) {

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['is_admin'] = $usuario['is_admin'];
                $_SESSION['imagem'] = $usuario['imagem'];

                $this->cartModel->syncSessionToDatabase();

                if ((int)$usuario['is_admin'] === 1) {

                    $this->redirect('admin');
                    return;
                }

                $this->redirect('');
                return;
            }

            $_SESSION['erro_login'] =
                'E-mail ou senha incorretos.';

            $this->redirect('login');
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | REGISTRO
        |--------------------------------------------------------------------------
        */

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && ($_POST['acao'] ?? '') === 'registrar'
        ) {

            require_csrf($_POST['_csrf'] ?? null);

            $nome = trim((string)($_POST['nome'] ?? ''));

            $email = filter_var(
                trim((string)($_POST['email'] ?? '')),
                FILTER_VALIDATE_EMAIL
            );

            $senha = trim((string)($_POST['senha'] ?? ''));

            $senhaRegex =
                '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/';

            if (
                $nome === ''
                || !$email
                || !preg_match($senhaRegex, $senha)
            ) {

                $erro =
                    'A senha deve conter no mínimo 8 caracteres, letra maiúscula, minúscula, número e caractere especial.';

                $this->view('auth.login_register', [
                    'erroRegistro' => $erro
                ]);

                return;
            }

            $usuarioExistente =
                $this->userModel->findByEmail((string)$email);

            if ($usuarioExistente) {

                $erro = 'Este e-mail já está cadastrado.';

                $this->view('auth.login_register', [
                    'erroRegistro' => $erro
                ]);

                return;
            }

            $resultado = $this->userModel->createCustomer(
                $nome,
                (string)$email,
                $senha
            );

            if ($resultado === true) {

                $_SESSION['sucesso'] =
                    'Cadastro realizado com sucesso!';

                $this->redirect('login');
                return;
            }

            $erro = is_string($resultado)
                ? $resultado
                : 'Não foi possível concluir o cadastro.';

            $this->view('auth.login_register', [
                'erroRegistro' => $erro
            ]);

            return;
        }

        $this->view('auth.login_register', [
            'erroRegistro' => null
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(): void
    {
        session_unset();
        session_destroy();

        $this->redirect('');
    }

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword(): void
    {
        $this->view('auth.forgot_password');
    }

/*
|--------------------------------------------------------------------------
| SEND RESET
|--------------------------------------------------------------------------
*/

public function sendReset(): void
{
    require_csrf($_POST['_csrf'] ?? null);

    $email = filter_var(
        trim((string)($_POST['email'] ?? '')),
        FILTER_VALIDATE_EMAIL
    );

    if (!$email) {

        $_SESSION['erro_reset'] =
            'Informe um e-mail válido.';

        $this->redirect('forgot-password');
        return;
    }

    $usuario = $this->userModel->findByEmail($email);

    if (!$usuario) {

        $_SESSION['erro_reset'] =
            'E-mail não encontrado.';

        $this->redirect('forgot-password');
        return;
    }

    $token = bin2hex(random_bytes(32));

    $this->userModel->saveResetToken(
        (int)$usuario['id'],
        $token
    );

    $resetLink =
        url('reset-password')
        . '?token=' .
        urlencode($token);

    $this->view('auth.reset_link', [
        'resetLink' => $resetLink
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD PAGE
    |--------------------------------------------------------------------------
    */

    public function resetPassword(): void
    {
        $token = trim($_GET['token'] ?? '');

        if ($token === '') {
            die('Token inválido.');
        }

        $usuario =
            $this->userModel->findByResetToken($token);

        if (!$usuario) {
            die('Token expirado ou inválido.');
        }

        $this->view('auth.reset_password', [
            'token' => $token
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function updatePassword(): void
    {
        require_csrf($_POST['_csrf'] ?? null);

        $token = trim($_POST['token'] ?? '');

        $senha = trim($_POST['senha'] ?? '');

        $senhaRegex =
            '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/';

        if (!preg_match($senhaRegex, $senha)) {

            $_SESSION['erro_reset'] =
                'Senha inválida.';

            header(
                'Location: ' .
                url('reset-password') .
                '?token=' .
                urlencode($token)
            );

            exit;
        }

        $usuario =
            $this->userModel->findByResetToken($token);

        if (!$usuario) {
            die('Token inválido ou expirado.');
        }

        $this->userModel->updatePassword(
            (int)$usuario['id'],
            $senha
        );

        $_SESSION['sucesso'] =
            'Senha alterada com sucesso!';

        $this->redirect('login');
    }
}