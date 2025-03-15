<?php

declare(strict_types=1);

namespace Auth\Application\Middleware;

use Hyperf\Di\Container;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
// Core
use App\Domain\Jwt\TokenInterface;
// Shared
use Shared\Exception\BusinessException;
use Shared\Http\Enums\ErrorCodeEnum;
// Domain
use Auth\Domain\Token\TokenPayload;

class AuthMiddleware
{
    public function __construct(private TokenInterface $token, private Container $container) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $token = $request->getHeader("Authorization");
        if (empty($token)) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_EMPTY);
        }

        try {
            $decoded = $this->token->decode(substr($token[0], 7));

            if (!array_key_exists("resource", $decoded) || $decoded["resource"] !== TokenPayload::RESOURCE_TYPE) {
                throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
            }

            if (!array_key_exists("id", $decoded) || !array_key_exists("session_id", $decoded)) {
                throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID);
            }

            // $cache = $this->container->get(\Psr\SimpleCache\CacheInterface::class);

            // $key = 'user-' . $decoded['id'];
            // // $cache->delete($key);
            // if (!$cache->has($key)) {
            //     // $user = GlobalUser::with('contexts')->find($decoded->id);
            //     // $user->roles = $user->contexts
            //     //     ->keyBy('id')
            //     //     ->map(fn ($res) => $res->pivot->role)
            //     //     ->toArray();

            //     // $collection = $user->toArray();
            //     // unset($collection['contexts']);

            //     // $cache->set(
            //     //     $key,
            //     //     $collection,
            //     //     Carbon::now()->diffInSeconds(Carbon::parse($decoded->exp))
            //     // );
            // } else {
            //     $collection = $cache->get($key);
            //     // $user = new User::instantiate($collection);
            //     // $user->roles = $collection['roles'];
            // }

            // $this->container->set('global_user', $user);
            $this->container->set("user", $decoded);
        } catch (\Exception $e) {
            throw new BusinessException(ErrorCodeEnum::AUTH_JWT_KEY_INVALID, previous: $e);
        }

        return $handler->handle($request);
    }
}
