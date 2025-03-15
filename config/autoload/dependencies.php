<?php

declare(strict_types=1);

use App\Application\Http\RequestFactory;
use App\Application\Http\ResponseFactory;
use App\Application\Exception\Handler\HttpExceptionHandler;
use App\Domain\Mailer\MailerBuilder;
use App\Domain\Mailer\MailerBuilderInterface;
use App\Domain\Mailer\MailerServiceInterface;
use App\Infrastructure\Mailer\MailerService;
// --
use Shared\Exception\AppExceptionInterface;
use Shared\Http\RequestFactoryInterface;
use Shared\Http\ResponseFactoryInterface;

// ---
use Auth\Infrastructure\Dao\UserDao as AuthUserDao;
use Auth\Application\Dispatcher\SendEmail;
use Auth\Domain\Email\SendEmailInterface;
use Auth\Infrastructure\Dao\SessionDao;
use Auth\Infrastructure\Dao\CredentialDao;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Domain\Dao\UserDaoInterface as AuthUserDaoInterface;
// ---
use User\Domain\Dao\UserDaoInterface;
use User\Infrastructure\Dao\UserDao;

use App\Domain\Jwt\TokenInterface;
use App\Infrastructure\Jwt\Token;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    TokenInterface::class => Token::class,
    RequestFactoryInterface::class => RequestFactory::class,
    ResponseFactoryInterface::class => ResponseFactory::class,
    AppExceptionInterface::class => HttpExceptionHandler::class,
    CredentialDaoInterface::class => CredentialDao::class,
    AuthUserDaoInterface::class => AuthUserDao::class,
    UserDaoInterface::class => UserDao::class,
    SessionDaoInterface::class => SessionDao::class,
    SendEmailInterface::class => SendEmail::class,
    MailerBuilderInterface::class => MailerBuilder::class,
    MailerServiceInterface::class => MailerService::class,
];
