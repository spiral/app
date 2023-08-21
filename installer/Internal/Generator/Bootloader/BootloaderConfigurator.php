<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use Installer\Internal\ClassMetadataInterface;
use Installer\Internal\Events\ConstantInjected;
use Installer\Internal\EventStorage;
use Installer\Internal\Generator\AbstractConfigurator;
use Spiral\Reactor\Writer;

class BootloaderConfigurator extends AbstractConfigurator
{
    /** @var array<non-empty-string, Constant> */
    private array $constants = [];

    public function __construct(
        ClassMetadataInterface $class,
        Writer $writer,
        protected readonly ?EventStorage $eventStorage = null
    ) {
        parent::__construct($class, $writer);
    }

    public function __destruct()
    {
        $this->inject();
        $this->write();
    }

    protected function registerConstant(string $name): void
    {
        $this->constants[$name] = new Constant($name);
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
        $class = $this->declaration->getClass($this->class->getName());
        foreach ($this->constants as $constant) {
            $this->eventStorage?->addEvent(new ConstantInjected($this->class->getName(), $constant));
            $constant->inject($class, $this->namespace);
        }
    }
}
