<?php

declare(strict_types=1);

namespace App\Application\Stempler;

use Spiral\Stempler\Directive\AbstractDirective;

final class FrameworkDirectives extends AbstractDirective
{
    public function renderSpiralVersion(): string
    {
        return '<?= "Spiral Framework 3" ?>';
    }
}
