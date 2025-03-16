<?php

declare(strict_types=1);

namespace Auth\Application\UseCase;

use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Email\SendEmailInterface;
use Auth\Domain\ValueObject\Email;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;

class ResendConfirmation
{
    public function __construct(private UserDaoInterface $userDao, private SendEmailInterface $sendEmail)
    {
    }

    public function make(Email $email): void
    {
        if (!$email->validate()) {
            throw new FieldException(['email' => ValidationCodeEnum::EMAIL_INVALID]);
        }

        if (!($user = $this->userDao->findByEmail($email))) {
            return;
        }

        if ($user->isEmailVerified()) {
            throw new FieldException(['email' => ValidationCodeEnum::VERIFIED]);
        }

        if (!$user->isActive()) {
            throw new FieldException(['email' => $user->reason_status ?? ValidationCodeEnum::LOGIN_BLOCKED]);
        }

        $this->sendEmail->sendConfirmationEmail($user);
    }
}
