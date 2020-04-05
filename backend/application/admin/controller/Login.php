<?php

namespace app\admin\controller;


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
        $query = DB::table('admin')->where("username='$username' and password='$password'")->find();
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
                'name' => '超级管理员',
                'token' => $token
            ]);
        } else {
            throw new ApiException(ErrorCode::GET_USER_INFO_EMPTY);
        }


    }

    public function logout()
    {
        return success();
    }
}