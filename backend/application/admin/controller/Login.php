<?php

namespace app\admin\controller;

use Firebase\JWT\JWT;
use think\Controller;
use think\facade\Cache;

class Login extends Controller
{
    public function login() {

        $payload = [
            'uid' => 1
        ];

        $token = JWT::encode($payload, 'recover');
        Cache::set($token, time());
        return success([
            'roles' => ['admin'],
            'introduction' => '我是超级管理',
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            'name' => '超级管理员',
            'token' => $token
        ]);
    }

    public function logout() {
        return success();
    }
}