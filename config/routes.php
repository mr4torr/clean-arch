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
        // Router::post("/login", "App\Controller\AuthController@login");
        Router::post("/verify", "Auth\Application\Http\VerifyController");
        // Router::post("/reverify", "App\Controller\AuthController@reverify");
        // Router::post("/forgot", "App\Controller\AuthController@forgot");
        // Router::post("/reset", "App\Controller\AuthController@reset");
        // Router::post("/refresh", "App\Controller\AuthController@refresh");
    });
});

Router::get("/favicon.ico", function () {
    return "";
});
