<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Auth\Domain\SignUp;
use Auth\Domain\Dto\SignUpDto;
use Psr\Http\Message\ResponseInterface;
use Shared\Http\AbstractController;
use Shared\Http\Enums\SuccessCodeEnum;

class SignUpController extends AbstractController
{
    public function __invoke(SignUp $service): ResponseInterface
    {
        $this->request->validate([
            "name" => "required",
            "email" => "required|email|unique:auth_users,email",
            "password" => "required|min:8|confirmed",
        ]);

        $dto = $this->request->getContentMapped(SignUpDto::class);
        $service->make($dto);

        return $this->response->success("Usuário criado com sucesso", SuccessCodeEnum::CREATED);
    }
}
