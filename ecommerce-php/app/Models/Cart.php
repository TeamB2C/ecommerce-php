<?php

namespace App\Models;

use PDO;
use Throwable;

class Cart
{
    private ?bool $cartTableAvailable = null;

    public function __construct(private PDO $pdo)
    {
    }

    public function items(): array
    {
        $sessionCart = isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];

        $userId = $this->currentUserId();
        if ($userId === null || !$this->supportsDbCart()) {
            return $sessionCart;
        }

        $dbCart = $this->loadFromDatabase($userId);
        // Preserve current session order to avoid visual reordering in the cart UI.
        $mergedCart = $sessionCart;

        // Merge database state without changing existing item order.
        foreach ($dbCart as $productId => $quantity) {
            $productId = (int) $productId;
            $quantity = max(1, (int) $quantity);
            if ($productId <= 0) {
                continue;
            }

            if (isset($mergedCart[$productId])) {
                $mergedCart[$productId] = max((int) $mergedCart[$productId], $quantity);
            } else {
                $mergedCart[$productId] = $quantity;
            }
        }

        $_SESSION['carrinho'] = $mergedCart;

        if (!empty($mergedCart)) {
            $this->syncSessionToDatabase();
        }

        return $mergedCart;
    }

    public function add(int $productId, int $quantity): void
    {
        if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        if (isset($_SESSION['carrinho'][$productId])) {
            $_SESSION['carrinho'][$productId] += $quantity;
        } else {
            $_SESSION['carrinho'][$productId] = $quantity;
        }

        $this->saveItemToDatabase($productId, (int) $_SESSION['carrinho'][$productId]);
    }

    public function update(int $productId, int $quantity): void
    {
        if (isset($_SESSION['carrinho'][$productId])) {
            $_SESSION['carrinho'][$productId] = max(1, $quantity);
            $this->saveItemToDatabase($productId, (int) $_SESSION['carrinho'][$productId]);
        }
    }

    public function remove(int $productId): void
    {
        if (isset($_SESSION['carrinho'][$productId])) {
            unset($_SESSION['carrinho'][$productId]);
        }

        $this->deleteItemFromDatabase($productId);
    }

    public function clearSessionCart(): void
    {
        unset($_SESSION['carrinho']);
    }

    public function clearAllForCurrentUser(): void
    {
        $this->clearSessionCart();

        $userId = $this->currentUserId();
        if ($userId === null || !$this->supportsDbCart()) {
            return;
        }

        try {
            $stmt = $this->pdo->prepare('DELETE FROM carrinho WHERE usuario_id = :usuario_id');
            $stmt->execute(['usuario_id' => $userId]);
        } catch (Throwable) {
            // Fallback silencioso para manter funcionalidade mesmo sem tabela.
        }
    }

    public function syncSessionToDatabase(): void
    {
        $userId = $this->currentUserId();
        if ($userId === null || !$this->supportsDbCart()) {
            return;
        }

        $items = isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
        foreach ($items as $productId => $quantity) {
            $this->saveItemToDatabase((int) $productId, (int) $quantity);
        }
    }

    public function countItems(): int
    {
        return count($this->items());
    }

    public function total(): float
    {
        $total = 0;

        foreach ($this->items() as $productId => $quantity) {
            $stmt = $this->pdo->prepare('SELECT preco FROM produtos WHERE id = :id');
            $stmt->execute(['id' => (int) $productId]);
            $product = $stmt->fetch();

            if ($product) {
                $total += ((float) $product['preco']) * ((int) $quantity);
            }
        }

        return $total;
    }

    public function detailedItems(): array
    {
        $detailed = [];

        foreach ($this->items() as $productId => $quantity) {
            $stmt = $this->pdo->prepare('SELECT * FROM produtos WHERE id = :id');
            $stmt->execute(['id' => (int) $productId]);
            $product = $stmt->fetch();

            if (!$product) {
                continue;
            }

            $qty = max(1, (int) $quantity);
            $price = (float) ($product['preco'] ?? 0);

            $detailed[] = [
                'produto' => $product,
                'quantidade' => $qty,
                'subtotal' => $price * $qty,
            ];
        }

        return $detailed;
    }

    private function currentUserId(): ?int
    {
        return isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
    }

    private function supportsDbCart(): bool
    {
        if ($this->cartTableAvailable !== null) {
            return $this->cartTableAvailable;
        }

        try {
            $this->pdo->query('SELECT 1 FROM carrinho LIMIT 1');
            $this->cartTableAvailable = true;
        } catch (Throwable) {
            $this->cartTableAvailable = false;
        }

        return $this->cartTableAvailable;
    }

    private function loadFromDatabase(int $userId): array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT produto_id, quantidade FROM carrinho WHERE usuario_id = :usuario_id ORDER BY produto_id ASC');
            $stmt->execute(['usuario_id' => $userId]);
            $rows = $stmt->fetchAll();
        } catch (Throwable) {
            return [];
        }

        $items = [];
        foreach ($rows as $row) {
            $produtoId = (int) ($row['produto_id'] ?? 0);
            $quantidade = max(1, (int) ($row['quantidade'] ?? 1));
            if ($produtoId > 0) {
                $items[$produtoId] = $quantidade;
            }
        }

        return $items;
    }

    private function saveItemToDatabase(int $productId, int $quantity): void
    {
        $userId = $this->currentUserId();
        if ($userId === null || !$this->supportsDbCart()) {
            return;
        }

        try {
            $update = $this->pdo->prepare('UPDATE carrinho SET quantidade = :quantidade WHERE usuario_id = :usuario_id AND produto_id = :produto_id');
            $update->execute([
                'quantidade' => max(1, $quantity),
                'usuario_id' => $userId,
                'produto_id' => $productId,
            ]);

            if ($update->rowCount() === 0) {
                $insert = $this->pdo->prepare('INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (:usuario_id, :produto_id, :quantidade)');
                $insert->execute([
                    'usuario_id' => $userId,
                    'produto_id' => $productId,
                    'quantidade' => max(1, $quantity),
                ]);
            }
        } catch (Throwable) {
            // Fallback silencioso para manter fluxo funcionando sem tabela/colunas esperadas.
        }
    }

    private function deleteItemFromDatabase(int $productId): void
    {
        $userId = $this->currentUserId();
        if ($userId === null || !$this->supportsDbCart()) {
            return;
        }

        try {
            $stmt = $this->pdo->prepare('DELETE FROM carrinho WHERE usuario_id = :usuario_id AND produto_id = :produto_id');
            $stmt->execute([
                'usuario_id' => $userId,
                'produto_id' => $productId,
            ]);
        } catch (Throwable) {
            // Fallback silencioso.
        }
    }
}
