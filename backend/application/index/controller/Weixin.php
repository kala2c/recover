<?php
namespace app\index\controller;

use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\model\User;
use Firebase\JWT\JWT;
use think\facade\Cache;
use think\Controller;
use think\exception\ValidateException;
use think\facade\Request;
use think\facade\Validate;
use \Requests;

class Weixin extends Controller
{
    public function check()
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

    public function sdkConf()
    {

    }
}
