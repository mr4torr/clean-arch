<?php

declare(strict_types=1);

namespace Core\Domain\Mailer;

interface MailerBuilderInterface
{
    public function to(string $email, ?string $name = null): self;

    public function from(string $email, ?string $name = null): self;

    public function subject(string $subject): self;

    public function template(string $template): self;

    public function addParam(string $key, $value): self;

    public function addAttachment(string $key, $value): self;

    public function has(string $key): bool;

    public function get(string $key): mixed;
}
