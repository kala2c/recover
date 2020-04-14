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
     * @throws DbException
     */
    public function index()
    {
        $area = Area::getList();
        return success($area);
    }
}