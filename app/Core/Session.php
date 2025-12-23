<?php

namespace app\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => (int)$_ENV['SESSION_LIFETIME'] * 60,
                'cookie_httponly' => true,
                'cookie_secure' => $_ENV['APP_ENV'] === 'production',
                'cookie_samesite' => 'Strict'
            ]);
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function flash(string $key): ?string
    {
        $value = self::get($key);
        self::remove($key);
        return $value;
    }

    public static function setFlash(string $key, string $message): void
    {
        self::set($key, $message);
    }
}
