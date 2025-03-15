<?php

declare(strict_types=1);

namespace Auth\Application\Listener;

use Hyperf\Event\Contract\ListenerInterface;
// Core -
use App\Domain\Mailer\MailerServiceInterface;
// Domain
use Auth\Application\Event\SendEmailEvent;

class SendEmailListener implements ListenerInterface
{
    public function __construct(private MailerServiceInterface $mailer) {}

    public function listen(): array
    {
        return [SendEmailEvent::class];
    }

    public function process($event): void
    {
        $this->mailer->send($event->builder);
    }
}
