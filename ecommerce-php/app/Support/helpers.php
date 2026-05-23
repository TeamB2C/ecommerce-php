<?php

use App\Core\HttpException;

if (!function_exists('asset_version')) {
    function asset_version(string $filepath): int
    {
        return file_exists($filepath) ? filemtime($filepath) : time();
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        return isset($_POST[$key])
            ? htmlspecialchars((string) $_POST[$key], ENT_QUOTES, 'UTF-8')
            : $default;
    }
}

if (!function_exists('env')) {
    function env(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);

        return $value === false
            ? $default
            : $value;
    }
}

if (!function_exists('app_base_url')) {
    function app_base_url(): string
    {
        $appUrl = env('APP_URL');

        if ($appUrl !== null && $appUrl !== '') {
            return rtrim($appUrl, '/');
        }

        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

        $base = rtrim(dirname($scriptName), '/');

        if ($base === '' || $base === '.') {
            return '';
        }

        return $base;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $base = app_base_url();

        $normalized = ltrim($path, '/');

        if ($normalized === '') {
            return $base !== ''
                ? $base
                : '/';
        }

        if ($base === '' || $base === '/') {
            return '/' . $normalized;
        }

        return $base . '/' . $normalized;
    }
}

if (!function_exists('site_root_url')) {
    function site_root_url(): string
    {
        return preg_replace('#/public$#', '', app_base_url());
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        return site_root_url() . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('upload_url')) {
    function upload_url(string $path): string
    {
        return site_root_url() . '/uploads/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (
            !isset($_SESSION['_csrf_token']) ||
            !is_string($_SESSION['_csrf_token'])
        ) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' .
            htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') .
            '">';
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token(?string $token): bool
    {
        if (
            !isset($_SESSION['_csrf_token']) ||
            !is_string($_SESSION['_csrf_token'])
        ) {
            return false;
        }

        return is_string($token) &&
            hash_equals($_SESSION['_csrf_token'], $token);
    }
}

if (!function_exists('require_csrf')) {
    function require_csrf(?string $token): void
    {
        if (!verify_csrf_token($token)) {
            throw new HttpException('Token CSRF inválido.', 403);
        }
    }
}