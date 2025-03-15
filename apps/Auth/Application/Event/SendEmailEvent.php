<?php

declare(strict_types=1);

namespace Auth\Application\Event;

use App\Domain\Mailer\MailerBuilderInterface;

class SendEmailEvent
{
    public function __construct(public readonly MailerBuilderInterface $builder) {}
}
