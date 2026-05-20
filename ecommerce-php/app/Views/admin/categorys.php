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
</head>

<body class="text-gray-800 bg-gray-100">
  <div class="flex min-h-screen">
    <?php
    $current = 'categorias';
    include __DIR__ . '/../partials/admin/sidebar.php';
    ?>

    <div class="flex-1 p-4 md:p-6 space-y-6 min-w-0">
      <?php
      $title = 'Categorias';
      include __DIR__ . '/../partials/admin/topbar.php';
      ?>

      <?php if (!empty($sucesso)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
          <p><?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>

      <?php if (!empty($erro)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
          <p><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>

      <div class="bg-white rounded-xl shadow p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
          <div>
            <h3 class="text-lg font-semibold text-gray-800">Lista de Categorias</h3>
            <p class="text-sm text-gray-500"><?php echo count($categorias ?? []); ?> categoria(s) cadastrada(s)</p>
          </div>
          <a href="<?php echo url('admin/categorias/adicionar'); ?>"
            class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white text-sm font-medium rounded-lg hover:bg-pink-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Adicionar Categoria
          </a>
        </div>

        <!-- Tabela desktop -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="bg-pink-50 text-pink-600">
              <tr>
                <th class="px-4 py-3 rounded-tl-lg">#</th>
                <th class="px-4 py-3">Nome</th>
                <th class="px-4 py-3">Descrição</th>
                <th class="px-4 py-3 rounded-tr-lg text-right">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                  <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-400 text-xs"><?php echo (int) $categoria['id']; ?></td>
                    <td class="px-4 py-3 font-medium text-gray-800">
                      <?php echo htmlspecialchars($categoria['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">
                      <?php echo htmlspecialchars($categoria['descricao'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="px-4 py-3">
                      <div class="flex gap-2 justify-end">
                        <a href="<?php echo url('admin/categorias/editar'); ?>?id=<?php echo (int) $categoria['id']; ?>"
                          class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg text-xs font-medium hover:bg-yellow-600 transition-colors">Editar</a>
                        <form id="remover-cat-<?php echo (int) $categoria['id']; ?>"
                          action="<?php echo url('admin/categorias/remover'); ?>" method="POST" class="m-0">
                          <?php echo csrf_field(); ?>
                          <input type="hidden" name="id" value="<?php echo (int) $categoria['id']; ?>">
                          <button type="button" onclick="confirmarRemocaoCategoria(<?php echo (int) $categoria['id']; ?>)"
                            class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-medium hover:bg-red-700 transition-colors">Remover</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center text-gray-400 py-10">
                    <div class="flex flex-col items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z" />
                      </svg>
                      <span class="text-sm">Nenhuma categoria cadastrada</span>
                      <a href="<?php echo url('admin/categorias/adicionar'); ?>"
                        class="text-pink-600 text-sm hover:underline">Adicionar a primeira categoria</a>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Cards mobile -->
        <div class="md:hidden space-y-3">
          <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $categoria): ?>
              <div class="bg-gray-50 rounded-lg p-3">
                <div class="flex items-start justify-between gap-2">
                  <div class="min-w-0">
                    <p class="font-medium text-gray-800 text-sm">
                      <?php echo htmlspecialchars($categoria['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php if (!empty($categoria['descricao'])): ?>
                      <p class="text-xs text-gray-500 mt-0.5 truncate">
                        <?php echo htmlspecialchars($categoria['descricao'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                  </div>
                  <div class="flex gap-1.5 flex-shrink-0">
                    <a href="<?php echo url('admin/categorias/editar'); ?>?id=<?php echo (int) $categoria['id']; ?>"
                      class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Editar</a>
                    <form id="remover-cat-mobile-<?php echo (int) $categoria['id']; ?>"
                      action="<?php echo url('admin/categorias/remover'); ?>" method="POST" class="m-0">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="id" value="<?php echo (int) $categoria['id']; ?>">
                      <button type="button" onclick="confirmarRemocaoCategoria(<?php echo (int) $categoria['id']; ?>)"
                        class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Remover</button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center text-gray-400 py-10">
              <p class="text-sm">Nenhuma categoria cadastrada</p>
              <a href="<?php echo url('admin/categorias/adicionar'); ?>"
                class="text-pink-600 text-sm hover:underline mt-1 block">Adicionar a primeira categoria</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    function confirmarRemocaoCategoria(id) {
      Swal.fire({
        title: 'Remover categoria?',
        text: 'Os produtos vinculados não serão removidos.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, remover',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#e11d63'
      }).then(result => {
        if (result.isConfirmed) {
          const form = document.getElementById('remover-cat-' + id) ??
            document.getElementById('remover-cat-mobile-' + id);
          if (form) form.submit();
        }
      });
    }
  </script>
</body>

</html>