<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use Installer\Internal\Generator\AbstractConfigurator;
use Spiral\Reactor\Writer;

class BootloaderConfigurator extends AbstractConfigurator
{
    /** @var array<non-empty-string, Constant> */
    private array $constants = [];

    public function __construct(
        string $class,
        Writer $writer
    ) {
        parent::__construct($class, $writer);
    }

    protected function registerConstant(string $name): void
    {
        $this->constants[$name] = new Constant($name);
    }

    public function __destruct()
    {
        $this->inject();
        $this->write();
    }

    protected function append(string $constant, Value $binding): void
    {
        if (!isset($this->constants[$constant])) {
            $this->registerConstant($constant);
        }

        $this->constants[$constant]->addValue($binding);
    }

    private function inject(): void
    {
        $class = $this->declaration->getClass($this->reflection->getName());
        foreach ($this->constants as $constant) {
            $constant->inject($class, $this->namespace);
        }
    }
}
