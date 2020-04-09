<?php

namespace app\admin\controller;

use think\facade\Request;
use app\common\model\OrderMaster as OrderModel;

class Order extends Base
{
    public function OrderList()
    {
        // 用于模糊查询微信名称
        $username = Request::param('username') . '%';
        // 用于模糊查询手机号
        $phone = Request::param('phone') . '%';
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取所有的订单信息
        $query = OrderModel::pageUtil($pagenum, [['username', 'like', $username], ['phone', 'like', $phone]], $pagesize)->select();
        //获取订单总数的数量
        $count = OrderModel::pageInfo()['total'];
        return success([
            'orderlist' => $query,
            'total' => $count
        ]);
    }
}