<?php


namespace app\admin\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use think\Controller;

class Test extends Controller
{
    public function test() {
        throw new ApiException(ErrorCode::OK);
    }
}