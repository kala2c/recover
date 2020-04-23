<?php

namespace app\admin\controller;

use app\common\exception\DataException;
use app\common\model\Area as AreaModel;
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
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');

        $area = AreaModel::field('id')
                        ->where('admin_id', $this->user_info['uid'])
                        ->select();
        $area_list = [];
        foreach ($area as $value) {
            array_push($area_list, $value['id']);
        }
        $map = [
            ['username', 'like', $username],
            ['phone', 'like', $phone],
            ['order_no', 'like', $orderno],
            // 限制区域
            ['area_id', 'in', $area_list]
        ];
        //获取所有的订单信息
        $query = OrderModel::pageUtil($pagenum, $map, $pagesize)
                    ->with(['waste'])
                    ->withAttr('status_text', function ($value, $data) {
                        return OrderModel::$STATUS_MSG[$data['status']];
                    })->append(['status_text'])
                    ->select();
        //获取订单总数的数量
        $count = OrderModel::pageInfo()['total'];
        return success([
            'orderlist' => $query,
            'status' => OrderModel::$STATUS_MSG,
            'total' => $count
        ]);
    }

    public function DeleteOrder()
    {
        // 要删除的订单的id
        $id = Request::param('id');
        OrderModel::update(['status' => OrderModel::STATUS_CANCEL], ['id' => $id]);
        return success();
    }

    /**
     * 标记订单完成
     * @throws DataException
     */
    public function CompleteOrder()
    {
        // 要删除的订单的id
        $id = Request::param('id');
        OrderModel::complete($id);
        return successWithMsg('操作成功');
    }
}