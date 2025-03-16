<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addServer('http', function (): void {
    Router::get('/health', fn () => 'OK');

    // Endpoint utilizado pelo nginx para validar o token JWT do usuário
    Router::get('/auth/authenticate', 'Auth\Presentation\Http\AuthenticateController');

    Router::addGroup('/auth', function (): void {
        Router::post('/register', 'Auth\Presentation\Http\SignUpController');
        Router::post('/verify', 'Auth\Presentation\Http\VerifyController');
        Router::post('/reverify', 'Auth\Presentation\Http\ReverifyController');
        Router::post('/forgot', 'Auth\Presentation\Http\ForgotController');
        Router::post('/login', 'Auth\Presentation\Http\SignInController');
        Router::post('/reset', 'Auth\Presentation\Http\ResetController');
        Router::post('/refresh', 'Auth\Presentation\Http\RefreshController');
    });

    Router::get('/sessions', 'Auth\Presentation\Http\ActiveSessionsController');

    Router::addGroup('/user', function (): void {
        Router::get('/me', 'User\Presentation\Http\MeController');
    });
});

Router::get('/favicon.ico', fn () => '');
