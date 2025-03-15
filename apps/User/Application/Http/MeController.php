<?php

declare(strict_types=1);

namespace User\Application\Http;

use Psr\Http\Message\ResponseInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Di\Annotation\Inject;
// Shared -
use Shared\Http\AbstractController;
// Domain -
use User\Domain\Dao\UserDaoInterface;

class MeController extends AbstractController
{
    #[Inject]
    private ContainerInterface $cont;

    public function __invoke(UserDaoInterface $userDao): ResponseInterface
    {
        $user = $this->cont->get("user");
        $user = $userDao->find($user["id"]);
        return $this->response->success($user);
    }
}
