<?php

namespace App\Models;

use PDO;

class Product
{
    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM produtos');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM produtos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        return $product ?: null;
    }

    public function related(string $category, int $excludeId, int $limit = 4): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM produtos WHERE categoria = :categoria AND id != :id LIMIT ' . (int) $limit);
        $stmt->execute([
            'categoria' => $category,
            'id' => $excludeId,
        ]);

        return $stmt->fetchAll();
    }

    public function create(string $nome, string $descricao, float $preco, string $categoria, string $imagem): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO produtos (nome, descricao, preco, categoria, imagem) VALUES (:nome, :descricao, :preco, :categoria, :imagem)');
        return $stmt->execute([
            'nome' => $nome,
            'descricao' => $descricao,
            'preco' => $preco,
            'categoria' => $categoria,
            'imagem' => $imagem,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $sets = [];
        foreach ($data as $field => $value) {
            $sets[] = $field . ' = :' . $field;
        }

        $sql = 'UPDATE produtos SET ' . implode(', ', $sets) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM produtos WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function countAll(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) AS total FROM produtos');
        return (int) $stmt->fetch()['total'];
    }
}
