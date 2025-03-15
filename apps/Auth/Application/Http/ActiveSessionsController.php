<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\ContainerInterface;
// Shared -
use Shared\Http\AbstractController;
// Domain -
use Auth\Domain\Dao\SessionDaoInterface;

class ActiveSessionsController extends AbstractController
{
    #[Inject]
    private ContainerInterface $cont;

    public function __invoke(SessionDaoInterface $dao): ResponseInterface
    {
        $user = $this->cont->get("user");
        return $this->response->success($dao->all($user["id"]));
    }
}
