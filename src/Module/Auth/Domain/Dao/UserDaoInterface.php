<?php

declare(strict_types=1);

namespace Auth\Domain\Dao;

use Auth\Domain\Entity\User;
use Auth\Domain\ValueObject\Email;

interface UserDaoInterface
{
    public function create(User $user): ?User;
    public function delete(string $id): bool;
    public function find(string $id, array $columns = ["*"]): ?User;
    public function findByEmail(Email $email, array $columns = ["*"]): ?User;
    public function verified(User $user): bool;
    public function emailAlreadyExists(Email $email): bool;
}
