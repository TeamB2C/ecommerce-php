<?php

namespace App\Models;

use PDO;

class User
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM usuarios WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM usuarios WHERE email = :email'
        );

        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function createCustomer(
        string $nome,
        string $email,
        string $senha
    ): bool|string {

        if ($this->findByEmail($email)) {
            return 'E-mail já cadastrado!';
        }

        $senhaHash =
            password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare(
            'INSERT INTO usuarios
            (nome, email, senha, is_admin, imagem)
            VALUES
            (:nome, :email, :senha, 0, NULL)'
        );

        return $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senhaHash,
        ]);
    }

    public function updateProfile(
        int $id,
        array $data
    ): bool {

        if (empty($data)) {
            return false;
        }

        $sets = [];

        foreach ($data as $field => $value) {
            $sets[] = $field . ' = :' . $field;
        }

        $sql =
            'UPDATE usuarios SET '
            . implode(', ', $sets)
            . ' WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);

        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function countCustomers(): int
    {
        $stmt = $this->pdo->query(
            'SELECT COUNT(*) AS total
             FROM usuarios
             WHERE is_admin = 0'
        );

        return (int) $stmt->fetch()['total'];
    }

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function saveResetToken(
        int $userId,
        string $token
    ): bool {

        $stmt = $this->pdo->prepare(
            'UPDATE usuarios
             SET
                reset_token = :token,
                reset_token_expira_em = DATE_ADD(NOW(), INTERVAL 1 HOUR)
             WHERE id = :id'
        );

        return $stmt->execute([
            'token' => $token,
            'id' => $userId
        ]);
    }

    public function findByResetToken(
        string $token
    ): ?array {

        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM usuarios
             WHERE reset_token = :token
             AND reset_token_expira_em > NOW()
             LIMIT 1'
        );

        $stmt->execute([
            'token' => $token
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function updatePassword(
        int $id,
        string $novaSenha
    ): bool {

        $senhaHash =
            password_hash($novaSenha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare(
            'UPDATE usuarios
             SET
                senha = :senha,
                reset_token = NULL,
                reset_token_expira_em = NULL
             WHERE id = :id'
        );

        return $stmt->execute([
            'senha' => $senhaHash,
            'id' => $id
        ]);
    }
}