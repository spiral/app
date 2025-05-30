<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use Cycle\ORM\Select\Repository;
use App\Domain\User\Entity\User;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;

/**
 * @template TUser of User
 * @extends Repository<TUser>
 */
final class CycleORMUserRepository extends Repository implements UserRepositoryInterface
{
    #[\Override]
    public function getById(int $id): User
    {
        $user = $this->findByPK($id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
