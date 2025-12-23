<?php

namespace app\Core;

use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Logger
{
    private static ?MonologLogger $instance = null;

    public static function getInstance(): MonologLogger
    {
        if (self::$instance === null) {
            $logger = new MonologLogger('app');

            $logPath = __DIR__ . '/../../storage/logs';
            if (!is_dir($logPath)) {
                mkdir($logPath, 0775, true);
                chmod($logPath, 0775);
            }

            // Format personnalisé
            $formatter = new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% %context%\n",
                "Y-m-d H:i:s"
            );

            // Handler rotatif (un fichier par jour, garde 14 jours)
            $handler = new RotatingFileHandler(
                $logPath . '/app.log',
                14,
                $_ENV['APP_DEBUG'] === 'true' ? Level::Debug : Level::Warning

            );
            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            // En développement, aussi en console
            if ($_ENV['APP_DEBUG'] === 'true') {
                $consoleHandler = new StreamHandler('php://stdout', Level::Debug);
                $consoleHandler->setFormatter($formatter);
                $logger->pushHandler($consoleHandler);
            }

            self::$instance = $logger;
        }

        return self::$instance;
    }
}
