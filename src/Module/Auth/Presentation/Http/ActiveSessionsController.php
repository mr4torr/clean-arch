<?php

declare(strict_types=1);

namespace Auth\Presentation\Http;

use Auth\Domain\Dao\SessionDaoInterface;
use Psr\Http\Message\ResponseInterface;
use Shared\Context\AuthContext;
use Shared\Http\AbstractController;

class ActiveSessionsController extends AbstractController
{
    public function __invoke(SessionDaoInterface $dao, AuthContext $context): ResponseInterface
    {
        return $this->response->success($dao->all($context->getUserId()));
    }
}
