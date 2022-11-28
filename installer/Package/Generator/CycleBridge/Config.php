<?php

declare(strict_types=1);

namespace Installer\Package\Generator\CycleBridge;

use Cycle\ORM\Collection\ArrayCollectionFactory;
use Cycle\ORM\Collection\DoctrineCollectionFactory;
use Cycle\ORM\Collection\IlluminateCollectionFactory;
use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Installer\Package\Package;
use Installer\Package\Packages;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\Literal;

final class Config implements GeneratorInterface
{
    private const FILENAME = 'cycle.php';

    public function process(Context $context): void
    {
        $collections = $this->getInstalledCollections($context);

        $config = \file_get_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME
        );

        \file_put_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
            \str_replace(
                [':collections:', ':collectionsFactory:'],
                [$collections->value, (new Dumper())->dump($this->getFactory($collections))],
                $config
            ),
        );
    }

    private function getFactory(Collections $collections): array
    {
        return match ($collections) {
            Collections::Doctrine => [
                Collections::Doctrine->value => new Literal('new DoctrineCollectionFactory()'),
            ],
            Collections::Illuminate => [
                Collections::Illuminate->value => new Literal('new IlluminateCollectionFactory()'),
            ],
            default => [Collections::Array->value => new Literal('new ArrayCollectionFactory()')]
        };
    }

    private function getInstalledCollections(Context $context): Collections
    {
        $doctrine = new Package(Packages::DoctrineCollections);
        $illuminate = new Package(Packages::IlluminateCollections);

        if (\in_array($doctrine->getName(), $context->composerDefinition['extra']['spiral']['packages'], true)) {
            return Collections::Doctrine;
        }

        if (\in_array($illuminate->getName(), $context->composerDefinition['extra']['spiral']['packages'], true)) {
            return Collections::Illuminate;
        }

        return Collections::Array;
    }
}
