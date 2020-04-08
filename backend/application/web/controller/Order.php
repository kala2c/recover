<?php


namespace app\web\controller;


use app\common\exception\ApiException;
use app\common\model\Waste as WasteModel;
use app\common\model\Address as AddressModel;
use mysql_xdevapi\TableInsert;
use think\Exception\DbException;
use think\exception\ValidateException;
use think\facade\Validate;
use app\common\model\OrderMaster as OrderMasterModel;
class Order extends Base
{
    /**
     * 创建订单
     * @return mixed
     */
    public function create()
    {
        $post = $this->request->post();
        $validate = Validate::make([
            'weight' => 'require',
//            'pick_fast' => 'number',
//            'pick_time' => 'require',
            'address' => 'require'
        ], [
            'weight.require' => '请选择预估重量',
//            'sub_time.require' => '请填写预约时间',
            'address.require' => '请填写取货地址'
        ]);

        if (!$validate->check($post)) {
            throw new ValidateException($validate->getError());
        }

//        OrderMasterModel::create($post)
        return successWithMsg('操作成功');
    }

    /**
     * 获取下单的一些信息
     * @throws DbException
     */
    public function orderInfo()
    {
        $user_id = $this->user_info['uid'];
        $waste_list = WasteModel::all();
        $default_address = AddressModel::getDefaultAddress($user_id);

        return success([
            'waste_list' => $waste_list,
            'default_address' => $default_address
        ]);
    }

    /**
     * 获取订单列表
     * @return mixed
     */
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