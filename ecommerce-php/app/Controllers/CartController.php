<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;
use App\Models\User;
use PDO;

class CartController extends Controller
{
    private Cart $cartModel;
    private User $userModel;

    public function __construct(private PDO $pdo)
    {
        $this->cartModel = new Cart($pdo);
        $this->userModel = new User($pdo);
    }

    public function index(): void
    {
        $this->view('cart.index', [
            'itens_carrinho' => $this->cartModel->items(),
            'total_carrinho' => $this->cartModel->total(),
            'usuario'        => $this->currentUser(),
            'pdo'            => $this->pdo,
        ]);
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);

            $productId = (int) ($_POST['produto_id'] ?? 0);
            $quantity  = max(1, min(999, (int) ($_POST['quantidade'] ?? 1)));

            if ($productId > 0) {
                $this->cartModel->add($productId, $quantity);
            }
        }

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            $this->json();
            return;
        }

        $this->redirect('carrinho');
    }

    public function update(): void
    {
        require_csrf($_POST['_csrf'] ?? null);

        $productId = (int) ($_POST['produto_id'] ?? 0);
        $quantity  = max(1, min(999, (int) ($_POST['quantidade'] ?? 1)));

        if ($productId > 0) {
            $this->cartModel->update($productId, $quantity);
        }

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            $this->json();
            return;
        }

        $this->redirect('carrinho');
    }

    public function remove(): void
    {
        require_csrf($_POST['_csrf'] ?? null);

        $productId = (int) ($_POST['produto_id'] ?? 0);
        if ($productId > 0) {
            $this->cartModel->remove($productId);
        }

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            $this->json();
            return;
        }

        $this->redirect('carrinho');
    }

    public function finalize(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);
        }

        $usuario = $this->currentUser();
        $this->cartModel->clearAllForCurrentUser();

        $this->view('cart.finalized', [
            'usuario' => $usuario,
        ]);
    }

    private function json(): void
    {
        $itens   = $this->cartModel->items();
        $payload = [];

        foreach ($itens as $productId => $quantity) {
            $stmt = $this->pdo->prepare('SELECT id, nome, preco, imagem FROM produtos WHERE id = :id');
            $stmt->execute(['id' => (int) $productId]);
            $product = $stmt->fetch();

            if (!$product) continue;

            $preco    = (float) $product['preco'];
            $quantity = (int) $quantity;

            $payload[] = [
                'id'         => (int) $product['id'],
                'nome'       => $product['nome'],
                'preco'      => $preco,
                'imagem'     => $product['imagem'] ?? '',
                'quantidade' => $quantity,
                'subtotal'   => $preco * $quantity,
            ];
        }

        $total      = array_sum(array_column($payload, 'subtotal'));
        $totalItens = array_sum(array_column($payload, 'quantidade'));

        header('Content-Type: application/json');
        echo json_encode([
            'itens'       => $payload,
            'total'       => $total,
            'total_itens' => $totalItens,
        ]);
        exit;
    }

    private function currentUser(): array
    {
        $default = [
            'logado'   => false,
            'nome'     => '',
            'imagem'   => '',
            'is_admin' => false,
        ];

        if (!isset($_SESSION['usuario_id'])) {
            return $default;
        }

        $dbUser = $this->userModel->findById((int) $_SESSION['usuario_id']);
        if (!$dbUser) {
            return $default;
        }

        return [
            'logado'   => true,
            'nome'     => $dbUser['nome']     ?? '',
            'imagem'   => $dbUser['imagem']   ?? '',
            'is_admin' => (bool) ($dbUser['is_admin'] ?? false),
        ];
    }
}
