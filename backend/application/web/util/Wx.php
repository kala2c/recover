<?php


namespace app\web\util;

class Wx
{

    static public function test()
    {
        $appId = config('secret.wx.appId');
        $appSecret = config('secret.wx.appSecret');

        dump($appId);
        dump($appSecret);
    }
}