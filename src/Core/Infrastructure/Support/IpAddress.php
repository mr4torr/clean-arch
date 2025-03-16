<?php

declare(strict_types=1);

namespace Core\Infrastructure\Support;

use Core\Domain\Support\IpAddressInterface;
use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpServer\Contract\RequestInterface;

class IpAddress implements IpAddressInterface
{
    public function __construct(private ConfigInterface $config, private RequestInterface $request)
    {
    }

    public function get(): string
    {
        $realIP = '';
        $key = __CLASS__;
        if (Context::has($key)) {
            $realIP = Context::get($key);
        }
        if (! empty($realIP)) {
            return $realIP;
        }

        $realIP = $this->request->server('remote_addr', '');

        $config = $this->config->get('ip', []);
        $trustedProxies = $config['proxy'] ?? [];
        if (count($trustedProxies) > 0) {
            $tempIP = $this->request->header('x-forwarded-for');
            $tempIP = trim(explode(',', $tempIP)[0]);

            if (! $this->isValidIP($tempIP)) {
                $tempIP = null;
            }

            if (! empty($tempIP)) {
                $realIPBin = $this->ip2bin($realIP);
                foreach ($trustedProxies as $ip) {
                    $serverIPElements = explode('/', $ip);
                    $serverIP = $serverIPElements[0];
                    $serverIPPrefix = $serverIPElements[1] ?? 128;
                    $serverIPBin = $this->ip2bin($serverIP);

                    if (strlen($realIPBin) !== strlen($serverIPBin)) {
                        continue;
                    }

                    if (strncmp($realIPBin, $serverIPBin, (int) $serverIPPrefix) === 0) {
                        $realIP = $tempIP;
                        break;
                    }
                }
            }
        }

        if (! $this->isValidIP($realIP)) {
            $realIP = '0.0.0.0';
        }

        return Context::set($key, $realIP);
    }

    public function ip2bin(string $ip): string
    {
        if ($this->isValidIP($ip, 'ipv6')) {
            $IPHex = str_split(bin2hex(inet_pton($ip)), 4);
            foreach ($IPHex as $key => $value) {
                $IPHex[$key] = intval($value, 16);
            }
            $IPBin = vsprintf('%016b%016b%016b%016b%016b%016b%016b%016b', $IPHex);
        } else {
            $IPHex = str_split(bin2hex(inet_pton($ip)), 2);
            foreach ($IPHex as $key => $value) {
                $IPHex[$key] = intval($value, 16);
            }
            $IPBin = vsprintf('%08b%08b%08b%08b', $IPHex);
        }

        return $IPBin;
    }

    private function isValidIP(string $ip, string $type = ''): bool
    {
        switch (strtolower($type)) {
            case 'ipv4':
                $flag = FILTER_FLAG_IPV4;
                break;
            case 'ipv6':
                $flag = FILTER_FLAG_IPV6;
                break;
            default:
                $flag = 0;
                break;
        }

        return boolval(filter_var($ip, FILTER_VALIDATE_IP, $flag));
    }
}
