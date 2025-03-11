<?php

declare(strict_types=1);

use App\Application\Http\RequestFactory;
use App\Application\Http\ResponseFactory;
use App\Application\Exception\Handler\HttpExceptionHandler;
use App\Infrastructure\Support\Hash;
use App\Infrastructure\Support\Token;
use App\Infrastructure\Mailer\MailerService;
// --
use Shared\Exception\AppExceptionInterface;
use Shared\Http\RequestFactoryInterface;
use Shared\Http\ResponseFactoryInterface;
use Shared\Support\HashInterface;
use Shared\Support\TokenInterface;
use Shared\Mailer\MailerInterface;

// ---
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Infrastructure\Dao\UserDao;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Infrastructure\Dao\CredentialDao;
use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Infrastructure\Dao\SessionDao;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    HashInterface::class => Hash::class,
    TokenInterface::class => Token::class,
    MailerInterface::class => MailerService::class,
    RequestFactoryInterface::class => RequestFactory::class,
    ResponseFactoryInterface::class => ResponseFactory::class,
    AppExceptionInterface::class => HttpExceptionHandler::class,
    CredentialDaoInterface::class => CredentialDao::class,
    UserDaoInterface::class => UserDao::class,
    SessionDaoInterface::class => SessionDao::class,
];
