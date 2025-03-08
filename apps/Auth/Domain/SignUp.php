<?php

declare(strict_types=1);

namespace Auth\Domain;

use Psr\EventDispatcher\EventDispatcherInterface;
use Shared\Support\HashInterface;
use Shared\Support\TokenInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;
use Shared\Exception\BusinessException;
use Auth\Domain\Entity\User;
use Auth\Domain\Dto\SignUpDto;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Event\SendConfirmationEmailEvent;

class SignUp
{
    public function __construct(
        private TokenInterface $token,
        private HashInterface $hash,
        private UserDaoInterface $userDao,
        private CredentialDaoInterface $credentialDao,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function make(SignUpDto $input): User
    {
        if ($this->userDao->emailAlreadyExists($input->email)) {
            throw new FieldException(["email" => ValidationCodeEnum::DUPLICATED]);
        }

        try {
            $user = User::new($this->hash, $input->name, $input->email);
            if (!$this->userDao->create($user)) {
                throw new BusinessException(ErrorCodeEnum::INTERNAL_SERVER_ERROR, "auth.error.sign_up_user");
            }
            $credential = Credential::new($this->hash, $user->id, $input->password, $input->provider);
            if (!$this->credentialDao->create($credential)) {
                throw new BusinessException(ErrorCodeEnum::INTERNAL_SERVER_ERROR, "auth.error.sign_up_credential");
            }
        } catch (\Throwable $e) {
            if (isset($user)) $this->userDao->delete($user->id);
            if (isset($credential)) $this->credentialDao->delete($credential->id);

            throw $e;
        }

        $this->eventDispatcher->dispatch(
            SendConfirmationEmailEvent::make($this->token, $user)
        );

        return $user;
    }
}
