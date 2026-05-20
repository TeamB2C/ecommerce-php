<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Adicionar Categoria - Um Convite de Casamento</title>
  <link rel="stylesheet" href="../admin/css/painel.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="text-gray-800 bg-gray-100">
  <div class="flex min-h-screen">
    <?php
    $current = 'categorias';
    include __DIR__ . '/../partials/admin/sidebar.php';
    ?>

    <div class="flex-1 p-4 md:p-6 space-y-6 min-w-0">
      <?php
      $title = 'Adicionar Categoria';
      include __DIR__ . '/../partials/admin/topbar.php';
      ?>

      <div class="w-full max-w-xl bg-white p-6 md:p-8 rounded-xl shadow space-y-5">
        <div class="flex items-center gap-3">
          <a href="<?php echo url('admin/categorias'); ?>" class="text-gray-400 hover:text-pink-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
          <h3 class="text-base font-semibold text-gray-800">Nova Categoria</h3>
        </div>

        <?php if (!empty($erro)): ?>
          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
            <p class="font-bold text-sm">Erro</p>
            <p class="text-sm"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>

        <?php
        $mode = 'create';
        $action = url('admin/categorias/adicionar');
        $submitLabel = 'Salvar Categoria';
        include __DIR__ . '/../partials/admin/formulario_categoria.php';
        ?>
      </div>
    </div>
  </div>
</body>

</html>