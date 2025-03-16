<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Application\UseCase\AuthenticateUser;
use Auth\Domain\Dto\Input\NetworkInfoDto;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\ValueObject\Password;
use Core\Domain\Jwt\TokenInterface;
use Core\Domain\Support\IpAddressInterface;
use Psr\Http\Message\ResponseInterface;
use Shared\Http\AbstractController;

class SignInController extends AbstractController
{
    public function __invoke(AuthenticateUser $service, TokenInterface $token, IpAddressInterface $ipAddress): ResponseInterface
    {
        $this->request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $headers = $this->request->getHeaders();
        $resource = $service->make(
            new Email($this->request->get('email')),
            new Password($this->request->get('password')),
            new NetworkInfoDto(
                $headers['x-real-ip'] ?: ($headers['x-forwarded-for'] ?: $ipAddress->get()),
                $headers['user-agent'] ?: null
            ),
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
