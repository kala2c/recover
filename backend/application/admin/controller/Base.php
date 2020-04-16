<?php


namespace app\admin\controller;


use app\common\BaseController;
use app\common\model\OrderMaster as OrderModel;
use app\common\model\Waste as WasteModel;
use app\common\model\User as UserModel;
use app\common\model\Pickman as PickmanModel;

class Base extends BaseController
{
    //后台首页数据聚合
    public function dashboard()
    {
        //废品种类总数
        $wastecount = WasteModel::count();
        //用户总数
        $allusercount = UserModel::count();
        //今日新增用户数
        $todayusercount = UserModel::whereTime('create_time','today')->count();

        //配送员总数
        $pickmancount = PickmanModel::where('status', '=', 1)->count();
        //总成交订单数
        $allordercount = OrderModel::where('status','=',OrderModel::STATUS_SUCCESS)->count();
        //今日成交订单数
        $todayordercount = OrderModel::where('status','=',OrderModel::STATUS_SUCCESS)->whereTime('create_time','today')->count();
        //今日下单数
//        $todayordercount = OrderModel::where('','=',OrderModel::STATUS_SUCCESS)->count();
        //今日成交额
//        $todayordersum = OrderModel::where('status','=',OrderModel::STATUS_SUCCESS)->whereTime('create_time','today')->count()
        $result_map = [
            'dashboarddata' => [
                'wastecount' => $wastecount,
                'allusercount' => $allusercount,
                'pickmancount' => $pickmancount,
                'todayusercount' => $todayusercount,
                'allordercount'=>$allordercount,
                'todayordercount'=>$todayordercount
            ]
        ];
        return success($result_map);
    }
}