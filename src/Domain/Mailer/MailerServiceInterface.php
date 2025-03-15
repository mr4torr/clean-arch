<?php

declare(strict_types=1);

namespace App\Domain\Mailer;

use App\Domain\Mailer\MailerBuilderInterface;

interface MailerServiceInterface
{
    public function send(MailerBuilderInterface $builder): void;
}
