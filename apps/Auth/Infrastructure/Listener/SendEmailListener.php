<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Listener;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Shared\Mailer\MailerInterface;
use Auth\Domain\Event\SendConfirmationEmailEvent;

#[Listener]
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