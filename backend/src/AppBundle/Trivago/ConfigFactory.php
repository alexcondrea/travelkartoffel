<?php

namespace AppBundle\Trivago;

use Trivago\Tas\Config;
use Trivago\Tas\HttpHandler\Curl;

/**
 * Class ClientFactory
 *
 * @package AppBundle\Trivago
 */
class ConfigFactory
{

    /**
     * @param string $access
     * @param string $secret
     *
     * @return Config
     */
    public static function createConfig($access, $secret)
    {
        return new Config([
            Config::BASE_URL => 'https://api.trivago.com/webservice/tas',
            Config::ACCESS_ID => $access,
            Config::SECRET_KEY => $secret,
            Config::HTTP_HANDLER => new Curl(),
            Config::GET_TRACKING_ID_CALLBACK => function () {
                return isset($_COOKIE['trv_tid']) ? $_COOKIE['trv_tid'] : null;
            },
            Config::STORE_TRACKING_ID_CALLBACK => function ($trackingId) {
                // keep the Tracking-Id as long as possible
                setcookie('trv_tid', $trackingId, 2147483647, '/', null, null, true);
            }
        ]);

    }
}