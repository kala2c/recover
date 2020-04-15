<?php

namespace app\admin\controller;

use think\facade\Request;
use app\common\model\Pickman as PickmanModel;

class Pickman extends Base
{
    //返回取货员列表
    public function PickmanList()
    {
        // 用于取货员真实姓名的模糊查询
        $realname = '%' . Request::param('realname') . '%';
        // 用于取货员手机号的模糊查询
        $phone = '%' . Request::param('phone') . '%';
        // 用于返回指定状态的用户,-1表示返回所有的用户
        $status = Request::param('status', '-1');
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取取货员信息
        if ($status) {
            $query = PickmanModel::pageUtil($pagenum, [['realname', 'like', $realname], ['phone', 'like', $phone], ['status', '=', $status]], $pagesize)->select();
        } else {
            $query = PickmanModel::pageUtil($pagenum, [['realname', 'like', $realname], ['phone', 'like', $phone]], $pagesize)->select();
        }
        //获取用户总数的数量
        $count = PickmanModel::pageInfo()['total'];
        return success([
            'pickmanlist' => $query,
            'total' => $count
        ]);
    }

    //修改取货员状态
    public function PickmanStatus()
    {
        $id = Request::param('id');
        $status = Request::param('status');
        $pickman = PickmanModel::get($id);
        $pickman->status = $status;
        $pickman->save();
        return success();
    }
}