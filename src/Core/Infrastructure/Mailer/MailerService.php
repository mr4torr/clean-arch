<?php

declare(strict_types=1);

namespace Core\Infrastructure\Mailer;

use Core\Domain\Mailer\MailerBuilderInterface;
use Core\Domain\Mailer\MailerServiceInterface;
use Symfony\Component\Mailer\Mailer as ComponentMailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
// Domain -
use Symfony\Component\Mime\Email;

use function Hyperf\Config\config;

final class MailerService implements MailerServiceInterface
{
    private ComponentMailer $mailer;

    public function __construct()
    {
        $this->mailer = new ComponentMailer(
            new EsmtpTransport(config('mail.mailer.smtp.host'), (int) config('mail.mailer.smtp.port'))
        );
    }

    public function send(MailerBuilderInterface $builder): void
    {
        $this->mailer->send($this->email($builder));
    }

    private function email(MailerBuilderInterface $builder): Email
    {
        if (! $builder->has('from')) {
            $builder->from(config('mail.from.address'), config('mail.from.name'));
        }

        $builder->addParam('appName', config('app_name'));
        $builder->addParam('appUrl', config('app_url'));

        [$fromEmail, $fromName] = $builder->get('from');
        [$toEmail, $toName] = $builder->get('to');
        $subject = $builder->get('subject') ?? 'Template Subject';
        $template = $builder->get('template');
        $params = $builder->get('params') ?? [];

        $email = (new Email())
            ->from(new Address($fromEmail, $fromName))
            ->to(new Address($toEmail, $toName))
            ->subject(self::template($subject, $params))
            ->html(self::template(file_get_contents($template), $params));

        $attachments = $builder->get('attachments') ?? [];
        foreach ($attachments as $key => $value) {
            $email->embedFromPath($value, $key);
        }

        return $email;
    }

    /**
     * @return null|array|string
     */
    private static function template(string $content, array $params): string
    {
        foreach ($params as $key => $value) {
            $content = preg_replace('/\{\{\s*?' . $key . '\s*?\}\}/is', (string) $value, $content);
        }
        return $content;
    }
}
