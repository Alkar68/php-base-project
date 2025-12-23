<?php

use app\Helpers\SecurityHelper;

if (!function_exists('e')) {
    function e(mixed $data): string
    {
        return SecurityHelper::escape($data);
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars): never
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
        die();
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}

/**
 * Récupère un message d'erreur traduit
 */
function error_message(string $key, array $params = []): string
{
    static $messages = null;

    if ($messages === null) {
        $messages = require __DIR__ . '/../../config/error_messages.php';
    }

    $message = $messages[$key] ?? $key;

    foreach ($params as $param => $value) {
        $message = str_replace('{' . $param . '}', $value, $message);
    }

    return $message;
}
