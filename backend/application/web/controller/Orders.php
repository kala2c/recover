<?php


namespace app\web\controller;


use app\common\exception\ApiException;
use think\exception\ValidateException;
use think\facade\Validate;
use app\common\model\OrderMaster as OrderMasterModel;
class Orders extends Base
{
    public function create()
    {
        $post = $this->request->post();
        $validate = Validate::make([
            'weight' => 'require',
            'sub_time' => 'require',
            'address' => 'require'
        ], [
            'weight.require' => '请选择回收重量',
            'sub_time.require' => '请填写预约时间',
            'address.require' => '请填写取货地址'
        ]);

        if (!$validate->check($post)) {
            throw new ValidateException($validate->getError());
        }

//        OrderMasterModel::create($post)
        return successWithMsg('操作成功');
    }

    public function getList()
    {
        $param = $this->request->get();
        $validate = Validate::make([
            'type' => 'require',
            'page' => 'integer|egt:1',
        ], [
            'type.require' => '请选择订单类型',
            'page.integer' => '页码格式不正确',
            'page.egt' => '页码不能为负数'
        ]);

        if (!$validate->check($param)) {
            throw new ValidateException($validate->getError());
        }
        $map = ['type' => $param['type']];
//        $orderList = OrderMasterModel::pageUtil()->select();
        return success();
    }
}