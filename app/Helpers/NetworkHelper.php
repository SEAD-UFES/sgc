<?php

namespace App\Helpers;

class NetworkHelper
{
    // Adapted from: https://stackoverflow.com/a/2031935
    public static function getClientIpAddress(): string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    return $ip;
                }
            }
        }
        return 'CLI Mode?';
    }
}
