<?php
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
$app = require __DIR__ . '/../app/bootstrap.php';
$app['router']->dispatch('cart.remove');
