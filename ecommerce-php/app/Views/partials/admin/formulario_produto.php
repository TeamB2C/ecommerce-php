<?php
$mode = $mode ?? 'create';
$action = $action ?? url('admin/adicionar');
$submitLabel = $submitLabel ?? 'Salvar Produto';
$produto = $produto ?? [];
$categorias = $categorias ?? [];
?>
<form action="<?php echo htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>" method="POST" enctype="multipart/form-data"
    class="space-y-4">
    <?php echo csrf_field(); ?>

    <div>
        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome do Produto</label>
        <input type="text" name="nome" id="nome" required placeholder="Ex: Convite Clássico Perolado"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400"
            value="<?php echo $mode === 'edit' ? htmlspecialchars((string) ($produto['nome'] ?? ''), ENT_QUOTES, 'UTF-8') : old('nome'); ?>" />
    </div>

    <div>
        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
        <textarea name="descricao" id="descricao" required rows="3" placeholder="Descreva o produto..."
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400"><?php echo $mode === 'edit' ? htmlspecialchars((string) ($produto['descricao'] ?? ''), ENT_QUOTES, 'UTF-8') : old('descricao'); ?></textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="preco" class="block text-sm font-medium text-gray-700 mb-1">Preço (R$)</label>
            <input type="text" name="preco" id="preco" required placeholder="0,00"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400"
                value="<?php echo $mode === 'edit' ? htmlspecialchars(str_replace('.', ',', (string) ($produto['preco'] ?? '')), ENT_QUOTES, 'UTF-8') : old('preco'); ?>" />
        </div>

        <div>
            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
            <?php if (!empty($categorias)): ?>
                <select name="categoria" id="categoria" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400 bg-white">
                    <option value="">Selecione...</option>
                    <?php foreach ($categorias as $cat): ?>
                        <?php $sel = ($mode === 'edit' ? ($produto['categoria'] ?? '') : old('categoria')) === $cat['nome'] ? 'selected' : ''; ?>
                        <option value="<?php echo htmlspecialchars($cat['nome'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $sel; ?>>
                            <?php echo htmlspecialchars($cat['nome'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input type="text" name="categoria" id="categoria" required placeholder="Ex: Convites"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400"
                    value="<?php echo $mode === 'edit' ? htmlspecialchars((string) ($produto['categoria'] ?? ''), ENT_QUOTES, 'UTF-8') : old('categoria'); ?>" />
                <p class="text-xs text-gray-400 mt-1">
                    Nenhuma categoria cadastrada.
                    <a href="<?php echo url('admin/categorias/adicionar'); ?>" class="text-pink-600 hover:underline">Cadastrar
                        agora</a>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <label for="estoque" class="block text-sm font-medium text-gray-700 mb-1">Estoque</label>
        <input type="number" name="estoque" id="estoque" min="0" placeholder="0"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400"
            value="<?php echo $mode === 'edit' ? htmlspecialchars((string) ($produto['estoque'] ?? '0'), ENT_QUOTES, 'UTF-8') : old('estoque', '0'); ?>" />
    </div>

    <?php if ($mode === 'edit' && !empty($produto['imagem'])): ?>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Imagem Atual</label>
            <img
                src="<?php echo asset_url('images/produtos/' . htmlspecialchars((string) ($produto['imagem'] ?? ''), ENT_QUOTES, 'UTF-8')); ?>"
                alt="Imagem do Produto" class="max-w-[100px] h-auto border border-gray-300 rounded-lg shadow-sm mb-3" />
        </div>
    <?php endif; ?>

    <div>
        <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">
            <?php echo $mode === 'edit' ? 'Nova Imagem <span class="text-gray-400 font-normal">(opcional)</span>' : 'Foto do Produto <span class="text-gray-400 font-normal">(JPG/PNG)</span>'; ?>
        </label>
        <input type="file" id="foto" name="foto" accept="image/jpeg, image/png"
            <?php echo $mode === 'create' ? 'required' : ''; ?>
            class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-pink-50 file:text-pink-600 hover:file:bg-pink-100" />
    </div>

    <div class="flex flex-col sm:flex-row gap-3 pt-2">
        <button type="submit"
            class="flex-1 py-2 px-4 rounded-lg text-white bg-pink-600 hover:bg-pink-700 text-sm font-medium transition-colors">
            <?php echo htmlspecialchars($submitLabel, ENT_QUOTES, 'UTF-8'); ?>
        </button>
        <a href="<?php echo url('admin/produtos'); ?>"
            class="flex-1 py-2 px-4 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium text-center transition-colors">
            Cancelar
        </a>
    </div>
</form>