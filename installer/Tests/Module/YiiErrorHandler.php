<?php

declare(strict_types=1);

namespace Tests\Module;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\ErrorHandler\Yii\Package;
use Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader;

final class YiiErrorHandler extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            YiiErrorHandlerBootloader::class,
        ];
    }
}
