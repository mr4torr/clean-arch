<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Application\UseCase\RegisterUser;
use Auth\Domain\Dto\Input\SignUpDto;
use Psr\Http\Message\ResponseInterface;
use Shared\Http\AbstractController;
use Shared\Http\Enums\SuccessCodeEnum;

class SignUpController extends AbstractController
{
    public function __invoke(RegisterUser $service): ResponseInterface
    {
        $this->request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:auth_users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $service->make(
            $this->request->getContentMapped(SignUpDto::class)
        );

        return $this->response->success('Usuário criado com sucesso', SuccessCodeEnum::CREATED);
    }
}
