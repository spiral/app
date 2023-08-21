<?php

declare(strict_types=1);

namespace Installer\Internal\Configurator;

use Installer\Internal\Console\Output;

final class InstallationInstructionRenderer
{
    public function __construct(
        private readonly string $projectRoot,
    ) {
    }

    /**
     * @return \Generator<Output>
     */
    public function render(): \Generator
    {
        yield Output::success('Installation complete!');
        yield Output::write('');
        yield Output::comment('Next steps:');

        yield from $this->renderForApplication();
    }

    /**
     * @return \Generator<Output>
     */
    public function renderForApplication(): \Generator
    {
        yield Output::write(\sprintf('  1. Go to the project root directory', \rtrim($this->projectRoot, '/')));
        yield Output::write('');
        yield Output::write(\sprintf('     <info>cd %s</info>', $this->projectRoot));
        yield Output::write('');
        yield Output::write(\sprintf('  2. Read more about the project configuration in the project readme file: <info>%s/README.md</info>', \rtrim($this->projectRoot, '/')));
        yield Output::write('');
    }
}
