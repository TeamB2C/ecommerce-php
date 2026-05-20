<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Adicionar Categoria - Painel Admin</title>
  <link rel="stylesheet" href="../admin/css/painel.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.97);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(229, 231, 235, 0.5);
    }

    .btn-grad {
      background: linear-gradient(135deg, #db2777 0%, #be185d 100%);
      transition: all 0.3s ease;
    }

    .btn-grad:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(219, 39, 119, 0.3);
    }

    .form-input {
      transition: all 0.2s ease;
    }

    .form-input:focus {
      border-color: #db2777;
      box-shadow: 0 0 0 3px rgba(219, 39, 119, 0.1);
      outline: none;
    }
  </style>
</head>

<body class="text-gray-800 bg-[#f8fafc]">
  <div class="flex min-h-screen">
    <?php
    $current = 'categorias';
    include __DIR__ . '/../partials/admin/sidebar.php';
    ?>

    <div class="flex-1 p-4 md:p-10 space-y-6 min-w-0">
      <?php
      $title = '';
      include __DIR__ . '/../partials/admin/topbar.php';
      ?>

      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="<?php echo url('admin/categorias'); ?>"
          class="hover:text-pink-600 transition-colors font-medium">Categorias</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-700 font-semibold">Adicionar Categoria</span>
      </div>

      <?php if (!empty($erro)): ?>
        <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
          <i class="fas fa-exclamation-circle text-red-500"></i>
          <p class="text-sm font-medium"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>

      <div class="glass-card rounded-3xl shadow-sm overflow-hidden max-w-xl">

        <!-- Header do card -->
        <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
          <a href="<?php echo url('admin/categorias'); ?>"
            class="h-9 w-9 flex items-center justify-center bg-gray-100 text-gray-500 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition-all">
            <i class="fas fa-arrow-left text-sm"></i>
          </a>
          <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Nova Categoria</h2>
            <p class="text-gray-400 text-xs font-medium mt-0.5">Preencha os dados da nova categoria</p>
          </div>
        </div>

        <!-- Formulário -->
        <form action="<?php echo htmlspecialchars(url('admin/categorias/adicionar'), ENT_QUOTES, 'UTF-8'); ?>"
          method="POST" class="px-8 py-7 space-y-5">
          <?php echo csrf_field(); ?>

          <div class="space-y-1.5">
            <label for="nome" class="block text-sm font-semibold text-gray-700">
              Nome da Categoria <span class="text-pink-500">*</span>
            </label>
            <input type="text" name="nome" id="nome" required placeholder="Ex: Convites, Lembranças, Decoração..."
              value="<?php echo old('nome'); ?>"
              class="form-input w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white" />
          </div>

          <div class="space-y-1.5">
            <label for="descricao" class="block text-sm font-semibold text-gray-700">
              Descrição
              <span class="text-gray-400 font-normal ml-1">(opcional)</span>
            </label>
            <textarea name="descricao" id="descricao" rows="3" placeholder="Descreva brevemente esta categoria..."
              class="form-input w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white resize-none"><?php echo old('descricao'); ?></textarea>
          </div>

          <div class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-gray-50">
            <button type="submit"
              class="btn-grad flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-2xl text-white text-sm font-bold">
              <i class="fas fa-plus-circle"></i>
              Salvar Categoria
            </button>
            <a href="<?php echo url('admin/categorias'); ?>"
              class="flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-2xl border border-gray-200 text-gray-500 text-sm font-semibold hover:bg-gray-50 transition-colors text-center">
              <i class="fas fa-times"></i>
              Cancelar
            </a>
          </div>
        </form>
      </div>

    </div>
  </div>
</body>

</html>