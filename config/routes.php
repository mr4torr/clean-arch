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

// Router::addRoute(["GET", "POST", "HEAD"], "/", "Auth\SignUp\Application\Http\IndexController");

Router::addServer("http", function () {
    // Router::get('/', 'App\Controller\IndexController@index');
    Router::get("/", fn() => "Welcome");
    Router::get("/health", fn() => "OK");

    Router::addGroup("/auth", function () {
        Router::post("/register", "Auth\Application\Http\SignUpController");
        Router::post("/verify", "Auth\Application\Http\VerifyController");
        Router::post("/reverify", "Auth\Application\Http\ReverifyController");
        Router::post("/forgot", "Auth\Application\Http\ForgotController");
        Router::post("/login", "Auth\Application\Http\SignInController");
        Router::post("/reset", "Auth\Application\Http\ResetController");
        Router::post("/refresh", "Auth\Application\Http\RefreshController");
    });
});

Router::get("/favicon.ico", function () {
    return "";
});
