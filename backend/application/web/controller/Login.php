<?php


namespace app\web\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\model\User;
use Firebase\JWT\JWT;
use \Requests;
use think\Controller;
use think\exception\ValidateException;
use think\facade\Cache;
use think\facade\Validate;

class Login extends Controller
{
    /**
     * 微信授权登录 demo 待重写
     * @return string
     * @throws ApiException
     */
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
        $appId = config('secret.wx.appId');
        $appSecret = config('secret.wx.appSecret');
        $code = $param['code'];
        $response1 = Requests::get('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appId.'&secret='.$appSecret.'&code='.$code.'&grant_type=authorization_code');
        $response1 = json_decode($response1->body, true);
        if (array_key_exists('errcode', $response1)) {
            throw new ApiException(ErrorCode::WX_OAUTH_FAILED);
        }
        $access_token = $response1['access_token'];
        $openid = $response1['openid'];
//        Cache::set("wx_access_token.$openid", time());
//        Cache::set("wx_refresh_token.', time());
//        拉取用户信息
        $response2 = Requests::get('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN');
        $user_info = json_decode($response2->body, true);
        if (array_key_exists('errcode', $user_info)) {
            throw new ApiException(ErrorCode::WX_GET_INFO_FAILED);
        }
//        查找数据库里是否存在该用户
        $user = User::get(['openid' => $openid]);
        if (!$user) {
            $user = User::addUser([
                'openid' => $openid,
                'username' => $user_info['nickname'],
                'score' => 0,
                'status' => User::STATUS_NORMAL
            ]);
        }
        if ($user->status != User::STATUS_NORMAL) {
            throw new ApiException(ErrorCode::USER_LOCKED);
        }
        $uid = $user->id;
        $nickname = $user_info['nickname'];
        $avatar = $user_info['headimgurl'];
        $old_nickname = Cache::get("name.$uid");
        $old_avatar = Cache::get("avatar.$uid");
        if (
            (!$old_avatar || !$old_nickname) || // 没有缓存头像和昵称
            ($old_nickname != $nickname || $old_avatar != $avatar) // 缓存了 但是信息已更新
        ) {
            // 缓存头像和昵称
            Cache::set("name.$uid", $nickname);
            Cache::set("avatar.$uid", $avatar);
        }
        $payload = [
            'uid' => $user->id,
            'openid' => $openid
        ];
        $token = JWT::encode($payload, 'recover');
        Cache::set($token, time());
        $url = $param['state'].'?token='.$token;
        $this->redirect($url);
        return '<script>window.open("'.$url.'", "_self")</script>';
    }
}