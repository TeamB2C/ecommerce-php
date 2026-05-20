<?php
$mode = $mode ?? 'create';
$action = $action ?? url('admin/adicionar');
$submitLabel = $submitLabel ?? 'Salvar Produto';
$produto = $produto ?? [];
?>
<form action="<?php echo htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
    <?php echo csrf_field(); ?>
    <div>git branch -a
        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome do Produto</label>
        <input
            type="text"
            name="nome"
            id="nome"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
            value="<?php echo $mode === 'edit' ? htmlspecialchars((string) ($produto['nome'] ?? ''), ENT_QUOTES, 'UTF-8') : old('nome'); ?>"
        />
    </div>
    <div>
        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
        <textarea name="descricao" id="descricao" required rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"><?php echo $mode === 'edit' ? htmlspecialchars((string) ($produto['descricao'] ?? ''), ENT_QUOTES, 'UTF-8') : old('descricao'); ?></textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="preco" class="block text-sm font-medium text-gray-700 mb-1">Preço (R$)</label>
            <input
                type="text"
                name="preco"
                id="preco"
                required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                value="<?php echo $mode === 'edit' ? htmlspecialchars(str_replace('.', ',', (string) ($produto['preco'] ?? '')), ENT_QUOTES, 'UTF-8') : old('preco'); ?>"
            />
        </div>
        <div>
            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
            <input
                type="text"
                name="categoria"
                id="categoria"
                required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                value="<?php echo $mode === 'edit' ? htmlspecialchars((string) ($produto['categoria'] ?? ''), ENT_QUOTES, 'UTF-8') : old('categoria'); ?>"
            />
        </div>
    </div>

    <?php if ($mode === 'edit'): ?>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Imagem Atual</label>
            <img src="../assets/images/produtos/<?php echo htmlspecialchars((string) ($produto['imagem'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" alt="Imagem do Produto" class="max-w-[100px] h-auto border border-gray-300 rounded-md shadow-sm mb-3" />
        </div>
    <?php endif; ?>

    <div>
        <label for="foto" class="block text-sm font-medium text-gray-700 mb-1"><?php echo $mode === 'edit' ? 'Nova Imagem (opcional)' : 'Foto do Produto (JPG/PNG)'; ?></label>
        <input type="file" id="foto" name="foto" accept="image/jpeg, image/png" <?php echo $mode === 'create' ? 'required' : ''; ?> class="mt-1 block w-full text-sm text-gray-500" />
    </div>

    <button type="submit" class="w-full py-2 px-4 rounded-md text-white bg-pink-600 hover:bg-pink-700"><?php echo htmlspecialchars($submitLabel, ENT_QUOTES, 'UTF-8'); ?></button>
</form>

