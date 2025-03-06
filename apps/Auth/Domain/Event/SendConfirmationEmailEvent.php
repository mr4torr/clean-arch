<?php

declare(strict_types=1);

namespace Auth\Domain\Event;

use Auth\Domain\Entity\User;
use Shared\Event\EventInterface;

class SendConfirmationEmailEvent implements EventInterface
{
    public function __construct(public readonly User $user) {}
}
