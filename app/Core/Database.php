<?php

namespace app\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                Logger::getInstance()->info('Tentative de connexion à la base de données');

                $dsn = sprintf(
                    "%s:host=%s;port=%s;dbname=%s;charset=%s",
                    $_ENV['DB_CONNECTION'],
                    $_ENV['DB_HOST'],
                    $_ENV['DB_PORT'] ?? 3306,
                    $_ENV['DB_NAME'],
                    $_ENV['DB_CHARSET'] ?? 'utf8mb4'
                );

                self::$instance = new PDO(
                    $dsn,
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASSWORD'] ?? '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );

                Logger::getInstance()->info('Connexion à la base de données réussie');
            } catch (PDOException $e) {
                Logger::getInstance()->critical('Échec de connexion à la base de données', [
                    'error' => $e->getMessage(),
                    'host' => $_ENV['DB_HOST'],
                    'database' => $_ENV['DB_NAME']
                ]);

                // En production, on cache les détails sensibles
                $message = ($_ENV['APP_DEBUG'] === 'true')
                    ? "Erreur de connexion à la base de données : " . $e->getMessage()
                    : "Impossible de se connecter à la base de données. Vérifiez votre configuration.";

                throw new RuntimeException($message);
            }
        }

        return self::$instance;
    }
}
