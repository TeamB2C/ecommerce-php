<?php

echo "<pre>";

echo "PHP VERSION:\n";
echo phpversion();

echo "\n\nPHP INI:\n";
echo php_ini_loaded_file();

echo "\n\ncurl.cainfo:\n";
echo ini_get('curl.cainfo');

echo "\n\nopenssl.cafile:\n";
echo ini_get('openssl.cafile');

echo "</pre>";