<?php

declare(strict_types=1);

namespace Auth\Domain;

// Shared -
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;
// Domain -
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Email\SendEmailInterface;
use Auth\Domain\ValueObject\Email;

class Reverify
{
    public function __construct(private UserDaoInterface $userDao, private SendEmailInterface $sendEmail) {}

    public function make(Email $email): void
    {
        if (!($user = $this->userDao->findByEmail($email))) {
            return;
        }

        if ($user->isEmailVerified()) {
            throw new FieldException(["email" => ValidationCodeEnum::VERIFIED]);
        }

        if (!$user->isActive()) {
            throw new FieldException(["email" => $user->reason_status ?? ValidationCodeEnum::LOGIN_BLOCKED]);
        }

        $this->sendEmail->sendConfirmationEmail($user);
    }
}
