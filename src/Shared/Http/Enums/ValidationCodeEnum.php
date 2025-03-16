<?php

declare(strict_types=1);

namespace Shared\Http\Enums;

use Shared\Http\Objects\ResponseCode;

enum ValidationCodeEnum: int implements CodeEnumInterface
{
    // COMMON 20XX
    case VERIFIED = 2000;
    case NOT_VERIFIED = 2001;
    case DUPLICATED = 2002;
    case NOT_FOUND = 2004;
    case EMPTY = 2005;
    case NOT_EMPTY = 2006;
    case CHECK_EMAIL_FOR_RESET = 2007;
    case DIFFERENT_VALUE = 2008;
    case LOGIN_INVALID = 2009;
    case TOKEN_INVALID = 2010;
    case TOKEN_EXPIRED = 2011;
    case PASSWORDS_NOT_MATCH = 2012;
    case LOGIN_BLOCKED = 2013;
    case EMAIL_INVALID = 2014;

    // CONTEXT 21XX
    case CONTEXT_UNAUTHORIZED = 2110;
    case CONTEXT_UNBOUND = 2111;
    case CONTEXT_AUTHORIZED_ADMIN = 2112;
    case INVITATION_NOT_BELONG_TO_USER = 2113;
    case INVITATION_USER_IS_ALREADY_TEAM = 2114;

    public function get(): ResponseCode
    {
        return match ($this) {
            self::DUPLICATED => ResponseCode::make('validations.duplicated', StatusCodeEnum::FORBIDDEN),
            self::VERIFIED => ResponseCode::make(
                'Usuário já ativado, caso não lembre sua senha, acesse o link de recuperação.',
                StatusCodeEnum::FORBIDDEN
            ),
            self::NOT_VERIFIED => ResponseCode::make(
                'Usuário não ativado, verifique seu e-mail.',
                StatusCodeEnum::FORBIDDEN
            ),
            self::NOT_FOUND => ResponseCode::make('validations.not_found', StatusCodeEnum::NOT_FOUND),
            self::LOGIN_BLOCKED => ResponseCode::make('validations.user_blocked', StatusCodeEnum::FORBIDDEN),
            self::EMPTY => ResponseCode::make('validations.empty', StatusCodeEnum::FORBIDDEN),
            self::PASSWORDS_NOT_MATCH => ResponseCode::make(
                'validations.passwords_not_match',
                StatusCodeEnum::FORBIDDEN
            ),
            self::NOT_EMPTY => ResponseCode::make('validations.not_empty', StatusCodeEnum::FORBIDDEN),
            self::TOKEN_INVALID => ResponseCode::make('validations.token_invalid', StatusCodeEnum::FORBIDDEN),
            self::CHECK_EMAIL_FOR_RESET => ResponseCode::make(
                'Enviamos recentemente instruções para esse e-mail.',
                StatusCodeEnum::FORBIDDEN
            ),
            self::DIFFERENT_VALUE => ResponseCode::make('validations.different_value', StatusCodeEnum::FORBIDDEN),
            self::LOGIN_INVALID => ResponseCode::make('Email ou senha inválida', StatusCodeEnum::FORBIDDEN),
            self::CONTEXT_UNBOUND => ResponseCode::make(
                'Contexto não esta vínculado a esse usuário',
                StatusCodeEnum::FORBIDDEN
            ),
            self::CONTEXT_AUTHORIZED_ADMIN => ResponseCode::make(
                'Apenas administradores e proprietários podem convidar usuários',
                StatusCodeEnum::FORBIDDEN
            ),
            self::INVITATION_NOT_BELONG_TO_USER => ResponseCode::make(
                'O convite não pertende a esse usuário',
                StatusCodeEnum::FORBIDDEN
            ),
            self::INVITATION_USER_IS_ALREADY_TEAM => ResponseCode::make(
                'O usuário já faz parte da equipe',
                StatusCodeEnum::FORBIDDEN
            ),
            self::TOKEN_EXPIRED => ResponseCode::make('validations.token_expired', StatusCodeEnum::FORBIDDEN),
            self::EMAIL_INVALID => ResponseCode::make('validations.email_invalid', StatusCodeEnum::FORBIDDEN),
        };
    }
}
