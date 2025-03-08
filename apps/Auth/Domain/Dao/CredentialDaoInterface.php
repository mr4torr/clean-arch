<?php

declare(strict_types=1);

namespace Auth\Domain\Dao;

use Auth\Domain\Entity\Credential;

interface CredentialDaoInterface
{
    public function create(Credential $credencial): bool;
    public function delete(string $id): bool;
}
