<?php

declare(strict_types=1);

/**
 * Dump value.
 */
function dump($value): mixed
{
    return \Symfony\Component\VarDumper\VarDumper::dump($value);
}
