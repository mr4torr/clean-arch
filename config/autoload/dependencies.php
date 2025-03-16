<?php

declare(strict_types=1);

use Auth\Application\Dispatcher\SendEmail;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\SessionDaoInterface;
use Auth\Domain\Dao\UserDaoInterface as AuthUserDaoInterface;
use Auth\Domain\Email\SendEmailInterface;
use Auth\Infrastructure\Dao\CredentialDao;
use Auth\Infrastructure\Dao\SessionDao;
use Auth\Infrastructure\Dao\UserDao as AuthUserDao;
use Core\Application\Exception\Handler\HttpExceptionHandler;
use Core\Application\Http\RequestFactory;
use Core\Application\Http\ResponseFactory;
// --
use Core\Domain\Jwt\TokenInterface;
use Core\Domain\Mailer\MailerBuilder;
use Core\Domain\Mailer\MailerBuilderInterface;
// ---
use Core\Domain\Mailer\MailerServiceInterface;
use Core\Domain\Support\IpAddressInterface;
use Core\Infrastructure\Jwt\Token;
use Core\Infrastructure\Mailer\MailerService;
use Core\Infrastructure\Support\IpAddress;
use Shared\Exception\AppExceptionInterface;
use Shared\Http\RequestFactoryInterface;
use Shared\Http\ResponseFactoryInterface;
// ---
use User\Domain\Dao\UserDaoInterface;
use User\Infrastructure\Dao\UserDao;

/*
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    TokenInterface::class => Token::class,
    IpAddressInterface::class => IpAddress::class,
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
