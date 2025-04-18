<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

return \Spiral\CodeStyle\Builder::create()
    ->include(__DIR__)
    ->include(__FILE__)
    ->cache('./runtime/php-cs-fixer.cache')
    ->allowRisky()
    ->build();
