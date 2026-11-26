<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge\Generator;

use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Module\CycleBridge\Collections\DoctrineCollections;
use Installer\Module\CycleBridge\Collections\IlluminateCollections;
use Installer\Module\CycleBridge\Collections\LoophpCollections;

final class Config implements GeneratorInterface
{
    private const FILENAME = 'cycle.php';
    private const TYPECAST_HANDLER = <<<'PHP'
                    // SchemaInterface::TYPECAST_HANDLER => [
                    //    :typecasters:,
                    // ],
        PHP;
    private const FACTORIES = [
        'array' => 'ArrayCollectionFactory',
        'doctrine' => 'DoctrineCollectionFactory',
        'illuminate' => 'IlluminateCollectionFactory',
        'loophp' => 'LoophpCollectionFactory',
    ];

    public function process(Context $context): void
    {
        $collections = $this->getInstalledCollections($context->application);

        $config = \file_get_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
        );

        // The skeleton entities use the UUID typecast, so the handler is enabled with the skeleton
        if ($context->application->hasSkeleton()) {
            $config = \str_replace(
                self::TYPECAST_HANDLER,
                \implode(PHP_EOL, [
                    '            \Cycle\ORM\SchemaInterface::TYPECAST_HANDLER => [',
                    '                \Cycle\ORM\Parser\Typecast::class,',
                    '                \App\Infrastructure\Cycle\Typecaster\UuidTypecast::class,',
                    '            ],',
                ]),
                $config,
            );
        }

        \file_put_contents(
            $context->applicationRoot . 'app/config/' . self::FILENAME,
            \str_replace(
                [':typecasters:', ':collections:', "':collectionsFactory:'"],
                [
                    '\Cycle\ORM\Parser\Typecast::class',
                    $collections->value,
                    $this->renderFactories($collections),
                ],
                $config,
            ),
        );
    }

    /**
     * The array factory is always available, the installed collections package adds its own.
     */
    private function renderFactories(Collections $collections): string
    {
        $lines = ['['];
        foreach (\array_unique([Collections::Array->value, $collections->value]) as $name) {
            $lines[] = \sprintf(
                "                '%s' => new \\Cycle\\ORM\\Collection\\%s(),",
                $name,
                self::FACTORIES[$name],
            );
        }
        $lines[] = '            ]';

        return \implode(PHP_EOL, $lines);
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
