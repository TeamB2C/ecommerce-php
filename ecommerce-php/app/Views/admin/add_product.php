<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Adicionar Produto - Painel Admin</title>
    <link rel="stylesheet" href="../admin/css/painel.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.97);
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

        .form-input {
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: #db2777;
            box-shadow: 0 0 0 3px rgba(219, 39, 119, 0.1);
            outline: none;
        }

        .upload-area {
            border: 2px dashed #fce7f3;
            transition: all 0.2s ease;
        }

        .upload-area:hover {
            border-color: #db2777;
            background: #fdf2f8;
        }

        #preview-container {
            display: none;
        }

        #preview-container.show {
            display: block;
        }
    </style>
</head>

<body class="text-gray-800 bg-[#f8fafc]">
    <div class="flex min-h-screen">
        <?php
        $current = 'produtos';
        include __DIR__ . '/../partials/admin/sidebar.php';
        ?>

        <div class="flex-1 p-4 md:p-10 space-y-6 min-w-0">
            <?php
            $title = '';
            include __DIR__ . '/../partials/admin/topbar.php';
            ?>

            <div class="flex items-center gap-2 text-sm text-gray-400">
                <a href="<?php echo url('admin/produtos'); ?>"
                    class="hover:text-pink-600 transition-colors font-medium">Produtos</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-700 font-semibold">Adicionar Produto</span>
            </div>

            <?php if (!empty($erro)): ?>
                <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <p class="text-sm font-medium"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>

            <div class="glass-card rounded-3xl shadow-sm overflow-hidden max-w-3xl">
                <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                    <a href="<?php echo url('admin/produtos'); ?>"
                        class="h-9 w-9 flex items-center justify-center bg-gray-100 text-gray-500 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition-all">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Novo Produto</h2>
                        <p class="text-gray-400 text-xs font-medium mt-0.5">Preencha os dados para adicionar ao catálogo</p>
                    </div>
                </div>

                <form action="<?php echo htmlspecialchars(url('admin/adicionar'), ENT_QUOTES, 'UTF-8'); ?>" method="POST"
                    enctype="multipart/form-data" class="px-8 py-7 space-y-6">
                    <?php echo csrf_field(); ?>

                    <div class="space-y-1.5">
                        <label for="nome" class="block text-sm font-semibold text-gray-700">
                            Nome do Produto <span class="text-pink-500">*</span>
                        </label>
                        <input type="text" name="nome" id="nome" required placeholder="Ex: Convite Clássico Perolado"
                            value="<?php echo old('nome'); ?>"
                            class="form-input w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="descricao" class="block text-sm font-semibold text-gray-700">
                            Descrição <span class="text-pink-500">*</span>
                        </label>
                        <textarea name="descricao" id="descricao" required rows="3"
                            placeholder="Descreva o produto, materiais, tamanho..."
                            class="form-input w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white resize-none"><?php echo old('descricao'); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="preco" class="block text-sm font-semibold text-gray-700">
                                Preço (R$) <span class="text-pink-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">R$</span>
                                <input type="text" name="preco" id="preco" required placeholder="0,00"
                                    value="<?php echo old('preco'); ?>"
                                    class="form-input w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white" />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="categoria" class="block text-sm font-semibold text-gray-700">
                                Categoria <span class="text-pink-500">*</span>
                            </label>
                            <?php if (!empty($categorias)): ?>
                                <select name="categoria" id="categoria" required
                                    class="form-input w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white">
                                    <option value="">Selecione...</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat['nome'], ENT_QUOTES, 'UTF-8'); ?>"
                                            <?php echo old('categoria') === $cat['nome'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['nome'], ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="text" name="categoria" id="categoria" required placeholder="Ex: Convites"
                                    value="<?php echo old('categoria'); ?>"
                                    class="form-input w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white" />
                                <p class="text-xs text-gray-400 mt-1">
                                    Nenhuma categoria cadastrada.
                                    <a href="<?php echo url('admin/categorias/adicionar'); ?>"
                                        class="text-pink-600 hover:underline">Cadastrar agora</a>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="estoque" class="block text-sm font-semibold text-gray-700">Estoque</label>
                        <input type="number" name="estoque" id="estoque" min="0" placeholder="0"
                            value="<?php echo old('estoque', '0'); ?>"
                            class="form-input px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white w-32" />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            Foto do Produto <span class="text-pink-500">*</span>
                            <span class="text-gray-400 font-normal ml-1">(JPG ou PNG, máx. 5MB)</span>
                        </label>
                        <label for="foto" class="upload-area rounded-2xl p-6 flex flex-col items-center gap-3 cursor-pointer">
                            <div id="upload-icon" class="h-12 w-12 bg-pink-50 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-cloud-upload-alt text-pink-400 text-xl"></i>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-gray-700">Clique para enviar imagem</p>
                                <p class="text-xs text-gray-400 mt-0.5">Arraste e solte ou clique para selecionar</p>
                            </div>
                            <input type="file" id="foto" name="foto" accept="image/jpeg, image/png" required class="hidden"
                                onchange="previewImage(event)" />
                        </label>
                        <div id="preview-container" class="mt-3">
                            <p class="text-xs font-semibold text-gray-500 mb-2">Pré-visualização:</p>
                            <div class="relative inline-block">
                                <img id="preview-img" src="" alt="Preview"
                                    class="h-28 w-28 object-cover rounded-2xl border border-gray-200 shadow-sm" />
                                <button type="button" onclick="clearPreview()"
                                    class="absolute -top-2 -right-2 h-6 w-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600 transition-colors flex items-center justify-center">
                                    <i class="fas fa-times text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-gray-50">
                        <button type="submit"
                            class="btn-grad flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-2xl text-white text-sm font-bold">
                            <i class="fas fa-plus-circle"></i>
                            Salvar Produto
                        </button>
                        <a href="<?php echo url('admin/produtos'); ?>"
                            class="flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-2xl border border-gray-200 text-gray-500 text-sm font-semibold hover:bg-gray-50 transition-colors text-center">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-container').classList.add('show');
                document.getElementById('upload-icon').innerHTML = '<i class="fas fa-check text-green-500 text-xl"></i>';
            };
            reader.readAsDataURL(file);
        }

        function clearPreview() {
            document.getElementById('foto').value = '';
            document.getElementById('preview-container').classList.remove('show');
            document.getElementById('upload-icon').innerHTML = '<i class="fas fa-cloud-upload-alt text-pink-400 text-xl"></i>';
        }
    </script>
</body>

</html>