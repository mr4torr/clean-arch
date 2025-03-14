<?php

declare(strict_types=1);

namespace Auth\Domain\Event;

use Shared\Token\TokenInterface;
use Shared\Mailer\MailerBuilder;
use Auth\Domain\Entity\User;
use Auth\Domain\ValueObject\TokenEmail;

class SendConfirmationEmailEvent
{
    public function __construct(public readonly MailerBuilder $builder) {}

    public static function make(TokenInterface $token, User $user): self
    {
        $builder = (new MailerBuilder((string)$user->getEmail(), $user->getName()))
            ->subject('{{appName}} - Ativação de conta!')
            ->template(dirname(__DIR__) . '/Email/sign-up.html')
            ->addParam('name', $user->getName())
            ->addParam('email', (string) $user->getEmail())
            ->addParam('token', $token->encode(new TokenEmail($user->getId())));

        return new self($builder);
    }
}
