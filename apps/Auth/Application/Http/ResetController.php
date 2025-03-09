<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Auth\Domain\Reset;
use Auth\Domain\ValueObject\Password;
use Shared\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class ResetController extends AbstractController
{
    public function __invoke(Reset $service): ResponseInterface
    {
        $this->request->validate([
            "token" => "required",
            "password" => "required|min:8|confirmed",
        ]);

        $service->make(
            $this->request->get('token'),
            new Password($this->request->get('password'))
        );

        return $this->response->success('Senha alterada com sucesso');
    }
}
