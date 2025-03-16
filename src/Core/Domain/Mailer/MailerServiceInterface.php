<?php

declare(strict_types=1);

namespace Core\Domain\Mailer;

interface MailerServiceInterface
{
    public function send(MailerBuilderInterface $builder): void;
}
