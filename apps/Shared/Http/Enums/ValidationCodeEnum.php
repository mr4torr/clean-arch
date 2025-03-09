<?php

declare(strict_types=1);

namespace Shared\Http\Enums;

use JsonSerializable;

enum ValidationCodeEnum: int implements JsonSerializable
{
    // COMMON 20XX
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

    // CONTEXT 21XX
    case CONTEXT_UNAUTHORIZED = 2110;
    case CONTEXT_UNBOUND = 2111;
    case CONTEXT_AUTHORIZED_ADMIN = 2112;
    case INVITATION_NOT_BELONG_TO_USER = 2113;
    case INVITATION_USER_IS_ALREADY_TEAM = 2114;

    public function get(): string
    {
        return match ($this) {
            self::DUPLICATED => "validations.duplicated",
            self::NOT_VERIFIED => "Usuário não ativado, verifique seu e-mail.",
            self::NOT_FOUND => "validations.not_found",
            self::EMPTY => "validations.empty",
            self::PASSWORDS_NOT_MATCH => "validations.passwords_not_match",
            self::NOT_EMPTY => "validations.not_empty",
            self::TOKEN_INVALID => "validations.token_invalid",
            self::CHECK_EMAIL_FOR_RESET => "Enviamos recentemente instruções para esse e-mail.",
            self::DIFFERENT_VALUE => "validations.different_value",
            self::LOGIN_INVALID => "Email ou senha inválida",
            self::CONTEXT_UNBOUND => "Contexto não esta vínculado a esse usuário",
            self::CONTEXT_AUTHORIZED_ADMIN => "Apenas administradores e proprietários podem convidar usuários",
            self::INVITATION_NOT_BELONG_TO_USER => "O convite não pertende a esse usuário",
            self::INVITATION_USER_IS_ALREADY_TEAM => "O usuário já faz parte da equipe",
            self::TOKEN_EXPIRED => "validations.token_expired",
        };
    }

    public function jsonSerialize(): mixed
    {
        return [
            $this->get(),
            // $this->value,
            // [ "code" => $this->value ]
            // 'code' => $this->value,
            // 'message' => $this->get(),
        ];
    }
}
