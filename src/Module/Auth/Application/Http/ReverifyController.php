<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
// Shared -
use Shared\Http\AbstractController;
// Domain -
use Auth\Domain\Reverify;
use Auth\Domain\ValueObject\Email;

class ReverifyController extends AbstractController
{
    public function __invoke(Reverify $service): ResponseInterface
    {
        $this->request->validate(["email" => "required|email"]);
        $service->make(new Email($this->request->get("email")));

        return $this->response->success("Enviado email com link para ativação");
    }
}
