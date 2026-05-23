<?php
$title = $title ?? 'Dashboard';
$usuario = $usuario ?? ['nome' => 'U', 'imagem' => ''];
$current = $current ?? '';
?>

<div class="flex justify-between items-center mb-4">
  <h2 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h2>
  <div class="flex items-center gap-2">
    <?php if (!empty($usuario['imagem'])): ?>
      <img src="../uploads/<?php echo htmlspecialchars($usuario['imagem'], ENT_QUOTES, 'UTF-8'); ?>"
        class="w-9 h-9 rounded-full border-2 border-pink-200" alt="Perfil" />
    <?php else: ?>
      <div class="avatar-fallback"><?php echo strtoupper(substr((string) ($usuario['nome'] ?? 'U'), 0, 1)); ?></div>
    <?php endif; ?>
  </div>
</div>

<nav class="md:hidden mb-5 space-y-3">
  <div>
    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-2">Menu</p>
    <div class="overflow-x-auto">
      <div class="flex items-center gap-2 min-w-max">
        <a href="<?php echo url('admin'); ?>"
          class="px-3 py-2 text-xs font-semibold rounded-lg whitespace-nowrap <?php echo $current === 'dashboard' ? 'bg-pink-600 text-white' : 'bg-white border border-gray-200 text-gray-700'; ?>">
          Dashboard
        </a>
        <a href="<?php echo url('admin/produtos'); ?>"
          class="px-3 py-2 text-xs font-semibold rounded-lg whitespace-nowrap <?php echo in_array($current, ['produtos', 'adicionar_produto']) ? 'bg-pink-600 text-white' : 'bg-white border border-gray-200 text-gray-700'; ?>">
          Produtos
        </a>
        <a href="<?php echo url('admin/categorias'); ?>"
          class="px-3 py-2 text-xs font-semibold rounded-lg whitespace-nowrap <?php echo in_array($current, ['categorias', 'adicionar_categoria']) ? 'bg-pink-600 text-white' : 'bg-white border border-gray-200 text-gray-700'; ?>">
          Categorias
        </a>
      </div>
    </div>
  </div>

  <div>
    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-2">Geral</p>
    <div class="overflow-x-auto">
      <div class="flex items-center gap-2 min-w-max">
        <a href="<?php echo url(''); ?>"
          class="px-3 py-2 text-xs font-semibold rounded-lg whitespace-nowrap bg-white border border-gray-200 text-gray-700">
          Ver Loja
        </a>
        <a href="<?php echo url('logout'); ?>"
          class="px-3 py-2 text-xs font-semibold rounded-lg whitespace-nowrap bg-white border border-red-200 text-red-600">
          Sair
        </a>
      </div>
    </div>
  </div>
</nav>
