<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Domain\User\Repository\UserRepositoryInterface;
use installer\Resources\packages\cycle\src\Infrastructure\Persistence\CycleORM\CycleORMUserRepository;
use Spiral\Boot\Bootloader\Bootloader;

final class PersistenceBootloader extends Bootloader
{
    protected const SINGLETONS = [
        UserRepositoryInterface::class => CycleORMUserRepository::class,
    ];
}