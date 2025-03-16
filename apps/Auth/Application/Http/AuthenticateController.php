<?php

declare(strict_types=1);

namespace Auth\Application\Http;

// Shared -

use App\Domain\Jwt\TokenInterface;
use Auth\Domain\Token\TokenPayload;
use Auth\Infrastructure\Dao\SessionDao;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Exception\BusinessException;
use Shared\Http\AbstractController;
use Shared\Http\Enums\ErrorCodeEnum;

/**
 * Classe responsável por validar o token JWT do usuário via api gateway do nginx
 */
class AuthenticateController extends AbstractController
{
    public function __invoke(
        ServerRequestInterface $request,
        TokenInterface $token,
        SessionDao $session
    ): void {
        $bearerToken = $request->getHeader("Authorization");
        $message = "";
        if ($target = $request->getHeader("X-target")) {
            $message = 'endpoint: ' . current($target);
        }

        if (empty($bearerToken)) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_EMPTY, $message);
        }

        try {
            $decoded = $token->decode(substr($bearerToken[0], 7));
            if (!array_key_exists("resource", $decoded) || $decoded["resource"] !== TokenPayload::RESOURCE_TYPE) {
                throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID, $message);
            }

            if (!array_key_exists("id", $decoded) || !array_key_exists("session_id", $decoded)) {
                throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID, $message);
            }

            if (!$session->exists($decoded["session_id"])) {
                throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID, $message);
            }
        } catch (\Exception $e) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID, $message, $e);
        }
    }
}
