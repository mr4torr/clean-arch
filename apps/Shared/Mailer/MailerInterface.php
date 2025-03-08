<?php

declare(strict_types=1);

namespace Shared\Mailer;

interface MailerInterface
{
    public function send(MailerBuilder $builder) : void;
}
