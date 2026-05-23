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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.95);
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

    .product-row {
      transition: all 0.2s ease;
    }

    .product-row:hover {
      background-color: #fff9fb;
    }
  </style>
</head>

<body class="text-gray-800 bg-[#f8fafc]">
  <div class="flex min-h-screen">
    <?php
    $current = 'produtos';
    include __DIR__ . '/../partials/admin/sidebar.php';
    ?>

    <div class="flex-1 p-4 md:p-10 space-y-8 min-w-0">
      <?php
      $title = '';
      include __DIR__ . '/../partials/admin/topbar.php';
      ?>

      <?php if (!empty($sucesso) || !empty($erro)): ?>
        <div class="animate-in fade-in slide-in-from-top-4 duration-300">
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
        <div class="px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100">
          <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Produtos</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">Gerencie seu catálogo de convites</p>
          </div>
          <a href="<?php echo url('admin/adicionar'); ?>"
            class="btn-grad inline-flex items-center gap-2 px-6 py-3 text-white text-sm font-bold rounded-2xl">
            <i class="fas fa-plus"></i>
            Adicionar Produto
          </a>
        </div>

        <div class="hidden md:block overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-gray-50/50">
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Produto</th>
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Categoria</th>
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Preço</th>
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Estoque</th>
                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                  <tr class="product-row">
                    <td class="px-8 py-5">
                      <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex-shrink-0">
                          <?php if (!empty($produto['imagem'])): ?>
                            <img
                              src="<?php echo asset_url('images/produtos/' . htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8')); ?>"
                              class="h-full w-full object-cover" />
                          <?php else: ?>
                            <div class="h-full w-full bg-pink-50 flex items-center justify-center text-pink-200">
                              <i class="fas fa-image text-xl"></i>
                            </div>
                          <?php endif; ?>
                        </div>
                        <span
                          class="font-bold text-gray-900"><?php echo htmlspecialchars($produto['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></span>
                      </div>
                    </td>
                    <td class="px-8 py-5">
                      <span
                        class="px-3 py-1 bg-white border border-pink-100 text-pink-600 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm">
                        <?php echo htmlspecialchars($produto['categoria'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </td>
                    <td class="px-8 py-5">
                      <span class="text-gray-900 font-extrabold">
                        <span
                          class="text-[10px] text-gray-400 mr-1">R$</span><?php echo number_format((float) ($produto['preco'] ?? 0), 2, ',', '.'); ?>
                      </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                      <span
                        class="inline-block min-w-[40px] px-2 py-1 bg-gray-100 rounded-lg text-xs font-bold text-gray-600">
                        <?php echo htmlspecialchars((string) ($produto['estoque'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </td>
                    <td class="px-8 py-5">
                      <div class="flex gap-2 justify-end">
                        <a href="<?php echo url('admin/editar'); ?>?id=<?php echo (int) $produto['id']; ?>"
                          class="h-9 w-9 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                          <i class="fas fa-edit text-sm"></i>
                        </a>
                        <form id="remover-form-<?php echo (int) $produto['id']; ?>"
                          action="<?php echo url('admin/remover'); ?>" method="POST" class="m-0">
                          <?php echo csrf_field(); ?>
                          <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">
                          <button type="button" onclick="confirmarRemocao(<?php echo (int) $produto['id']; ?>)"
                            class="h-9 w-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                            <i class="fas fa-trash text-sm"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="py-20 text-center text-gray-400">Nenhum produto encontrado.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="md:hidden px-4 py-4 space-y-3">
          <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
              <article class="border border-gray-100 rounded-2xl p-4 bg-white shadow-sm">
                <div class="flex items-start gap-3">
                  <div class="h-14 w-14 rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex-shrink-0">
                    <?php if (!empty($produto['imagem'])): ?>
                      <img
                        src="<?php echo asset_url('images/produtos/' . htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8')); ?>"
                        class="h-full w-full object-cover" />
                    <?php else: ?>
                      <div class="h-full w-full bg-pink-50 flex items-center justify-center text-pink-200">
                        <i class="fas fa-image text-xl"></i>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-bold text-gray-900 break-words">
                      <?php echo htmlspecialchars($produto['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                    </h3>
                    <div class="mt-2 flex items-center gap-2 flex-wrap">
                      <span class="px-2 py-1 bg-white border border-pink-100 text-pink-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                        <?php echo htmlspecialchars($produto['categoria'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                      <span class="text-xs font-bold text-gray-500">
                        Estoque: <?php echo htmlspecialchars((string) ($produto['estoque'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </div>
                    <p class="mt-2 text-sm font-extrabold text-gray-900">
                      R$ <?php echo number_format((float) ($produto['preco'] ?? 0), 2, ',', '.'); ?>
                    </p>
                  </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-2">
                  <a href="<?php echo url('admin/editar'); ?>?id=<?php echo (int) $produto['id']; ?>"
                    class="h-9 w-9 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                    title="Editar">
                    <i class="fas fa-edit text-sm"></i>
                  </a>
                  <form id="remover-form-mobile-<?php echo (int) $produto['id']; ?>"
                    action="<?php echo url('admin/remover'); ?>" method="POST" class="m-0">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">
                    <button type="button" onclick="confirmarRemocaoMobile(<?php echo (int) $produto['id']; ?>)"
                      class="h-9 w-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm"
                      title="Remover">
                      <i class="fas fa-trash text-sm"></i>
                    </button>
                  </form>
                </div>
              </article>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="py-8 text-center text-gray-400">Nenhum produto encontrado.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    function confirmarRemocao(id) {
      Swal.fire({
        title: 'Excluir item?',
        text: "Esta ação removerá o produto permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        background: '#fff',
        borderRadius: '24px'
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.getElementById('remover-form-' + id);
          if (form) form.submit();
        }
      })
    }

    function confirmarRemocaoMobile(id) {
      Swal.fire({
        title: 'Excluir item?',
        text: "Esta ação removerá o produto permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        background: '#fff',
        borderRadius: '24px'
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.getElementById('remover-form-mobile-' + id);
          if (form) form.submit();
        }
      })
    }
  </script>
</body>

</html>
