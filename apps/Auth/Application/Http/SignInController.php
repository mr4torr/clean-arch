<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Auth\Domain\SignIn;
use Auth\Domain\ValueObject\Email;
use Auth\Domain\ValueObject\Password;
use Shared\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class SignInController extends AbstractController
{
    public function __invoke(SignIn $service): ResponseInterface
    {
        $this->request->validate(["email" => "required|email"]);

        $resource = $service->make(
            new Email($this->request->get('email')),
            new Password($this->request->get('password'))
        );

        return $this->response->success($resource);
    }
}
