<?php
$current = $current ?? '';
?>
<aside class="hidden md:flex w-64 shrink-0 bg-white border-r border-gray-100 flex-col min-h-screen sticky top-0">

  <!-- Logo -->
  <div class="px-6 py-5 border-b border-gray-100">
    <a href="<?php echo url('admin'); ?>" class="flex items-center gap-3">
      <div class="h-9 w-9 flex items-center justify-center"
        style="background: linear-gradient(135deg, #db2777 0%, #be185d 100%);">
        <i class="fas fa-envelope-open-text text-white text-sm"></i>
      </div>
      <div>
        <p class="text-sm font-extrabold text-gray-900 leading-none">Dashboard</p>
        <p class="text-[10px] text-gray-400 font-medium mt-0.5">Painel Admin</p>
      </div>
    </a>
  </div>

  <!-- Nav -->
  <nav class="flex flex-col gap-1 px-3 py-4 flex-1">

    <p class="px-3 pt-1 pb-2 text-[10px] font-black text-gray-300 uppercase tracking-widest">Menu</p>

    <a href="<?php echo url('admin'); ?>"
      class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold transition-all
              <?php echo $current === 'dashboard' ? 'text-white shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600'; ?>"
      <?php if ($current === 'dashboard'): ?> style="background: linear-gradient(135deg, #db2777 0%, #be185d 100%);"
      <?php endif; ?>>
      <i
        class="fas fa-home w-4 text-center <?php echo $current === 'dashboard' ? 'text-white' : 'text-gray-400'; ?>"></i>
      Dashboard
    </a>

    <a href="<?php echo url('admin/produtos'); ?>"
      class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold transition-all
              <?php echo in_array($current, ['produtos', 'adicionar_produto']) ? 'text-white shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600'; ?>"
      <?php if (in_array($current, ['produtos', 'adicionar_produto'])): ?>
      style="background: linear-gradient(135deg, #db2777 0%, #be185d 100%);" <?php endif; ?>>
      <i
        class="fas fa-box-open w-4 text-center <?php echo in_array($current, ['produtos', 'adicionar_produto']) ? 'text-white' : 'text-gray-400'; ?>"></i>
      Produtos
    </a>

    <a href="<?php echo url('admin/categorias'); ?>"
      class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold transition-all
              <?php echo in_array($current, ['categorias', 'adicionar_categoria']) ? 'text-white shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600'; ?>"
      <?php if (in_array($current, ['categorias', 'adicionar_categoria'])): ?>
      style="background: linear-gradient(135deg, #db2777 0%, #be185d 100%);" <?php endif; ?>>
      <i
        class="fas fa-tags w-4 text-center <?php echo in_array($current, ['categorias', 'adicionar_categoria']) ? 'text-white' : 'text-gray-400'; ?>"></i>
      Categorias
    </a>

    <div class="my-3 border-t border-gray-100"></div>

    <p class="px-3 pb-2 text-[10px] font-black text-gray-300 uppercase tracking-widest">Geral</p>

    <a href="<?php echo url(''); ?>"
      class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-gray-500 hover:bg-pink-50 hover:text-pink-600 transition-all">
      <i class="fas fa-store w-4 text-center text-gray-400"></i>
      Ver Loja
    </a>

    <a href="<?php echo url('logout'); ?>"
      class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-red-400 hover:bg-red-50 hover:text-red-600 transition-all">
      <i class="fas fa-sign-out-alt w-4 text-center"></i>
      Sair
    </a>

  </nav>

  <!-- Rodapé -->
  <div class="px-5 py-4 border-t border-gray-100">
    <p class="text-[10px] text-gray-300 font-medium text-center">© <?php echo date('Y'); ?> Um Convite de Casamento</p>
  </div>

</aside>
