<?php

declare(strict_types=1);

use Spiral\Debug\Exception\DumpException;
use Spiral\Debug\HtmlDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

if (!\function_exists('dd')) {
    function dd(mixed ...$vars): mixed
    {
        $dumper = new HtmlDumper();
        $throwable = new DumpException();

        foreach ($vars as $var) {
            $throwable->addDump(
                $dumper->dump((new VarCloner())->cloneVar($var), true)
            );
        }

        throw $throwable;
    }
}
