<?php


namespace app\web\controller;


use app\common\model\Area;
use app\common\model\Pickman;
use app\web\util\Wx;
use think\Controller;
use think\exception\DbException;

class Test extends Controller
{
    /**
     * 测试接口
     */
    public function index()
    {
        $a2t = Area::getList(1);
//        dump($a2t);
//        return $a2t;
    }
}