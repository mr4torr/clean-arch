<?php

declare(strict_types=1);

namespace Auth\Domain\Event;

use Shared\Support\TokenInterface;
use Shared\Mailer\MailerBuilder;
use Auth\Domain\Entity\User;

class SendConfirmationEmailEvent
{
    public function __construct(public readonly MailerBuilder $builder) {}

    public static function make(TokenInterface $token, User $user): self
    {
        $builder = (new MailerBuilder($user->email, $user->name))
            ->subject('{{appName}} - Ativação de conta!')
            ->template(dirname(__DIR__) . '/Emails/sign-up.html')
            ->addParam('name', $user->name)
            ->addParam('email', $user->email)
            ->addParam('token', $token->encode(['id' => $user->id]));

        return new self($builder);
    }
}
