<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Categorias - Um Convite de Casamento</title>
  <link rel="stylesheet" href="../admin/css/painel.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    .category-row {
      transition: all 0.2s ease;
    }

    .category-row:hover {
      background-color: #fff9fb;
    }
  </style>
</head>

<body class="text-gray-800 bg-[#f8fafc]">
  <div class="flex min-h-screen">
    <?php
    $current = 'categorias';
    include __DIR__ . '/../partials/admin/sidebar.php';
    ?>

    <div class="flex-1 p-4 md:p-10 space-y-8 min-w-0">
      <?php
      $title = '';
      include __DIR__ . '/../partials/admin/topbar.php';
      ?>

      <?php if (!empty($sucesso) || !empty($erro)): ?>
        <div>
          <?php if (!empty($sucesso)): ?>
            <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
              <i class="fas fa-check-circle"></i>
              <p class="text-sm font-medium"><?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
          <?php if (!empty($erro)): ?>
            <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
              <i class="fas fa-exclamation-circle"></i>
              <p class="text-sm font-medium"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="glass-card rounded-3xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100">
          <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Categorias</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">Organize seus produtos por tipo ou estilo</p>
          </div>
          <a href="<?php echo url('admin/categorias/adicionar'); ?>"
            class="btn-grad inline-flex items-center gap-2 px-6 py-3 text-white text-sm font-bold rounded-2xl">
            <i class="fas fa-plus"></i>
            Adicionar Categoria
          </a>
        </div>

        <!-- Tabela Desktop -->
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-gray-50/50">
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-16">#</th>
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Nome</th>
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Descrição</th>
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                  <tr class="category-row group">
                    <td class="px-8 py-5">
                      <span class="text-xs font-bold text-gray-300 group-hover:text-pink-300 transition-colors">
                        #<?php echo str_pad((string) $categoria['id'], 3, '0', STR_PAD_LEFT); ?>
                      </span>
                    </td>
                    <td class="px-8 py-5">
                      <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl flex items-center justify-center bg-pink-50 flex-shrink-0">
                          <i class="fas fa-tag text-pink-400 text-xs"></i>
                        </div>
                        <span class="font-bold text-gray-900">
                          <?php echo htmlspecialchars($categoria['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                      </div>
                    </td>
                    <td class="px-8 py-5">
                      <p class="text-gray-400 text-sm max-w-xs truncate">
                        <?php echo htmlspecialchars(
                          !empty($categoria['descricao']) ? $categoria['descricao'] : 'Sem descrição.',
                          ENT_QUOTES,
                          'UTF-8'
                        ); ?>
                      </p>
                    </td>
                    <td class="px-8 py-5">
                      <div class="flex gap-2 justify-end">
                        <a href="<?php echo url('admin/categorias/editar'); ?>?id=<?php echo (int) $categoria['id']; ?>"
                          class="h-9 w-9 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                          title="Editar">
                          <i class="fas fa-edit text-sm"></i>
                        </a>
                        <form id="remover-cat-<?php echo (int) $categoria['id']; ?>"
                          action="<?php echo url('admin/categorias/remover'); ?>" method="POST" class="m-0">
                          <?php echo csrf_field(); ?>
                          <input type="hidden" name="id" value="<?php echo (int) $categoria['id']; ?>">
                          <button type="button" onclick="confirmarRemocaoCategoria(<?php echo (int) $categoria['id']; ?>)"
                            class="h-9 w-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm"
                            title="Remover">
                            <i class="fas fa-trash text-sm"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="py-20 text-center">
                    <div class="flex flex-col items-center gap-3 text-gray-300">
                      <i class="fas fa-tags text-5xl"></i>
                      <p class="text-sm font-bold text-gray-400">Nenhuma categoria encontrada</p>
                      <a href="<?php echo url('admin/categorias/adicionar'); ?>"
                        class="btn-grad text-white text-xs font-bold px-4 py-2 rounded-xl mt-1">
                        Criar primeira categoria
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <script>
    function confirmarRemocaoCategoria(id) {
      Swal.fire({
        title: 'Remover Categoria?',
        text: "Os produtos vinculados não serão apagados, mas ficarão sem categoria.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sim, remover!',
        cancelButtonText: 'Manter',
        background: '#fff'
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.getElementById('remover-cat-' + id);
          if (form) form.submit();
        }
      });
    }
  </script>
</body>

</html>