<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Listener;

use Auth\Application\Event\SendEmailEvent;
use Core\Domain\Mailer\MailerServiceInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

#[Listener]
class SendEmailListener implements ListenerInterface
{
    public function __construct(private MailerServiceInterface $mailer)
    {
    }

    public function listen(): array
    {
        return [SendEmailEvent::class];
    }

    public function process($event): void
    {
        $this->mailer->send($event->builder);
    }
}
