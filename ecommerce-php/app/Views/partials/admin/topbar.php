<?php
$title = $title ?? 'Dashboard';
$usuario = $usuario ?? ['nome' => 'U', 'imagem' => ''];
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