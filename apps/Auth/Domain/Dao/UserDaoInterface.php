<?php

declare(strict_types=1);

namespace Auth\Domain\Dao;

use Auth\Domain\Entity\User;
use Auth\Domain\ValueObject\Email;

interface UserDaoInterface
{
    public function create(User $user): void;

    public function emailAlreadyExists(Email $email): bool;
}
