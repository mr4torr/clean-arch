<?php

declare(strict_types=1);

namespace Auth\Domain;

// Shared -
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;
use Shared\Exception\BusinessException;
// Domain -
use Auth\Domain\Entity\User;
use Auth\Domain\Dto\SignUpDto;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Email\SendEmailInterface;

class SignUp
{
    public function __construct(
        private UserDaoInterface $userDao,
        private CredentialDaoInterface $credentialDao,
        private SendEmailInterface $sendEmail
    ) {}

    public function make(SignUpDto $input): User
    {
        if ($this->userDao->emailAlreadyExists($input->email)) {
            throw new FieldException(["email" => ValidationCodeEnum::DUPLICATED]);
        }

        try {
            $user = $this->userDao->create(new User($input->name, $input->email));
            if (!$user) {
                throw new BusinessException(ErrorCodeEnum::INTERNAL_SERVER_ERROR, "auth.error.sign_up_user");
            }

            $credential = $this->credentialDao->create(
                new Credential(user_id: $user->id, hash: (string) $input->password, provider: $input->provider)
            );
            if (!$credential) {
                throw new BusinessException(ErrorCodeEnum::INTERNAL_SERVER_ERROR, "auth.error.sign_up_credential");
            }
        } catch (\Throwable $e) {
            if (isset($user)) {
                $this->userDao->delete($user->id);
            }
            if (isset($credential)) {
                $this->credentialDao->delete($credential->id);
            }

            throw $e;
        }

        $this->sendEmail->sendConfirmationEmail($user);

        return $user;
    }
}
