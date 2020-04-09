<?php


namespace app\web\controller;


class User extends Base
{
    /**
     * 获取用户信息
     * @return mixed
     */
    public function info()
    {
        return success($this->user_info);
    }
}