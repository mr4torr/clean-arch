<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Auth\Domain\SignUp;
use Auth\Domain\Dto\SignUpDto;
use Shared\Http\AbstractController;
use Shared\Exception\FieldException;
use Shared\Http\Enums\SuccessCodeEnum;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class SignUpController extends AbstractController
{
    public function __invoke(SignUp $service, ValidatorFactoryInterface $validation)
    {
        $validator = $validation->make($this->request->all(), [
            "name" => "required",
            // "email" => "required|email|unique:users,email",
            "email" => "required|email",
            "password" => "required|min:8",
        ]);

        if ($validator->fails()) {
            throw new FieldException($validator->errors()->getMessages());
        }

        $service->make($this->request->getContentMapped(SignUpDto::class));

        return $this->response->success("Usuário criado com sucesso", SuccessCodeEnum::CREATED);
    }
}
