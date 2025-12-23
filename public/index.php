<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use app\Core\Router;
use app\Core\Database;
use app\Core\Session;
use app\Core\ErrorHandler;
use app\Controllers\HomeController;
use app\Controllers\AuthController;
use app\Controllers\DashboardController;
use app\Controllers\UserController;

// Charger les variables d'environnement
try {
    if (!file_exists(__DIR__ . '/../.env') && !file_exists(__DIR__ . '/../.env.local')) {
        http_response_code(500);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Erreur</title></head><body><h1>Fichier .env manquant</h1><p>Copiez .env.example vers .env</p></body></html>';
        exit;
    }

    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');

    if (file_exists(__DIR__ . '/../.env')) {
        $dotenv->load();
    }
    if (file_exists(__DIR__ . '/../.env.local')) {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env.local');
        $dotenv->load();
    }
} catch (Exception $e) {
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="' . $_ENV['APP_LOCALE'] . '"><head><meta charset="UTF-8"><title>Erreur</title></head><body><h1>Erreur .env</h1><p>' . htmlspecialchars($e->getMessage()) . '</p></body></html>';
    exit;
}

// Vérifier les variables requises
$requiredEnvVars = ['DB_CONNECTION', 'DB_HOST', 'DB_NAME', 'DB_USER', 'APP_DEBUG'];
$missingVars = array_filter($requiredEnvVars, fn($var) => !isset($_ENV[$var]));

if (!empty($missingVars)) {
    ErrorHandler::displayConfigError(
        'Configuration incomplète',
        'Variables manquantes : ' . implode(', ', $missingVars)
    );
}

// Initialiser le gestionnaire d'erreurs
ErrorHandler::init();

// Démarrer la session
Session::start();

// Initialiser la base de données
try {
    Database::getInstance();
} catch (RuntimeException $e) {
    ErrorHandler::displayDatabaseError($e);
}

// Initialiser le routeur
$router = new Router();
$router->register(HomeController::class);
$router->register(AuthController::class);
$router->register(DashboardController::class);
$router->register(UserController::class);

// Dispatcher la route
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
