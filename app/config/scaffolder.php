<?php

declare(strict_types=1);

use Spiral\Scaffolder\Declaration;

/**
 * @see \Spiral\Scaffolder\Config\ScaffolderConfig
 */
return [
    'namespace'    => 'App',
    'declarations' => [
        Declaration\BootloaderDeclaration::TYPE => [
            'namespace' => 'Application\\Bootloader',
        ],
        Declaration\ConfigDeclaration::TYPE => [
            'namespace' => 'Application\\Config',
        ],
        Declaration\ControllerDeclaration::TYPE => [
            'namespace' => 'Api\\Web\\Controller',
        ],
        Declaration\FilterDeclaration::TYPE => [
            'namespace' => 'Api\\Web\\Filter',
        ],
        Declaration\MiddlewareDeclaration::TYPE => [
            'namespace' => 'Application\\Middleware',
        ],
        Declaration\CommandDeclaration::TYPE => [
            'namespace' => 'Api\\Cli\\Command',
        ],
        Declaration\JobHandlerDeclaration::TYPE => [
            'namespace' => 'Api\\Job',
        ],
    ],
];
