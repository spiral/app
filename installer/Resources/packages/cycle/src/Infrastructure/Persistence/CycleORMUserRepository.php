<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use Cycle\ORM\Select\Repository;

final class CycleORMUserRepository extends Repository implements UserRepositoryInterface
{
    public function findById(int $id): User
    {
        $user = $this->findByPK($id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}