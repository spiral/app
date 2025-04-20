<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge\Generator;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Module\CycleBridge\Collections\DoctrineCollections;
use Installer\Module\CycleBridge\Collections\IlluminateCollections;
use Installer\Module\CycleBridge\Collections\LoophpCollections;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\Literal;

final class Config implements GeneratorInterface
{
    private const FILENAME = 'cycle.php';

    public function process(Context $context): void
    {
        $collections = $this->getInstalledCollections($context->application);

        $config = \file_get_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
        );

        $typecasters = ['\Cycle\ORM\Parser\Typecast::class'];

        if ($context->application->hasSkeleton()) {
            $typecasters[] = '\App\Infrastructure\CycleORM\Typecaster\UuidTypecast::class';
        }

        \file_put_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
            \str_replace(
                [':typecasters:', ':collections:', "':collectionsFactory:'"],
                [
                    \implode(', ', $typecasters),
                    $collections->value,
                    (new Dumper())->dump($this->getFactory($collections)),
                ],
                $config,
            ),
        );
    }

    private function getFactory(Collections $collections): array
    {
        return match ($collections) {
            Collections::Doctrine => [
                Collections::Doctrine->value => Literal::new('Cycle\ORM\Collection\DoctrineCollectionFactory'),
            ],
            Collections::Illuminate => [
                Collections::Illuminate->value => Literal::new('Cycle\ORM\Collection\IlluminateCollectionFactory'),
            ],
            Collections::Loophp => [
                Collections::Loophp->value => Literal::new('Cycle\ORM\Collection\LoophpCollectionFactory'),
            ],
            default => [
                Collections::Array->value => Literal::new('Cycle\ORM\Collection\ArrayCollectionFactory'),
            ],
        };
    }

    private function getInstalledCollections(ApplicationInterface $application): Collections
    {
        return match (true) {
            $application->isPackageInstalled(new DoctrineCollections()) => Collections::Doctrine,
            $application->isPackageInstalled(new IlluminateCollections()) => Collections::Illuminate,
            $application->isPackageInstalled(new LoophpCollections()) => Collections::Loophp,
            default => Collections::Array,
        };
    }
}
