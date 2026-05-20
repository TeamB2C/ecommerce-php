<?php

namespace App\Models;

use PDO;

class Categoria
{
  public function __construct(private PDO $pdo) {}

  public function all(): array
  {
    $stmt = $this->pdo->query('SELECT * FROM categorias ORDER BY nome ASC');
    return $stmt->fetchAll();
  }

  public function find(int $id): ?array
  {
    $stmt = $this->pdo->prepare('SELECT * FROM categorias WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $categoria = $stmt->fetch();
    return $categoria ?: null;
  }

  public function create(string $nome, string $descricao = ''): bool
  {
    $stmt = $this->pdo->prepare('INSERT INTO categorias (nome, descricao) VALUES (:nome, :descricao)');
    return $stmt->execute(['nome' => $nome, 'descricao' => $descricao]);
  }

  public function update(int $id, array $data): bool
  {
    if (empty($data)) return false;

    $sets = [];
    foreach ($data as $field => $value) {
      $sets[] = $field . ' = :' . $field;
    }

    $sql = 'UPDATE categorias SET ' . implode(', ', $sets) . ' WHERE id = :id';
    $stmt = $this->pdo->prepare($sql);
    $data['id'] = $id;
    return $stmt->execute($data);
  }

  public function delete(int $id): bool
  {
    $stmt = $this->pdo->prepare('DELETE FROM categorias WHERE id = :id');
    return $stmt->execute(['id' => $id]);
  }

  public function countAll(): int
  {
    $stmt = $this->pdo->query('SELECT COUNT(*) AS total FROM categorias');
    return (int) $stmt->fetch()['total'];
  }

  public function existsByName(string $nome, ?int $excludeId = null): bool
  {
    if ($excludeId !== null) {
      $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM categorias WHERE nome = :nome AND id != :id');
      $stmt->execute(['nome' => $nome, 'id' => $excludeId]);
    } else {
      $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM categorias WHERE nome = :nome');
      $stmt->execute(['nome' => $nome]);
    }
    return (int) $stmt->fetchColumn() > 0;
  }
}
