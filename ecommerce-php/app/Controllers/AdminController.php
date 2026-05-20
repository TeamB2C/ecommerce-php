<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Categoria;
use App\Models\Product;
use App\Models\User;
use Exception;
use PDO;

class AdminController extends Controller
{
    private const MAX_IMAGE_SIZE = 5_242_880;

    private Product $productModel;
    private Categoria $categoriaModel;
    private User $userModel;

    public function __construct(private PDO $pdo)
    {
        $this->productModel   = new Product($pdo);
        $this->categoriaModel = new Categoria($pdo);
        $this->userModel      = new User($pdo);
    }

    public function login(): void
    {
        $this->redirect('login');
    }

    public function dashboard(): void
    {
        $this->guardAdmin();

        $usuario      = $this->userModel->findById((int) $_SESSION['usuario_id']);
        $pedidosTotal = 0;
        $faturamento  = 0.0;

        try {
            $q            = $this->pdo->query('SELECT COUNT(*) AS total FROM pedidos');
            $pedidosTotal = (int) $q->fetch()['total'];
        } catch (Exception) {
            $pedidosTotal = 0;
        }

        try {
            $q           = $this->pdo->query("SELECT SUM(valor_total) AS faturamento FROM pedidos WHERE status <> 'cancelado'");
            $faturamento = (float) ($q->fetch()['faturamento'] ?? 0);
        } catch (Exception) {
            $faturamento = 0;
        }

        $this->view('admin.dashboard', [
            'usuario'        => $usuario,
            'produtos'       => $this->productModel->all(),
            'produtos_total' => $this->productModel->countAll(),
            'pedidos_total'  => $pedidosTotal,
            'clientes_total' => $this->userModel->countCustomers(),
            'faturamento'    => $faturamento,
        ]);
    }

    public function products(): void
    {
        $this->guardAdmin();

        $sucesso = $_GET['sucesso'] ?? null;
        $erro    = $_GET['error']  ?? null;

        $mensagens = [
            'produto-adicionado' => 'Produto adicionado com sucesso.',
            'produto-atualizado' => 'Produto atualizado com sucesso.',
            'produto-removido'   => 'Produto removido com sucesso.',
        ];

        $this->view('admin.produtos', [
            'produtos' => $this->productModel->all(),
            'sucesso'  => $mensagens[$sucesso] ?? null,
            'erro'     => $erro,
        ]);
    }

    public function addProduct(): void
    {
        $this->guardAdmin();
        $erro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);

            $nome      = trim((string) ($_POST['nome']     ?? ''));
            $descricao = trim((string) ($_POST['descricao'] ?? ''));
            $precoInput = str_replace(',', '.', (string) ($_POST['preco'] ?? ''));
            $preco     = filter_var($precoInput, FILTER_VALIDATE_FLOAT);
            $categoria = trim((string) ($_POST['categoria'] ?? ''));
            $estoque   = max(0, (int) ($_POST['estoque']   ?? 0));

            if ($nome === '' || $descricao === '' || $categoria === '' || $preco === false || $preco <= 0) {
                $erro = 'Preencha todos os campos corretamente.';
            }

            $nomeImagem = null;
            if (!$erro) {
                $nomeImagem = $this->handleProductImageUpload($_FILES['foto'] ?? null, true, $erro);
            }

            if (!$erro && $nomeImagem && $this->productModel->create($nome, $descricao, (float) $preco, $categoria, $nomeImagem, $estoque)) {
                $this->redirect('admin/produtos?sucesso=produto-adicionado');
            }

