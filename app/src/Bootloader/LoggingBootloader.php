<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Bootloader;

use Monolog\Logger;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Monolog\Config\MonologConfig;

class LoggingBootloader extends Bootloader
{
    public function init(MonologBootloader $monolog): void
    {
        // http level errors
        $monolog->addHandler(
            channel: ErrorHandlerMiddleware::class,
            handler: $monolog->logRotate(
                directory('runtime') . 'logs/http.log'
            )
        );

        // app level errors
        $monolog->addHandler(
            channel: MonologConfig::DEFAULT_CHANNEL,
            handler: $monolog->logRotate(
                filename: directory('runtime') . 'logs/error.log',
                level: Logger::ERROR,
                maxFiles: 25,
                bubble: false
            )
        );

        // debug and info messages via global LoggerInterface
        $monolog->addHandler(
            channel: MonologConfig::DEFAULT_CHANNEL,
            handler: $monolog->logRotate(
                filename: directory('runtime') . 'logs/debug.log'
            )
        );
    }
}
