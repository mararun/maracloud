<?php

namespace MaraOpen;

class Request
{
    public static function get($url, $param, $headers = [])
    {
        $query = http_build_query($param);
        if (strlen($query) > 0) {
            $query = "?" . $query;
        }

        $url = $url . $query;
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $body = curl_exec($ch);
        $ret  = curl_getinfo($ch);
        curl_close($ch);
        $ret['body'] = $body;

        return static::parse($ret);
    }

    public static function post($url, $data, $headers)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $body = curl_exec($ch);
        $ret  = curl_getinfo($ch);
        curl_close($ch);
        $ret['body'] = $body;

        return static::parse($ret);
    }

    protected static function parse($response)
    {
        if (($data = static::ok($response)) !== null) {
            return $data;
        } else if (($error = static::fail($response)) !== null) {
            throw new HttpRuntimeException($error['message']);
        } else {
            throw new HttpRuntimeException('Connect server failed');
        }
    }

    public static function ok($response)
    {
        if ($response['http_code'] == 201 || $response['http_code'] == 200) {
            return json_decode($response['body'], true);
        }

        return null;
    }

    public static function fail($response)
    {
        $data = json_decode($response['body'], true);
        if (isset($data['error'])) {
            return $data['error'];
        }

        return null;
    }

}