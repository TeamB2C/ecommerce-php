<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\ProfileController;
use App\Controllers\StoreController;

$storeController = new StoreController($pdo);
$authController = new AuthController($pdo);
$cartController = new CartController($pdo);
$profileController = new ProfileController($pdo);
$adminController = new AdminController($pdo);

$router->add('store.home', fn() => $storeController->home());
$router->add('store.product', fn() => $storeController->productDetail());

$router->add('auth.login_register', fn() => $authController->loginRegister());
$router->add('auth.logout', fn() => $authController->logout());

$router->add('auth.forgot_password', fn() => $authController->forgotPassword());
$router->add('auth.send_reset', fn() => $authController->sendReset());

$router->add('auth.reset_password', fn() => $authController->resetPassword());
$router->add('auth.update_password', fn() => $authController->updatePassword());

$router->add('cart.add', fn() => $cartController->add());
$router->add('cart.update', fn() => $cartController->update());
$router->add('cart.remove', fn() => $cartController->remove());
$router->add('cart.index', fn() => $cartController->index());
$router->add('cart.finalize', fn() => $cartController->finalize());

$router->add('profile.edit', fn() => $profileController->edit());

$router->add('admin.dashboard', fn() => $adminController->dashboard());
$router->add('admin.add_product', fn() => $adminController->addProduct());
$router->add('admin.edit_product', fn() => $adminController->editProduct());
$router->add('admin.remove_product', fn() => $adminController->removeProduct());
$router->add('admin.login', fn() => $adminController->login());
$router->add('admin.products', fn() => $adminController->products());
$router->add('admin.categories', fn() => $adminController->categories());
$router->add('admin.add_category', fn() => $adminController->addCategory());
$router->add('admin.edit_category', fn() => $adminController->editCategory());
$router->add('admin.remove_category', fn() => $adminController->removeCategory());
