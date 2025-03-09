<?php

declare(strict_types=1);

namespace Auth\Domain;

use Auth\Domain\Entity\User;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Event\SendConfirmationEmailEvent;
use Auth\Domain\ValueObject\Email;
use Psr\EventDispatcher\EventDispatcherInterface;
use Shared\Support\TokenInterface;
use Shared\Exception\BusinessException;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;

class Reverify
{
    public function __construct(
        private TokenInterface $token,
        private UserDaoInterface $userDao,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function make(Email $email): void
    {
        if (!$user = $this->userDao->findByEmail($email)) {
            return;
        }

        if ($user->getEmailVerifiedAt() !== null) {
            throw new FieldException(['email' => ValidationCodeEnum::VERIFIED]);
        }

        $this->eventDispatcher->dispatch(
            SendConfirmationEmailEvent::make($this->token, $user)
        );
    }
}
