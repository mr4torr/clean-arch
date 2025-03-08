<?php

namespace Shared\Mailer;

class MailerBuilder
{
    private array $parameters = [];

    public function __construct(string $email, ?string $name = null)
    {
        $this->parameters['to'] = [$email, $name];
    }

    public function to(string $email, ?string $name = null): self
    {
        return new self($email, $name);
    }

    public function from(string $email, ?string $name = null): self
    {
        $this->parameters['from'] = [$email, $name];
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->parameters['subject'] = $subject;
        return $this;
    }

    public function template(string $template): self
    {
        $this->parameters['template'] = $template;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return MailerBuilder
     */
    public function addParam(string $key, $value): self
    {
        $this->parameters['params'][$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return MailerBuilder
     */
    public function addAttachment(string $key, $value): self
    {
        $this->parameters['attachments'][$key] = $value;
        return $this;
    }

    public function has(string $key): bool
    {
        return !empty($this->parameters[$key]);
    }

    public function get(string $key): mixed
    {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }


    public function toArray(): MailerBuilder
    {
        return $this;
    }
}
