<?php


namespace app\web\controller;


class User extends Base
{
    public function info()
    {
        return success($this->user_info);
    }
}