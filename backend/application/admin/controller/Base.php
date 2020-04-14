<?php


namespace app\admin\controller;


use app\common\BaseController;
use app\common\model\OrderMaster as OrderModel;
use app\common\model\Waste as WasteModel;
use app\common\model\User as UserModel;

class Base extends BaseController
{
    //后台首页数据聚合
    public function dashboard()
    {
        //废品种类总数
        $wastecount = WasteModel::count();
        //用户总数
        $allusercount = UserModel::count();
        //今天新增用户数
        //$todayusercount = UserModel::where('createtime','=',tody)->count();

        //配送员总数

        //总成交订单数
//        $allordercount = OrderModel::where('status','=',OrderModel::STATUS_SUCCESS)->count();
        //今日成交订单数
//        $todayordercount = OrderModel::where('status','=',OrderModel::STATUS_SUCCESS)->count();
        //今日下单数
//        $todayordercount = OrderModel::where('','=',OrderModel::STATUS_SUCCESS)->count();
        //今日成交额
        $result_map = [
            'dashboarddata' => [
                'wastecount' => $wastecount,
                'allusercount' => $allusercount
            ]
        ];
        return success($result_map);
    }
}