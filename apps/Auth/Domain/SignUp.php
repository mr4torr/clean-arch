<?php

declare(strict_types=1);

namespace Auth\Domain;

use Psr\EventDispatcher\EventDispatcherInterface;
use Auth\Domain\Dto\SignUpDto;
use Auth\Domain\Entity\User;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Event\SendConfirmationEmailEvent;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\UserDaoInterface;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ValidationCodeEnum;

class SignUp
{
    public function __construct(
        private UserDaoInterface $userDao,
        private CredentialDaoInterface $credentialDao,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function make(SignUpDto $input): User
    {
        if ($this->userDao->emailAlreadyExists($input->email)) {
            throw new FieldException(["email" => ValidationCodeEnum::DUPLICATED]);
        }

        $user = User::new($input->name, $input->email);
        $this->userDao->create($user);

        $credential = Credential::new($user->id, $input->password, $input->provider);
        $this->credentialDao->create($credential);

        $this->eventDispatcher->dispatch(new SendConfirmationEmailEvent($user));

        return $user;
    }
}
