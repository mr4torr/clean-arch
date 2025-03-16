<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
// Shared -
use Shared\Context\AuthContext;
use Shared\Http\AbstractController;
// Domain -
use Auth\Domain\Dao\SessionDaoInterface;

class ActiveSessionsController extends AbstractController
{
    public function __invoke(SessionDaoInterface $dao, AuthContext $context): ResponseInterface
    {
        return $this->response->success($dao->all($context->getUserId()));
    }
}
