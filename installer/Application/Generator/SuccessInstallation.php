<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;

final class SuccessInstallation implements GeneratorInterface
{
    public function process(Context $context): void
    {
        $context->notification->addMessage('What\'s next?', $this->getMessage());
    }

    private function getMessage(): string
    {
        return <<<TEXT
          <info>The application was successfully installed.
          Please, configure the environment variables in the <comment>.env</comment> file at the application's root.

          Documentation: <comment>https://spiral.dev/docs</comment></info>
        TEXT;
    }
}
