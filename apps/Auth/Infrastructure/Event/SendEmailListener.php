<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Event;

use Hyperf\Event\Contract\ListenerInterface;
use Auth\Domain\Event\SendConfirmationEmailEvent;
use Shared\Mailer\MailerInterface;

class SendEmailListener implements ListenerInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function listen(): array
    {
        return [
            SendConfirmationEmailEvent::class,
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