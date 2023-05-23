<?php

declare(strict_types=1);

namespace App;

class Kernel extends \Spiral\Framework\Kernel
{
    protected const SYSTEM = [];

    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [];

    /*
     * Application specific services and extensions.
     */
    protected const APP = [];
}
