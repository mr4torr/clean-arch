<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
// Core -
use Core\Domain\Jwt\TokenInterface;
use Core\Infrastructure\Support\IpAddress;
// Shared -
use Shared\Http\AbstractController;
// Domain -
use Auth\Domain\SignIn;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\ValueObject\Password;

class SignInController extends AbstractController
{
    #[Inject]
    private IpAddress $ipAddress;

    public function __invoke(SignIn $service, TokenInterface $token): ResponseInterface
    {
        $this->request->validate([
            "email" => "required|email",
            "password" => "required|min:8",
        ]);

        $headers = $this->request->getHeaders();
        $ipAddress = $headers["x-real-ip"] ?: ($headers["x-forwarded-for"] ?: $this->ipAddress->get());
        $userAgent = $headers["user-agent"] ?: null;

        $resource = $service->make(
            new Email($this->request->get("email")),
            new Password($this->request->get("password")),
            $ipAddress,
            $userAgent
        );

        return $this->response->success([
            'access_token' => $token->encode(
                $resource->accessToken->toArray(),
                $resource->accessToken->expiresAt()
            ),
            'refresh_token' => $token->encode(
                $resource->refreshToken->toArray(),
                $resource->refreshToken->expiresAt()
            ),
        ]);
    }
}
