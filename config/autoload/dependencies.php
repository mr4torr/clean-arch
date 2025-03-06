<?php

declare(strict_types=1);

use App\Application\Http\RequestFactory;
use App\Application\Http\ResponseFactory;
use App\Application\Exception\Handler\HttpExceptionHandler;

// --
use Shared\Exception\AppExceptionInterface;
use Shared\Http\RequestFactoryInterface;
use Shared\Http\ResponseFactoryInterface;

// ---
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Infrastructure\Dao\UserDao;
use Auth\Infrastructure\Dao\CredentialDao;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    RequestFactoryInterface::class => RequestFactory::class,
    ResponseFactoryInterface::class => ResponseFactory::class,
    AppExceptionInterface::class => HttpExceptionHandler::class,
    CredentialDaoInterface::class => CredentialDao::class,
    UserDaoInterface::class => UserDao::class,
];
