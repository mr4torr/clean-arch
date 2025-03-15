<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
// Shared -
use Shared\Http\AbstractController;
use Shared\Http\Enums\SuccessCodeEnum;
// Domain -
use Auth\Domain\SignUp;
use Auth\Domain\Dto\SignUpDto;

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
