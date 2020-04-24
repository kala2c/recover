<?php

namespace app\admin\controller;

use think\facade\Request;
use app\common\model\OrderMaster as OrderModel;

class Order extends Base
{
    public function OrderList()
    {
        // 用于模糊查询用户名
        $username = '%' . Request::param('username') . '%';
        // 用于模糊查询手机号
        $phone = '%' . Request::param('phone') . '%';
        //用于模糊查询订单编号
        $orderno = '%' . Request::param('orderno') . '%';
        //用于查询订单状态
        $status = Request::param('status') . '%';
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取所有的订单信息
        $query = OrderModel::pageUtil($pagenum, [['username', 'like', $username], ['phone', 'like', $phone], ['order_no', 'like', $orderno], ['status', 'like', $status]], $pagesize)->select();
        //获取订单总数的数量
        $count = OrderModel::pageInfo()['total'];
        return success([
            'orderlist' => $query,
            'total' => $count
        ]);
    }

    public function DeleteOrder()
    {
        // 要删除的订单的id
        $id = Request::param('id');
        OrderModel::destroy($id);
        return success();
    }
}