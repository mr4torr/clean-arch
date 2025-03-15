<?php

declare(strict_types=1);

namespace Auth\Application\Dispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
// Shared -
use App\Domain\Jwt\TokenInterface;
use App\Domain\Mailer\MailerBuilderInterface;
// Domain -
use Auth\Application\Event\SendEmailEvent;
use Auth\Domain\Entity\User;
use Auth\Domain\Email\SendEmailInterface;
use Auth\Domain\Token\TokenForgotEmail;
use Auth\Domain\Token\TokenPayloadInterface;
use Auth\Domain\Token\TokenConfirmationEmail;

class SendEmail implements SendEmailInterface
{
    public function __construct(
        private TokenInterface $token,
        private EventDispatcherInterface $eventDispatcher,
        private MailerBuilderInterface $mailerBuilder
    ) {}

    public function sendForgotEmail(User $user): void
    {
        $builder = $this->builder($user, new TokenForgotEmail($user->id))
            ->subject("{{appName}} - Recuperação de conta!")
            ->template(dirname(__DIR__, 2) . "/Domain/Email/template/forgot.html");

        $this->eventDispatcher->dispatch(new SendEmailEvent($builder));
    }

    public function sendConfirmationEmail(User $user): void
    {
        $builder = $this->builder($user, new TokenConfirmationEmail($user->id))
            ->subject("{{appName}} - Ativação de conta!")
            ->template(dirname(__DIR__, 2) . "/Domain/Email/template/sign-up.html");

        $this->eventDispatcher->dispatch(new SendEmailEvent($builder));
    }

    private function builder(User $user, TokenPayloadInterface $token): MailerBuilderInterface
    {
        return $this->mailerBuilder
            ->to((string) $user->email, $user->name)
            ->addParam("name", $user->name)
            ->addParam("email", (string) $user->email)
            ->addParam("token", $this->token->encode($token->toArray(), $token->expiresAt()));
    }
}
