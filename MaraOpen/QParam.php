<?php

namespace MaraOpen;

class QParam
{
    public function toArr()
    {
        $arr = get_object_vars($this);
        unset($arr["attr"]);

        return $arr;
    }

    public function toJson()
    {
        $arr = get_object_vars($this);
        $arr = array_filter($arr, function ($k, $v) {
            return !empty($v);
        });

        return json_encode($arr);
    }

    public function procLimit()
    {
        if (is_null($this->limit)) {
            $this->limit = '0,20';
        }
    }
}
