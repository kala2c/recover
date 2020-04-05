<?php

namespace app\admin\controller;


use Firebase\JWT\JWT;
use think\Controller;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use app\common\exception\ApiException;
use app\common\error\ErrorCode;


class Waste extends Controller
{


    public function getWasteList()
    {
        //用于模糊查询
        $keyword = Request::param('keyword', '%');
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取所有的废品信息(这分页我也没测试对不对)
        $query = DB::table('waste')->where('name', 'like', $keyword)->limit($pagesize * ($pagenum - 1), $pagenum)->select();
        //获取废品种类的数量
        $count = Db::table('waste')->count();
        return success([
            'wastelist' => $query,
            'total' => $count
        ]);
    }
}