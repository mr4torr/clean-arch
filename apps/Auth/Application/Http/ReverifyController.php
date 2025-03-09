<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Auth\Domain\Reverify;
use Auth\Domain\ValueObject\Email;
use Psr\Http\Message\ResponseInterface;
use Shared\Http\AbstractController;

class ReverifyController extends AbstractController
{
    public function __invoke(Reverify $service): ResponseInterface
    {
        $this->request->validate([ "email" => "required|email" ]);
        $service->make(new Email($this->request->get('email')));

        return $this->response->success("Enviado email com link para ativação");
    }
}
