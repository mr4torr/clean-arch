<?php

declare(strict_types=1);

namespace Auth\Domain;

use Carbon\Carbon;
use Psr\EventDispatcher\EventDispatcherInterface;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Event\SendForgotEmailEvent;
use Auth\Domain\Event\SendConfirmationEmailEvent;
use Shared\Token\TokenInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;

class Forgot
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

        if (
            $user->getEmailVerifiedAt() === null &&
            Carbon::parse($user->getUpdatedAt())->diffInMinutes(Carbon::now()) < 1800
        ) {
            throw new FieldException(['email' => ValidationCodeEnum::CHECK_EMAIL_FOR_RESET]);
        }

        if ($user->getEmailVerifiedAt() === null) {
            $this->eventDispatcher->dispatch(
                SendConfirmationEmailEvent::make($this->token, $user)
            );
            return;
        }

        $this->eventDispatcher->dispatch(
            SendForgotEmailEvent::make($this->token, $user)
        );
    }
}
