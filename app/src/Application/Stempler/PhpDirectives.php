<?php

declare(strict_types=1);

namespace App\Application\Stempler;

use Spiral\Stempler\Directive\AbstractDirective;

final class PhpDirectives extends AbstractDirective
{
    public function renderPhpVersion(): string
    {
        return '<?= "PHP " . PHP_VERSION ?>';
    }
}
