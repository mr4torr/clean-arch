<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Shared\Mailer\MailerInterface;
use Auth\Domain\Event\SendForgotEmailEvent;
use Auth\Domain\Event\SendConfirmationEmailEvent;

class SendEmailListener implements ListenerInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function listen(): array
    {
        return [
            SendConfirmationEmailEvent::class,
            SendForgotEmailEvent::class
        ];
    }

    /**
     * @param SendConfirmationEmailEvent $event
     */
    public function process($event): void
    {
        $this->mailer->send($event->builder);
    }
}