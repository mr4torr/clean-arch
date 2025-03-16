<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Application\UseCase\ResendConfirmation;
use Auth\Domain\ValueObject\Email;
use Psr\Http\Message\ResponseInterface;
use Shared\Http\AbstractController;

class ReverifyController extends AbstractController
{
    public function __invoke(ResendConfirmation $service): ResponseInterface
    {
        $this->request->validate(['email' => 'required|email']);
        $service->make(new Email($this->request->get('email')));

        return $this->response->success('Enviado email com link para ativação');
    }
}
