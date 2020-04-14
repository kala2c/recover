<?php


namespace app\index\controller;


use app\common\model\Area;
use app\common\model\User;
use think\Controller;
use think\exception\DbException;

class Test extends Controller
{
    public function test() {
        return success(array_merge($this->request->get(), $this->request->post()));
    }
}