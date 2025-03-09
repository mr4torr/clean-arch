<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Auth\Domain\Verify;
use Psr\Http\Message\ResponseInterface;
use Shared\Http\AbstractController;

class VerifyController extends AbstractController
{
    public function __invoke(Verify $service): ResponseInterface
    {
        $this->request->validate([ "token" => "required" ]);
        $service->make($this->request->get('token'));
        return $this->response->success("Usuário criado com sucesso");
    }
}
