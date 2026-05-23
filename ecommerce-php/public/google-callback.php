<?php
$app = require __DIR__ . '/../app/bootstrap.php';
$app['router']->dispatch('auth.google_callback');
