<?php

declare(strict_types=1);

namespace Auth\Application\Http;

use Google\Api\JwtLocation;
use Shared\Http\AbstractController;

class AuthenticateController extends AbstractController
{
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = "JWT_SECRET_KEY";
    }

    public function __invoke()
    {
        $email = $this->request->get("email");
        $password = $this->request->get("password");

        // $token = JwtLocation::encode($tokenPayload, $this->jwtSecretKey, "HS256");
        return [
                // "method" => $method,
                // "message" => "Hello {$user}.",
            ];
    }
}
