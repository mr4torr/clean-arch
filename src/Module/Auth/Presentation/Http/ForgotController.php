<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Application\UseCase\RecoverPassword;
use Auth\Domain\ValueObject\Email;
use Psr\Http\Message\ResponseInterface;
use Shared\Http\AbstractController;

class ForgotController extends AbstractController
{
    public function __invoke(RecoverPassword $service): ResponseInterface
    {
        $this->request->validate(['email' => 'required|email']);

        $service->make(new Email($this->request->get('email')));

        return $this->response->success('Confira seu email para recuperação de conta');
    }
}
