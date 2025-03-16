<?php

declare(strict_types=1);

namespace Core\Domain\Mailer;

use Core\Domain\Mailer\MailerBuilderInterface;

interface MailerServiceInterface
{
    public function send(MailerBuilderInterface $builder): void;
}
