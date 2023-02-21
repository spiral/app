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
            'namespace' => 'Endpoint\Http',
            'postfix' => 'Controller',
            'class' => Declaration\ControllerDeclaration::class,
        ],
        Declaration\MiddlewareDeclaration::TYPE => [
            'namespace' => 'Application\Http\Middleware',
            'postfix' => '',
            'class' => Declaration\MiddlewareDeclaration::class,
        ],
        Declaration\CommandDeclaration::TYPE => [
            'namespace' => 'Endpoint\Cli',
            'postfix' => 'Command',
            'class' => Declaration\CommandDeclaration::class,
        ],
        Declaration\JobHandlerDeclaration::TYPE => [
            'namespace' => 'Endpoint\Job',
            'postfix' => 'Job',
            'class' => Declaration\JobHandlerDeclaration::class,
        ],
    ],
];
