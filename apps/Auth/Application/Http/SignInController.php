<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use App\Infrastructure\Support\IpAddress;
use Auth\Domain\SignIn;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\ValueObject\Password;
use Hyperf\Di\Annotation\Inject;
use Shared\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class SignInController extends AbstractController
{
    #[Inject]
    private IpAddress $ipAddress;

    public function __invoke(SignIn $service): ResponseInterface
    {
        $this->request->validate([
            "email" => "required|email",
            "password" => "required|min:8",
        ]);


        $headers = $this->request->getHeaders();
        $ipAddress = $headers['x-real-ip'] ?: ($headers['x-forwarded-for'] ?: $this->ipAddress->get());
        $userAgent = $headers['user-agent'] ?: null;

        $resource = $service->make(
            new Email($this->request->get('email')),
            new Password($this->request->get('password')),
            $ipAddress,
            $userAgent
        );

        return $this->response->success($resource);
    }
}
