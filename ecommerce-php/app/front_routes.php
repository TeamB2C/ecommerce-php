<?php

return [
    '/' => ['GET' => 'store.home'],
    '/produto' => ['GET' => 'store.product'],

    '/login' => ['GET' => 'auth.login_register', 'POST' => 'auth.login_register'],
    '/logout' => ['GET' => 'auth.logout'],
    '/forgot-password' => ['GET' => 'auth.forgot_password', 'POST' => 'auth.send_reset'],

    '/reset-password' => ['GET' => 'auth.reset_password', 'POST' => 'auth.update_password'],

    '/carrinho' => ['GET' => 'cart.index'],
    '/carrinho/adicionar' => ['GET' => 'cart.add', 'POST' => 'cart.add'],
    '/carrinho/atualizar' => ['POST' => 'cart.update'],
    '/carrinho/remover' => ['POST' => 'cart.remove'],
    '/carrinho/finalizar' => ['POST' => 'cart.finalize'],

    '/perfil' => ['GET' => 'profile.edit', 'POST' => 'profile.edit'],

    '/admin' => ['GET' => 'admin.dashboard'],
    '/admin/produtos' => ['GET' => 'admin.products'],
    '/admin/adicionar' => ['GET' => 'admin.add_product', 'POST' => 'admin.add_product'],
    '/admin/editar' => ['GET' => 'admin.edit_product', 'POST' => 'admin.edit_product'],
    '/admin/remover' => ['POST' => 'admin.remove_product'],
    '/admin/categorias' => ['GET' => 'admin.categories'],
    '/admin/categorias/adicionar' => ['GET' => 'admin.add_category', 'POST' => 'admin.add_category'],
    '/admin/categorias/editar' => ['GET' => 'admin.edit_category', 'POST' => 'admin.edit_category'],
    '/admin/categorias/remover' => ['POST' => 'admin.remove_category'],
];
