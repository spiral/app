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
use Spiral\Monolog\LogFactory;

class LoggingBootloader extends Bootloader
{
    /**
     * Bootloader execute method.
     *
     * @param MonologBootloader $monolog
     */
    public function boot(MonologBootloader $monolog): void
    {
        // http level errors
        $monolog->addHandler(ErrorHandlerMiddleware::class, $monolog->logRotate(
            directory('runtime') . 'logs/http.log'
        ));

        // app level errors
        $monolog->addHandler(LogFactory::DEFAULT, $monolog->logRotate(
            directory('runtime') . 'logs/error.log',
            Logger::ERROR,
            25,
            false
        ));

        // debug and info messages via global LoggerInterface
        $monolog->addHandler(LogFactory::DEFAULT, $monolog->logRotate(
            directory('runtime') . 'logs/debug.log'
        ));
    }
}
