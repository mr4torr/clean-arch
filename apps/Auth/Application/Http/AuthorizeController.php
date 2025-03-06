<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Shared\Http\AbstractController;

class AuthorizeController extends AbstractController
{
    public function __invoke()
    {
        $user = $this->request->get("user", "Hyperf");
        $method = $this->request->getMethod();

        return [
            "method" => $method,
            "message" => "Hello {$user}.",
        ];
    }
}
