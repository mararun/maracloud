<?php

namespace MaraOpen;

class BaseClient
{
    protected     $authorization = null;
    protected     $env           = null;
    public static $developDomain = 'http://api-open.test.mararun.com';
    public static $onlineDomain  = 'https://api-open.mararun.com';

    /**
     * MatchClient constructor.
     * @param null $accessKey
     * @param null $env
     */
    public function __construct($authorization, $env = Env::DEVELOP)
    {
        $this->authorization = $authorization;
        $this->env           = $env;
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function getDomain()
    {
        if ($this->env == Env::DEVELOP) {
            return static::$developDomain;
        } else {
            return static::$onlineDomain;
        }
    }

    public function get($url, $data)
    {
        $headers = [
            'Authorization:' . $this->authorization,
        ];

        return Request::get($url, $data, $headers);
    }

    public function post($url, $data)
    {
        $headers = [
            'Authorization:' . $this->authorization,
        ];

        return Request::get($url, $data, $headers);
    }
}
