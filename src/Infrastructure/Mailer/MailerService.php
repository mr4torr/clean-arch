<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer;

use Shared\Mailer\MailerBuilder;
use Shared\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer as ComponentMailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use function Hyperf\Config\config;

class MailerService implements MailerInterface
{
    public function send(MailerBuilder $builder): void
    {
        if (!$builder->has('from')) {
            $builder->from(config('mail.from.address'), config('mail.from.name'));
        }

        $builder->addParam('appName', config('app_name'));
        $builder->addParam('appUrl', config('app_url'));

        [$fromEmail, $fromName] = $builder->get('from');
        [$toEmail, $toName] = $builder->get('to');
        $subject = $builder->get('subject') ?? 'Template Subject';
        $template = $builder->get('template');
        $params = $builder->get('params') ?? [];
        $attachments = $builder->get('attachments') ?? [];

        $email = (new Email())
            ->from(new Address($fromEmail, $fromName))
            ->to(new Address($toEmail, $toName))
            ->subject(static::template($subject, $params))
            ->html(static::template(file_get_contents($template), $params));

        foreach ($attachments as $key => $value) {
            $email->embedFromPath($value, $key);
        }

        $transport = new EsmtpTransport(config('mail.mailer.smtp.host'), (int) config('mail.mailer.smtp.port'));
        $mailer = new ComponentMailer($transport);

        $mailer->send($email);
    }

    /**
     * @param string $content
     * @param array $params
     * @return array|string|null
     */
    private static function template(string $content, array $params): string
    {
        foreach ($params as $key => $value) {
            $content = preg_replace('/\{\{\s*?' . $key . '\s*?\}\}/is', (string) $value, $content);
        }
        return $content;
    }
}