            if (!$erro) {
                $erro = 'Erro ao salvar produto no banco.';
            }
        }

        $this->view('admin.add_product', [
            'erro'       => $erro,
            'categorias' => $this->categoriaModel->all(),
        ]);
    }

    public function editProduct(): void
    {
        $this->guardAdmin();

        $produtoId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($produtoId <= 0) {
            $this->redirect('admin/produtos');
        }

        $produto = $this->productModel->find($produtoId);
        if (!$produto) {
            $this->redirect('admin/produtos?error=Produto não encontrado');
        }

        $erro    = '';
        $sucesso = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);

            $precoInput = str_replace(',', '.', (string) ($_POST['preco'] ?? ''));
            $preco      = filter_var($precoInput, FILTER_VALIDATE_FLOAT);

            $data = [
                'nome'      => trim((string) ($_POST['nome']      ?? '')),
                'descricao' => trim((string) ($_POST['descricao'] ?? '')),
                'preco'     => $preco,
                'categoria' => trim((string) ($_POST['categoria'] ?? '')),
                'estoque'   => max(0, (int) ($_POST['estoque']    ?? 0)),
                'imagem'    => $produto['imagem'],
            ];

            if ($data['nome'] === '' || $data['descricao'] === '' || $data['categoria'] === '' || $preco === false || $preco <= 0) {
                $erro = 'Preencha todos os campos corretamente.';
            }

            if (!$erro && isset($_FILES['foto']) && ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                $novaImagem = $this->handleProductImageUpload($_FILES['foto'], false, $erro);
                if ($novaImagem) {
                    $imagemAntiga = __DIR__ . '/../../assets/images/produtos/' . $produto['imagem'];
                    if (!empty($produto['imagem']) && file_exists($imagemAntiga)) {
                        @unlink($imagemAntiga);
                    }
                    $data['imagem'] = $novaImagem;
                }
            }

            if (!$erro && $this->productModel->update($produtoId, $data)) {
                $sucesso = 'Produto atualizado com sucesso.';
                $produto = $this->productModel->find($produtoId) ?? $produto;
            }

            if (!$erro && !$sucesso) {
                $erro = 'Erro ao atualizar o produto.';
            }
        }

        $this->view('admin.edit_product', [
            'produto'    => $produto,
            'produto_id' => $produtoId,
            'erro'       => $erro,
            'sucesso'    => $sucesso,
            'categorias' => $this->categoriaModel->all(),
        ]);
    }

    public function removeProduct(): void
    {
        $this->guardAdmin();

        require_csrf($_POST['_csrf'] ?? null);

        $produtoId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($produtoId <= 0) {
            $this->redirect('admin/produtos');
        }

        $produto = $this->productModel->find($produtoId);
        if (!$produto) {
            $this->redirect('admin/produtos?error=Produto não encontrado');
        }

        if ($this->productModel->delete($produtoId)) {
            $caminhoImagem = __DIR__ . '/../../assets/images/produtos/' . $produto['imagem'];
            if (!empty($produto['imagem']) && file_exists($caminhoImagem)) {
                @unlink($caminhoImagem);
            }
            $this->redirect('admin/produtos?sucesso=produto-removido');
        }

        $this->redirect('admin/produtos?error=Erro ao remover produto');
    }

    public function categories(): void
    {
        $this->guardAdmin();

        $sucesso = $_GET['sucesso'] ?? null;
        $erro    = $_GET['error']  ?? null;

        $mensagens = [
            'categoria-adicionada' => 'Categoria adicionada com sucesso.',
            'categoria-atualizada' => 'Categoria atualizada com sucesso.',
            'categoria-removida'   => 'Categoria removida com sucesso.',
        ];

        $this->view('admin.categorias', [
            'categorias' => $this->categoriaModel->all(),
            'sucesso'    => $mensagens[$sucesso] ?? null,
            'erro'       => $erro,
        ]);
    }

    public function addCategory(): void
    {
        $this->guardAdmin();
        $erro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);

            $nome      = trim((string) ($_POST['nome']      ?? ''));
            $descricao = trim((string) ($_POST['descricao'] ?? ''));

            if ($nome === '') {
                $erro = 'O nome da categoria é obrigatório.';
            } elseif ($this->categoriaModel->existsByName($nome)) {
                $erro = 'Já existe uma categoria com esse nome.';
            }

            if (!$erro && $this->categoriaModel->create($nome, $descricao)) {
                $this->redirect('admin/categorias?sucesso=categoria-adicionada');
            }

            if (!$erro) {
                $erro = 'Erro ao salvar categoria no banco.';
            }
        }

        $this->view('admin.adicionar_categoria', ['erro' => $erro]);
    }

    public function editCategory(): void
    {
        $this->guardAdmin();

        $categoriaId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($categoriaId <= 0) {
            $this->redirect('admin/categorias');
        }

        $categoria = $this->categoriaModel->find($categoriaId);
        if (!$categoria) {
            $this->redirect('admin/categorias?error=Categoria não encontrada');
        }

        $erro    = '';
        $sucesso = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf($_POST['_csrf'] ?? null);

            $nome      = trim((string) ($_POST['nome']      ?? ''));
            $descricao = trim((string) ($_POST['descricao'] ?? ''));

            if ($nome === '') {
                $erro = 'O nome da categoria é obrigatório.';
            } elseif ($this->categoriaModel->existsByName($nome, $categoriaId)) {
                $erro = 'Já existe outra categoria com esse nome.';
            }

            if (!$erro && $this->categoriaModel->update($categoriaId, ['nome' => $nome, 'descricao' => $descricao])) {
                $sucesso   = 'Categoria atualizada com sucesso.';
                $categoria = $this->categoriaModel->find($categoriaId) ?? $categoria;
            }

            if (!$erro && !$sucesso) {
                $erro = 'Erro ao atualizar a categoria.';
            }
        }

        $this->view('admin.editar_categoria', [
            'categoria'    => $categoria,
            'categoria_id' => $categoriaId,
            'erro'         => $erro,
            'sucesso'      => $sucesso,
        ]);
    }

    public function removeCategory(): void
    {
        $this->guardAdmin();

        require_csrf($_POST['_csrf'] ?? null);

        $categoriaId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($categoriaId <= 0) {
            $this->redirect('admin/categorias');
        }

        if (!$this->categoriaModel->find($categoriaId)) {
            $this->redirect('admin/categorias?error=Categoria não encontrada');
        }

        if ($this->categoriaModel->delete($categoriaId)) {
            $this->redirect('admin/categorias?sucesso=categoria-removida');
        }

        $this->redirect('admin/categorias?error=Erro ao remover categoria');
    }

    private function guardAdmin(): void
    {
        if (!isset($_SESSION['usuario_id']) || (int) ($_SESSION['is_admin'] ?? 0) !== 1) {
            $this->redirect('login');
        }
    }

    private function handleProductImageUpload(?array $file, bool $required, ?string &$erro = null): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            if ($required) {
                $erro = 'Imagem do produto é obrigatória.';
            }
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $erro = 'Erro no upload da imagem.';
            return null;
        }

        if (($file['size'] ?? 0) > self::MAX_IMAGE_SIZE) {
            $erro = 'Imagem muito grande. Limite de 5MB.';
            return null;
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        $finfo   = finfo_open(FILEINFO_MIME_TYPE);
        $mime    = $finfo ? finfo_file($finfo, $tmpName) : false;
        if ($finfo) {
            finfo_close($finfo);
        }

        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
        ];

        if (!is_string($mime) || !isset($allowedMimes[$mime])) {
            $erro = 'Formato de imagem inválido. Use JPG ou PNG.';
            return null;
        }

        $newName        = uniqid('', true) . '.' . $allowedMimes[$mime];
        $destinationDir = __DIR__ . '/../../assets/images/produtos/';

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (!move_uploaded_file($tmpName, $destinationDir . $newName)) {
            $erro = 'Falha ao salvar a imagem.';
            return null;
        }

        return $newName;
    }
}
