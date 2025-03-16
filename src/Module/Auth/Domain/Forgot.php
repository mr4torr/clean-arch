<?php

declare(strict_types=1);

namespace Auth\Domain;

// Shared -
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;
// Domain -
use Auth\Domain\ValueObject\Email;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Email\SendEmailInterface;

class Forgot
{
    public function __construct(private UserDaoInterface $userDao, private SendEmailInterface $sendEmail) {}

    public function make(Email $email): void
    {
        if (!($user = $this->userDao->findByEmail($email))) {
            return;
        }

        $diffInMinutes = ((new \DateTime())->getTimestamp() - $user->updated_at->getTimestamp()) / 60;
        // se o tempo for menor que 60 minutos
        if (!$user->isEmailVerified() && $diffInMinutes < 60) {
            throw new FieldException(["email" => ValidationCodeEnum::CHECK_EMAIL_FOR_RESET]);
        }

        if (!$user->isEmailVerified()) {
            $this->sendEmail->sendConfirmationEmail($user);
            return;
        }

        $this->sendEmail->sendForgotEmail($user);
    }
}
