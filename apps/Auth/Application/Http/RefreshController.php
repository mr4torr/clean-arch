<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Auth\Domain\Refresh;
use Shared\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class RefreshController extends AbstractController
{
    public function __invoke(Refresh $service): ResponseInterface
    {
        $this->request->validate(["token" => "required"]);

        return $this->response->success(
            $service->make($this->request->get('token'))
        );
    }
}
