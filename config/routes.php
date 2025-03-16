<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addServer("http", function () {
    Router::get("/health", fn() => "OK");

    // Endpoint utilizado pelo nginx para validar o token JWT do usuário
    Router::get("/auth/authenticate", "Auth\Application\Http\AuthenticateController");

    Router::addGroup("/auth", function (): void {
        Router::post("/register", "Auth\Application\Http\SignUpController");
        Router::post("/verify", "Auth\Application\Http\VerifyController");
        Router::post("/reverify", "Auth\Application\Http\ReverifyController");
        Router::post("/forgot", "Auth\Application\Http\ForgotController");
        Router::post("/login", "Auth\Application\Http\SignInController");
        Router::post("/reset", "Auth\Application\Http\ResetController");
        Router::post("/refresh", "Auth\Application\Http\RefreshController");
    });

    Router::get("/sessions", "Auth\Application\Http\ActiveSessionsController");

    Router::addGroup("/user", function (): void {
        Router::get("/me", "User\Application\Http\MeController");
    });
});

Router::get("/favicon.ico", function () {
    return "";
});
