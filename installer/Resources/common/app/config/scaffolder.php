<?php

declare(strict_types=1);

use Spiral\Scaffolder\Declaration;

/**
 * @see \Spiral\Scaffolder\Config\ScaffolderConfig
 */
return [
    'namespace' => 'App',
    'declarations' => [
        Declaration\BootloaderDeclaration::TYPE => [
            'namespace' => 'Application\Bootloader',
            'postfix' => 'Bootloader',
            'class' => Declaration\BootloaderDeclaration::class,
        ],
        Declaration\ConfigDeclaration::TYPE => [
            'namespace' => 'Application\Config',
            'postfix' => 'Config',
            'class' => Declaration\ConfigDeclaration::class,
            'options' => [
                'directory' => directory('config'),
            ],
        ],
        Declaration\ControllerDeclaration::TYPE => [
            'namespace' => 'Api\Web\Controller',
            'postfix' => 'Controller',
            'class' => Declaration\ControllerDeclaration::class,
        ],
        Declaration\MiddlewareDeclaration::TYPE => [
            'namespace' => 'Api\Web\Middleware',
            'postfix' => '',
            'class' => Declaration\MiddlewareDeclaration::class,
        ],
        Declaration\CommandDeclaration::TYPE => [
            'namespace' => 'Api\Cli\Command',
            'postfix' => 'Command',
            'class' => Declaration\CommandDeclaration::class,
        ],
        Declaration\JobHandlerDeclaration::TYPE => [
            'namespace' => 'Api\Job',
            'postfix' => 'Job',
            'class' => Declaration\JobHandlerDeclaration::class,
        ],
    ],
];
