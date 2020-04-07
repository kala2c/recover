<?php


namespace app\web\util;

class Wx
{

    static public function test()
    {
        $appID = config('secret.wx.appID');
        $appsecret = config('secret.wx.appsecret');

        dump($appID);
        dump($appsecret);
    }
}