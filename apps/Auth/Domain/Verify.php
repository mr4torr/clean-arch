<?php

declare(strict_types=1);

namespace Auth\Domain;

// use Psr\EventDispatcher\EventDispatcherInterface;
// use Shared\Support\HashInterface;
use Shared\Support\TokenInterface;
// use Shared\Exception\FieldException;
// use Shared\Http\Enums\ErrorCodeEnum;
// use Shared\Http\Enums\ValidationCodeEnum;
// use Shared\Exception\BusinessException;
use Auth\Domain\Entity\User;
// use Auth\Domain\Dto\SignUpDto;
// use Auth\Domain\Entity\Credential;
use Auth\Domain\Dao\UserDaoInterface;
use Shared\Exception\BusinessException;
use Shared\Exception\FieldException;
use Shared\Http\Enums\ErrorCodeEnum;
use Shared\Http\Enums\ValidationCodeEnum;

// use Auth\Domain\Dao\CredentialDaoInterface;
// use Auth\Domain\Event\SendConfirmationEmailEvent;

class Verify
{
    public function __construct(
        private TokenInterface $token,
        private UserDaoInterface $userDao,
        // private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function make(string $token): User
    {
        $resource = $this->token->decode($token);


        $user = $this->userDao->find($resource['id']);

        // $user = GlobalUser::find($id);

        if (!$user) {
            throw new BusinessException(ErrorCodeEnum::NOT_FOUND);
        }

        if ($user->getEmailVerifiedAt() !== null) {
            throw new FieldException(['token' => ValidationCodeEnum::NOT_VERIFIED]);
        }

        $this->userDao->verified($user);

        // $this->eventDispatcher->dispatch(
        //     SendVerifyEmailEvent::make($this->token, $user)
        // );

        return $user;
    }
}
