<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Produtos - Um Convite de Casamento</title>
  <link rel="stylesheet" href="../admin/css/painel.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="text-gray-800 bg-gray-100">
  <div class="flex min-h-screen">
    <?php
    $current = 'produtos';
    include __DIR__ . '/../partials/admin/sidebar.php';
    ?>

    <div class="flex-1 p-4 md:p-6 space-y-6 min-w-0">
      <?php
      $title = 'Produtos';
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
            <h3 class="text-lg font-semibold text-gray-800">Lista de Produtos</h3>
            <p class="text-sm text-gray-500"><?php echo count($produtos ?? []); ?> produto(s) cadastrado(s)</p>
          </div>
          <a href="<?php echo url('admin/adicionar'); ?>"
            class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white text-sm font-medium rounded-lg hover:bg-pink-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Adicionar Produto
          </a>
        </div>

        <!-- Tabela desktop -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="bg-pink-50 text-pink-600">
              <tr>
                <th class="px-4 py-3 rounded-tl-lg">Imagem</th>
                <th class="px-4 py-3">Nome</th>
                <th class="px-4 py-3">Categoria</th>
                <th class="px-4 py-3">Preço</th>
                <th class="px-4 py-3">Estoque</th>
                <th class="px-4 py-3 rounded-tr-lg text-right">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                  <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                      <?php if (!empty($produto['imagem'])): ?>
                        <img
                          src="<?php echo url('assets/images/produtos/' . htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8')); ?>"
                          alt="<?php echo htmlspecialchars($produto['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                          class="w-12 h-12 object-cover rounded-lg border border-gray-200" />
                      <?php else: ?>
                        <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center text-pink-300">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">
                      <?php echo htmlspecialchars($produto['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <td class="px-4 py-3">
                      <span class="px-2 py-0.5 bg-pink-50 text-pink-600 rounded-full text-xs">
                        <?php echo htmlspecialchars($produto['categoria'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                      R$ <?php echo number_format((float) ($produto['preco'] ?? 0), 2, ',', '.'); ?>
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                      <?php echo htmlspecialchars((string) ($produto['estoque'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <td class="px-4 py-3">
                      <div class="flex gap-2 justify-end">
                        <a href="<?php echo url('admin/editar'); ?>?id=<?php echo (int) $produto['id']; ?>"
                          class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg text-xs font-medium hover:bg-yellow-600 transition-colors">Editar</a>
                        <form id="remover-form-<?php echo (int) $produto['id']; ?>"
                          action="<?php echo url('admin/remover'); ?>" method="POST" class="m-0">
                          <?php echo csrf_field(); ?>
                          <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">
                          <button type="button" onclick="confirmarRemocao(<?php echo (int) $produto['id']; ?>)"
                            class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-medium hover:bg-red-700 transition-colors">Remover</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-gray-400 py-10">
                    <div class="flex flex-col items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                      </svg>
                      <span class="text-sm">Nenhum produto cadastrado</span>
                      <a href="<?php echo url('admin/adicionar'); ?>"
                        class="text-pink-600 text-sm hover:underline">Adicionar o primeiro produto</a>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Cards mobile -->
        <div class="md:hidden space-y-3">
          <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
              <div class="bg-gray-50 rounded-lg p-3 flex items-center gap-3">
                <?php if (!empty($produto['imagem'])): ?>
                  <img
                    src="<?php echo url('assets/images/produtos/' . htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8')); ?>"
                    alt="<?php echo htmlspecialchars($produto['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    class="w-14 h-14 object-cover rounded-lg border border-gray-200 flex-shrink-0" />
                <?php else: ?>
                  <div class="w-14 h-14 bg-pink-50 rounded-lg flex items-center justify-center text-pink-300 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-gray-800 text-sm truncate">
                    <?php echo htmlspecialchars($produto['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
                  <p class="text-xs text-pink-600">
                    <?php echo htmlspecialchars($produto['categoria'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
                  <p class="text-xs text-gray-500">R$
                    <?php echo number_format((float) ($produto['preco'] ?? 0), 2, ',', '.'); ?></p>
                </div>
                <div class="flex flex-col gap-1.5 flex-shrink-0">
                  <a href="<?php echo url('admin/editar'); ?>?id=<?php echo (int) $produto['id']; ?>"
                    class="px-3 py-1 bg-yellow-500 text-white rounded text-xs text-center hover:bg-yellow-600">Editar</a>
                  <form id="remover-form-mobile-<?php echo (int) $produto['id']; ?>"
                    action="<?php echo url('admin/remover'); ?>" method="POST" class="m-0">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">
                    <button type="button" onclick="confirmarRemocao(<?php echo (int) $produto['id']; ?>)"
                      class="w-full px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Remover</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center text-gray-400 py-10">
              <p class="text-sm">Nenhum produto cadastrado</p>
              <a href="<?php echo url('admin/adicionar'); ?>"
                class="text-pink-600 text-sm hover:underline mt-1 block">Adicionar o primeiro produto</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    function confirmarRemocao(id) {
      Swal.fire({
        title: 'Remover produto?',
        text: 'Essa ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, remover',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#e11d63'
      }).then(result => {
        if (result.isConfirmed) {
          const form = document.getElementById('remover-form-' + id) ??
            document.getElementById('remover-form-mobile-' + id);
          if (form) form.submit();
        }
      });
    }
  </script>
</body>

</html>