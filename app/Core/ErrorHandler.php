<?php

namespace app\Core;

use ErrorException;
use RuntimeException;
use Throwable;

class ErrorHandler
{
    private static bool $isDebug = false;

    public static function init(): void
    {
        self::$isDebug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';

        if (self::$isDebug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleException(Throwable $exception): void
    {
        //Logger l'exception
        Logger::getInstance()->error('Exception non gérée', [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);

        http_response_code(500);

        if (self::$isDebug) {
            self::displayDebugError($exception);
        } else {
            self::displayProductionError();
        }

        exit;
    }

    public static function handleError(int $level, string $message, string $file = '', int $line = 0): bool
    {
        Logger::getInstance()->warning('Erreur PHP', [
            'level' => $level,
            'message' => $message,
            'file' => $file,
            'line' => $line
        ]);

        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }

        return false;
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            Logger::getInstance()->critical('Erreur fatale', $error);

            self::handleException(
                new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line'])
            );
        }
    }

    public static function displayConfigError(string $message, ?string $details = null): never
    {
        Logger::getInstance()->critical('Erreur de configuration', [
            'message' => $message,
            'details' => $details
        ]);
        http_response_code(500);

        echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - Configuration</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 40px; margin: 0; }
        .error-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #d32f2f; margin-top: 0; font-size: 24px; }
        .message { color: #666; line-height: 1.6; margin: 20px 0; }
        .details { background: #f5f5f5; padding: 15px; border-radius: 4px; margin-top: 20px; font-family: monospace; font-size: 12px; overflow-x: auto; word-break: break-all; }
        .icon { font-size: 48px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon">⚠️</div>
        <h1>Erreur de configuration</h1>
        <p class="message">' . htmlspecialchars($message) . '</p>';

        if (self::$isDebug && $details) {
            echo '<div class="details">' . htmlspecialchars($details) . '</div>';
        }

        echo '</div>
</body>
</html>';

        exit;
    }

    public static function displayDatabaseError(RuntimeException $exception): never
    {
        Logger::getInstance()->critical('Erreur de base de données', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
        http_response_code(500);

        $message = 'Impossible de se connecter à la base de données';
        $details = self::$isDebug ? $exception->getMessage() : 'Veuillez vérifier votre configuration dans le fichier .env';

        echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - Base de données</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 40px; margin: 0; }
        .error-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #d32f2f; margin-top: 0; font-size: 24px; }
        .message { color: #666; line-height: 1.6; margin: 20px 0; }
        .details { background: #f5f5f5; padding: 15px; border-radius: 4px; margin-top: 20px; font-family: monospace; font-size: 12px; overflow-x: auto; word-break: break-all; }
        .icon { font-size: 48px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon">🔌</div>
        <h1>Erreur de base de données</h1>
        <p class="message">' . htmlspecialchars($message) . '</p>
        <p class="details">' . htmlspecialchars($details) . '</p>
    </div>
</body>
</html>';

        exit;
    }

    private static function displayDebugError(Throwable $exception): void
    {
        echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; margin: 0; }
        .error-container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #d32f2f; margin-top: 0; font-size: 24px; }
        .exception-type { color: #666; font-size: 14px; margin-bottom: 20px; }
        .message { background: #ffebee; padding: 15px; border-left: 4px solid #d32f2f; margin: 20px 0; border-radius: 4px; }
        .trace { background: #f5f5f5; padding: 15px; border-radius: 4px; margin-top: 20px; font-family: monospace; font-size: 12px; overflow-x: auto; }
        .trace-line { padding: 5px 0; border-bottom: 1px solid #ddd; }
        .trace-file { color: #1976d2; }
        .trace-line-number { color: #d32f2f; font-weight: bold; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>⚠️ Exception non gérée</h1>
        <div class="exception-type">' . htmlspecialchars(get_class($exception)) . '</div>
        <div class="message">' . htmlspecialchars($exception->getMessage()) . '</div>
        <div class="trace">
            <strong>Fichier:</strong> ' . htmlspecialchars($exception->getFile()) . '<br>
            <strong>Ligne:</strong> ' . $exception->getLine() . '<br><br>
            <strong>Stack trace:</strong><br>';

        foreach ($exception->getTrace() as $i => $trace) {
            echo '<div class="trace-line">#' . $i . ' ';
            if (isset($trace['file'])) {
                echo '<span class="trace-file">' . htmlspecialchars($trace['file']) . '</span>';
                echo ':<span class="trace-line-number">' . ($trace['line'] ?? '?') . '</span> ';
            }
            if (isset($trace['class'])) {
                echo htmlspecialchars($trace['class'] . $trace['type'] . $trace['function']);
            } elseif (isset($trace['function'])) {
                echo htmlspecialchars($trace['function']);
            }
            echo '</div>';
        }

        echo '</div>
    </div>
</body>
</html>';
    }

    private static function displayProductionError(): void
    {
        echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .error-container { max-width: 500px; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #d32f2f; margin-top: 0; font-size: 24px; }
        p { color: #666; line-height: 1.6; }
        .icon { font-size: 64px; margin-bottom: 20px; }
        a { color: #1976d2; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon">😔</div>
        <h1>Une erreur est survenue</h1>
        <p>Nous sommes désolés, une erreur inattendue s\'est produite.</p>
        <p>Veuillez réessayer ultérieurement ou <a href="/">retourner à l\'accueil</a>.</p>
    </div>
</body>
</html>';
    }
}
