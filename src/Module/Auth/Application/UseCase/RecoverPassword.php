<?php

declare(strict_types=1);

namespace Auth\Application\UseCase;

use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Email\SendEmailInterface;
use Auth\Domain\ValueObject\Email;
use DateTime;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;

class RecoverPassword
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

        $diffInMinutes = ((new DateTime())->getTimestamp() - $user->updated_at->getTimestamp()) / 60;
        // se o tempo for menor que 60 minutos
        if (!$user->isEmailVerified() && $diffInMinutes < 60) {
            throw new FieldException(['email' => ValidationCodeEnum::CHECK_EMAIL_FOR_RESET]);
        }

        if (!$user->isEmailVerified()) {
            $this->sendEmail->sendConfirmationEmail($user);
            return;
        }

        $this->sendEmail->sendForgotEmail($user);
    }
}
