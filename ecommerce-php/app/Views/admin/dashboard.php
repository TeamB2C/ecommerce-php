<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - Painel Admin</title>
  <link rel="stylesheet" href="../admin/css/painel.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      border: 1px solid rgba(229, 231, 235, 0.5);
    }

    .btn-grad {
      background: linear-gradient(135deg, #db2777 0%, #be185d 100%);
      transition: all 0.2s ease;
    }

    .btn-grad:hover {
      opacity: 0.9;
      box-shadow: 0 4px 12px rgba(219, 39, 119, 0.3);
    }

    .stat-card {
      border-left: 4px solid;
      transition: box-shadow 0.2s ease;
    }

    .stat-card:hover {
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
    }

    .product-row:hover {
      background-color: #fff9fb;
    }

    .product-row {
      transition: background 0.15s;
    }
  </style>
</head>

<body class="text-gray-800 bg-[#f8fafc]">
  <div class="flex min-h-screen">
    <?php
    $current = 'dashboard';
    include __DIR__ . '/../partials/admin/sidebar.php';
    ?>

    <div class="flex-1 p-4 md:p-10 space-y-8 min-w-0">
      <?php
      $title = '';
      include __DIR__ . '/../partials/admin/topbar.php';
      ?>

      <!-- Título da página -->
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard</h1>
        <p class="text-gray-400 text-sm mt-1">Bem-vindo ao painel de controle</p>
      </div>

      <!-- Cards de estatísticas -->
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="glass-card stat-card p-6 shadow-sm" style="border-color: #db2777;">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Produtos</span>
            <div class="h-9 w-9 flex items-center justify-center bg-pink-50">
              <i class="fas fa-box-open text-pink-500"></i>
            </div>
          </div>
          <p class="text-3xl font-extrabold text-gray-900"><?php echo (int) ($produtos_total ?? 0); ?></p>
          <a href="<?php echo url('admin/produtos'); ?>"
            class="text-xs text-pink-500 font-semibold mt-2 inline-block hover:underline">
            Ver todos →
          </a>
        </div>

        <div class="glass-card stat-card p-6 shadow-sm" style="border-color: #f59e0b;">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Pedidos</span>
            <div class="h-9 w-9 flex items-center justify-center bg-amber-50">
              <i class="fas fa-shopping-bag text-amber-500"></i>
            </div>
          </div>
          <p class="text-3xl font-extrabold text-gray-900"><?php echo (int) ($pedidos_total ?? 0); ?></p>
          <span class="text-xs text-gray-400 font-medium mt-2 inline-block">Total de pedidos</span>
        </div>

        <div class="glass-card stat-card p-6 shadow-sm" style="border-color: #10b981;">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Clientes</span>
            <div class="h-9 w-9 flex items-center justify-center bg-emerald-50">
              <i class="fas fa-users text-emerald-500"></i>
            </div>
          </div>
          <p class="text-3xl font-extrabold text-gray-900"><?php echo (int) ($clientes_total ?? 0); ?></p>
          <span class="text-xs text-gray-400 font-medium mt-2 inline-block">Clientes cadastrados</span>
        </div>

        <div class="glass-card stat-card p-6 shadow-sm" style="border-color: #6366f1;">
          <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Faturamento</span>
            <div class="h-9 w-9 flex items-center justify-center bg-indigo-50">
              <i class="fas fa-dollar-sign text-indigo-500"></i>
            </div>
          </div>
          <p class="text-2xl font-extrabold text-gray-900">
            R$ <?php echo number_format((float) ($faturamento ?? 0), 2, ',', '.'); ?>
          </p>
          <span class="text-xs text-gray-400 font-medium mt-2 inline-block">Total faturado</span>
        </div>

      </div>

      <!-- Tabela de produtos recentes -->
      <div class="glass-card shadow-sm overflow-hidden">

        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
          <div>
            <h2 class="text-base font-extrabold text-gray-900">Produtos Cadastrados</h2>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Visão rápida do catálogo</p>
          </div>
          <a href="<?php echo url('admin/produtos'); ?>"
            class="btn-grad text-white text-xs font-bold px-4 py-2 flex items-center gap-2">
            <i class="fas fa-plus text-[10px]"></i>
            Adicionar
          </a>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-gray-50/60">
                <th class="px-8 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest">Produto</th>
                <th class="px-8 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest">Categoria</th>
                <th class="px-8 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest">Preço</th>
                <th class="px-8 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Estoque</th>
                <th class="px-8 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                  <tr class="product-row">
                    <td class="px-8 py-4">
                      <div class="flex items-center gap-3">
                        <div class="h-10 w-10 overflow-hidden border border-gray-100 flex-shrink-0">
                          <?php if (!empty($produto['imagem'])): ?>
                            <img
                              src="<?php echo asset_url('images/produtos/' . htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8')); ?>"
                              class="h-full w-full object-cover" />
                          <?php else: ?>
                            <div class="h-full w-full bg-pink-50 flex items-center justify-center">
                              <i class="fas fa-image text-pink-200 text-sm"></i>
                            </div>
                          <?php endif; ?>
                        </div>
                        <span class="font-bold text-gray-900 text-sm">
                          <?php echo htmlspecialchars($produto['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                      </div>
                    </td>
                    <td class="px-8 py-4">
                      <span
                        class="px-2 py-0.5 bg-white border border-pink-100 text-pink-600 text-[10px] font-black uppercase tracking-wider">
                        <?php echo htmlspecialchars($produto['categoria'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </td>
                    <td class="px-8 py-4">
                      <span class="font-extrabold text-gray-900 text-sm">
                        <span
                          class="text-[10px] text-gray-400 mr-0.5">R$</span><?php echo number_format((float) ($produto['preco'] ?? 0), 2, ',', '.'); ?>
                      </span>
                    </td>
                    <td class="px-8 py-4 text-center">
                      <span class="inline-block min-w-[36px] px-2 py-0.5 bg-gray-100 text-xs font-bold text-gray-600">
                        <?php echo (int) ($produto['estoque'] ?? 0); ?>
                      </span>
                    </td>
                    <td class="px-8 py-4">
                      <div class="flex gap-2 justify-end">
                        <a href="<?php echo url('admin/editar'); ?>?id=<?php echo (int) $produto['id']; ?>"
                          class="h-8 w-8 flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all">
                          <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form id="dash-remove-<?php echo (int) $produto['id']; ?>"
                          action="<?php echo url('admin/remover'); ?>" method="POST" class="m-0">
                          <?php echo csrf_field(); ?>
                          <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">
                          <button type="button" onclick="confirmarRemocao(<?php echo (int) $produto['id']; ?>)"
                            class="h-8 w-8 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-trash text-xs"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="py-16 text-center text-gray-400 text-sm">
                    Nenhum produto cadastrado ainda.
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
    function confirmarRemocao(id) {
      Swal.fire({
        title: 'Excluir produto?',
        text: 'Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.getElementById('dash-remove-' + id);
          if (form) form.submit();
        }
      });
    }
  </script>
</body>

</html>