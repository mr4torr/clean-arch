<?php

declare(strict_types=1);

namespace User\Presentation\Http;

use Psr\Http\Message\ResponseInterface;
use Shared\Context\AuthContext;
use Shared\Http\AbstractController;
use User\Domain\Dao\UserDaoInterface;

class MeController extends AbstractController
{
    public function __invoke(UserDaoInterface $userDao, AuthContext $context): ResponseInterface
    {
        $user = $userDao->find($context->getUserId());
        return $this->response->success($user);
    }
}
