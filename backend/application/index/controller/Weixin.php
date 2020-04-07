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

    public function oauth()
    {
//        获取参数并验证
        $param = $this->request->get();
        $validate = Validate::make([
            'code' => 'require',
            'state' => 'require'
        ], [
            'code.require' => 'code不可缺少',
            'state.require' => 'state不可缺少',
//            'state.eq' => 'state错误'
        ]);
        if (!$validate->check($param)) {
            throw new ValidateException($validate->getError());
        }
//        通过code换取access_token
        $appid = config('secret.wx.appID');
        $appsecret = config('secret.wx.appsecret');
        $code = $param['code'];
        $response1 = Requests::get('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code');
        $response1 = (array)json_decode($response1->body);
        if (array_key_exists('errcode', $response1)) {
            throw new ApiException(ErrorCode::WX_OAUTH_FAILED);
        }
        $access_token = $response1['access_token'];
        $openid = $response1['openid'];
//        Cache::set('wx_access_token.', time());
//        Cache::set('wx_refresh_token.', time());
//        拉取用户信息
        $response2 = Requests::get('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN');
        $response2 = (array)json_decode($response2->body);
        if (array_key_exists('errcode', $response2)) {
            throw new ApiException(ErrorCode::WX_GET_INFO_FAILED);
        }
        $user = User::get(['openid' => $openid]);
        if (!$user) {
            User::addUser(['openid' => $openid]);
        }
        $token = JWT::encode($response2, 'recover');
        Cache::set($token, time());
        $url = $param['state'].'?token='.$token;
        return '<script>window.open("'.$url.'", "_self")</script>';
    }
}
