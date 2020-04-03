<?php


namespace app\admin\controller;


use think\Controller;

class User extends Controller
{
    public function info() {
        return success([
            'roles' => ['admin'],
            'introduction' => '我是超级管理',
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            'name' => '超级管理员',
        ]);
    }
}