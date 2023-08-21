<?php

declare(strict_types=1);

namespace Tests\Module\Validators;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Module\Validators\Spiral\Package;
use Tests\Module\AbstractModule;
use Spiral\Validation\Bootloader\ValidationBootloader;
use Spiral\Validator\Bootloader\ValidatorBootloader;
use Spiral\Filter\ValidationHandlerMiddleware;

final class Spiral extends AbstractModule
{
    public function __construct()
    {
        parent::__construct(new Package());
    }

    public function getBootloaders(ApplicationInterface $application): array
    {
        return [
            ValidationBootloader::class,
            ValidatorBootloader::class,
        ];
    }

    public function getMiddleware(ApplicationInterface $application): array
    {
        return [
            'web' => [
                ValidationHandlerMiddleware::class
            ]
        ];
    }
}
