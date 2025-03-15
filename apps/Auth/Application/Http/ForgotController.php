<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
// Shared -
use Shared\Http\AbstractController;
// Domain -
use Auth\Domain\Forgot;
use Auth\Domain\ValueObject\Email;

class ForgotController extends AbstractController
{
    public function __invoke(Forgot $service): ResponseInterface
    {
        $this->request->validate(["email" => "required|email"]);

        $service->make(new Email($this->request->get("email")));

        return $this->response->success("Confira seu email para recuperação de conta");
    }
}
