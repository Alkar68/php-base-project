<?php

namespace app\Helpers;

use app\Core\Logger;
use DateMalformedStringException;
use DateTime;
use Random\RandomException;
use RuntimeException;

class SecurityHelper
{
    /**
     * Échappe les données pour éviter les attaques XSS
     */
    public static function escape(mixed $data): string
    {
        if (is_array($data)) {
            return implode(', ', array_map([self::class, 'escape'], $data));
        }

        return htmlspecialchars((string)$data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Hash un mot de passe avec bcrypt
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => (int)$_ENV['BCRYPT_ROUNDS']
        ]);
    }

    /**
     * Vérifie un mot de passe contre son hash
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Génère un token sécurisé
     */
    public static function generateToken(int $length = 32): string
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (RandomException $e) {
            Logger::getInstance()->critical('Token generation failed', [
                'exception' => $e->getMessage(),
                'length' => $length
            ]);
            throw new RuntimeException('Failed to generate secure token: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Génère un token avec expiration pour réinitialisation mot de passe
     */
    public static function generatePasswordResetToken(): array
    {
        $token = self::generateToken(64);
        $expiresAt = new DateTime();
        try {
            $expiresAt->modify('+' . $_ENV['TOKEN_NEW_PASSWORD_TIMEOUT'] . ' seconds');
        } catch (DateMalformedStringException $e) {
            Logger::getInstance()->error('Invalid token timeout configuration', [
                'exception' => $e->getMessage(),
                'config_value' => $_ENV['TOKEN_NEW_PASSWORD_TIMEOUT']
            ]);
            throw new RuntimeException('Invalid token timeout configuration: ' . $e->getMessage(), 0, $e);
        }

        return [
            'token' => $token,
            'expires_at' => $expiresAt
        ];
    }

    /**
     * Vérifie si un token de réinitialisation est valide
     */
    public static function isPasswordResetTokenValid(DateTime $expiresAt): bool
    {
        return new DateTime() < $expiresAt;
    }

    /**
     * Nettoie une chaîne pour éviter les injections
     */
    public static function sanitize(string $input): string
    {
        return filter_var(trim($input), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    /**
     * Valide une adresse email
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Génère un hash sécurisé pour comparaison
     */
    public static function hash(string $data): string
    {
        return hash('sha256', $data);
    }
}
