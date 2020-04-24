<?php

namespace app\admin\controller;


use app\admin\model\Administrator;
use Firebase\JWT\JWT;
use think\Controller;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use app\common\exception\ApiException;
use app\common\error\ErrorCode;



class Login extends Controller
{
    public function login()
    {
        $username = Request::param('username');
        $password = Request::param('password');
        $query = Administrator::where("username='$username' and password='$password'")->find();
        if ($query) {
            $payload = [
                'uid' => $query['id']
            ];
            $token = JWT::encode($payload, 'recover');
            Cache::set($token, time());
            return success([
                'roles' => ['admin'],
                'introduction' => $query['username'],
                'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
                'name' => $query['note'],
                'token' => $token
            ]);
        } else {
            throw new ApiException(ErrorCode::ACCOUNT_PASSWORD_ERROR);
        }


    }

    public function logout()
    {
        return success();
    }
}