<?php
$mode       = $mode       ?? 'create';
$action     = $action     ?? url('admin/categorias/adicionar');
$submitLabel = $submitLabel ?? 'Salvar Categoria';
$categoria  = $categoria  ?? [];
?>
<form action="<?php echo htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>" method="POST" class="space-y-5">
  <?php echo csrf_field(); ?>

  <div class="space-y-1.5">
    <label for="nome" class="block text-sm font-semibold text-gray-700">
      Nome da Categoria <span class="text-pink-500">*</span>
    </label>
    <input type="text" name="nome" id="nome" required placeholder="Ex: Convites, Lembranças, Decoração..."
      value="<?php echo $mode === 'edit' ? htmlspecialchars((string) ($categoria['nome'] ?? ''), ENT_QUOTES, 'UTF-8') : old('nome'); ?>"
      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white
                  focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition" />
  </div>

  <div class="space-y-1.5">
    <label for="descricao" class="block text-sm font-semibold text-gray-700">
      Descrição <span class="text-gray-400 font-normal ml-1">(opcional)</span>
    </label>
    <textarea name="descricao" id="descricao" rows="3" placeholder="Descreva brevemente esta categoria..."
      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white resize-none
                     focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition"><?php echo $mode === 'edit' ? htmlspecialchars((string) ($categoria['descricao'] ?? ''), ENT_QUOTES, 'UTF-8') : old('descricao'); ?></textarea>
  </div>

  <div class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-gray-50">
    <button type="submit"
      class="flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-2xl text-white text-sm font-bold transition-all"
      style="background: linear-gradient(135deg, #db2777 0%, #be185d 100%);">
      <i class="fas fa-<?php echo $mode === 'edit' ? 'save' : 'plus-circle'; ?>"></i>
      <?php echo htmlspecialchars($submitLabel, ENT_QUOTES, 'UTF-8'); ?>
    </button>
    <a href="<?php echo url('admin/categorias'); ?>"
      class="flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-2xl border border-gray-200 text-gray-500 text-sm font-semibold hover:bg-gray-50 transition-colors text-center">
      <i class="fas fa-times"></i>
      Cancelar
    </a>
  </div>
</form>