<?php

declare(strict_types=1);

namespace Auth\Domain\Email;

// Domain -
use Auth\Domain\Entity\User;

interface SendEmailInterface
{
    public function sendConfirmationEmail(User $user): void;
    public function sendForgotEmail(User $user): void;
}
