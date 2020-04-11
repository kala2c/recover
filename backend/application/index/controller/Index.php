<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Request;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function wxCheck()
    {
        $signature = Request::param('signature');
        $timestamp = Request::param('timestamp');
        $nonce = Request::param('nonce');
        $echostr = Request::param('echostr');
        $token = 'recover';
        //校验微信服务器的接入请求
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if ($tmpStr == $signature){
            return $echostr;
        }
        return '';
    }
}
