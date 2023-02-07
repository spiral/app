<?php

declare(strict_types=1);

namespace Installer\Package\Generator\CycleBridge;

use Installer\Application\ApplicationInterface;
use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Package\DoctrineCollections;
use Installer\Package\IlluminateCollections;
use Installer\Package\LoophpCollections;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\Literal;

final class Config implements GeneratorInterface
{
    private const FILENAME = 'cycle.php';

    public function process(Context $context): void
    {
        $collections = $this->getInstalledCollections($context->application);

        $config = \file_get_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME
        );

        \file_put_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
            \str_replace(
                [':collections:', "':collectionsFactory:'"],
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
            Collections::Loophp => [
                Collections::Loophp->value => new Literal('new LoophpCollectionFactory()'),
            ],
            default => [
                Collections::Array->value => new Literal('new ArrayCollectionFactory()'),
            ]
        };
    }

    private function getInstalledCollections(ApplicationInterface $application): Collections
    {
        return match (true) {
            $application->isPackageInstalled(new DoctrineCollections()) => Collections::Doctrine,
            $application->isPackageInstalled(new IlluminateCollections()) => Collections::Illuminate,
            $application->isPackageInstalled(new LoophpCollections()) => Collections::Loophp,
            default => Collections::Array
        };
    }
}
