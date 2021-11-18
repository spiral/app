<?php

declare(strict_types=1);

namespace App\Bootloader;

use Cycle\Database\DatabaseProviderInterface;
use Cycle\ORM\Collection\ArrayCollectionFactory;
use Cycle\ORM\Collection\CollectionFactoryInterface;
use Cycle\ORM\Collection\DoctrineCollectionFactory;
use Cycle\ORM\Collection\IlluminateCollectionFactory;
use Cycle\ORM\Config\RelationConfig;
use Cycle\ORM\Factory;
use Cycle\ORM\FactoryInterface;
use Cycle\ORM\ORM;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\SchemaInterface;
use Cycle\ORM\Transaction;
use Cycle\ORM\TransactionInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\FinalizerInterface;
use Spiral\Core\Container;
use Spiral\Cycle\RepositoryInjector;

final class CycleOrmV2Bootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        DatabaseBootloader::class,
        SchemaBootloader::class,
    ];

    protected const BINDINGS = [
        TransactionInterface::class => Transaction::class,

        // You can choose preferable collections for relations
        CollectionFactoryInterface::class => ArrayCollectionFactory::class,
        // CollectionFactoryInterface::class => DoctrineCollectionFactory::class,
        // CollectionFactoryInterface::class => IlluminateCollectionFactory::class,
    ];

    protected const SINGLETONS = [
        ORMInterface::class => ORM::class,
        ORM::class => [self::class, 'orm'],
        FactoryInterface::class => [self::class, 'factory'],
    ];

    public function boot(Container $container, FinalizerInterface $finalizer): void
    {
        $finalizer->addFinalizer(
            function () use ($container): void {
                if ($container->hasInstance(ORMInterface::class)) {
                    $container->get(ORMInterface::class)->getHeap()->clean();
                }
            }
        );

        $container->bindInjector(RepositoryInterface::class, RepositoryInjector::class);
    }

    private function orm(
        FactoryInterface $factory,
        SchemaInterface $schema = null
    ): ORMInterface {
        return new ORM($factory, $schema);
    }

    private function factory(
        DatabaseProviderInterface $dbal,
        Container $container,
        CollectionFactoryInterface $collectionFactory
    ): FactoryInterface {
        return new Factory($dbal, RelationConfig::getDefault(), $container, $collectionFactory);
    }
}
