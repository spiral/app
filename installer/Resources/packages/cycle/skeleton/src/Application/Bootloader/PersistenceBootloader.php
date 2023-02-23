<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Persistence\CycleORM\CycleORMUserRepository;
use Spiral\Boot\Bootloader\Bootloader;

/**
 * Simple bootloaders that registers Domain repositories.
 */
final class PersistenceBootloader extends Bootloader
{
    protected const SINGLETONS = [
        UserRepositoryInterface::class => CycleORMUserRepository::class,
    ];
}
