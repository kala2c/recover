<?php


namespace app\web\controller;

use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\exception\DataException;
use app\common\model\OrderMaster as OrderMasterModel;
use app\common\model\Pickman as PickmanModel;
use think\Exception\DbException;
use think\exception\ValidateException;
use think\facade\Validate;

/**
 * 回收站接单 历史订单信息等
 * Class TakeOrder
 * @package app\web\controller
 */
class DepotOrder extends Depot
{

    /**
     * 获取可以接的订单
     * @throws DbException
     * @return mixed
     */
    public function getTakeOrders()
    {
        $param = $this->request->get();
        $validate = Validate::make([
            'page' => 'integer|egt:1',
        ], [
            'page.integer' => '页码格式不正确',
            'page.egt' => '页码不能为负数'
        ]);
        if (!$validate->check($param)) {
            throw new ValidateException($validate->getError());
        }
        $page = $param['page'] ?? 1;
        // 获取回收负责的区域
        $area_id = $this->depot->area_id;
//        没有负责区域直接返回空
        if (!$area_id) {
            return success([
                'list' => [],
                'status' => OrderMasterModel::$STATUS_MSG,
                'pageMax' => 0
            ]);
        }
        $map = [
            ['area_id', 'in', $area_id],
            ['status', 'eq', OrderMasterModel::STATUS_WAIT]
        ];
        // 查询列表
        $list = OrderMasterModel::pageUtil($page, $map)->with(['waste'])->select();
        $pageInfo = OrderMasterModel::pageInfo();
        return success([
            'list' => $list,
            'status' => OrderMasterModel::$STATUS_MSG,
            'pageMax' => $pageInfo['pageMax']
        ]);
    }

    /**
     * 获取服务过的订单
     * @throws DbException
     */
    public function getList()
    {
        $param = $this->request->get();
        $validate = Validate::make([
            'status' => 'require',
            'page' => 'integer|egt:1',
        ], [
            'status.require' => '请选择订单状态',
            'page.integer' => '页码格式不正确',
            'page.egt' => '页码不能为负数'
        ]);

        if (!$validate->check($param)) {
            throw new ValidateException($validate->getError());
        }
        $page = $param['page'] ?? 1;
        $depot_id = $this->depot->id;
        $map = [
            'depot_id' => $depot_id,
            'status' => $param['status']
        ];
        $list = OrderMasterModel::pageUtil($page, $map)
                    ->order('update_time', 'desc')
                    ->with(['waste'])->select();
        $pageInfo = OrderMasterModel::pageInfo();
        return success([
            'list' => $list,
            'status' => OrderMasterModel::$STATUS_MSG,
            'pageMax' => $pageInfo['pageMax']
        ]);
    }

    /**
     * 处理订单
     * @throws ApiException
     * @throws DataException
     */
    public function takeOrder()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'order_id' => 'require|number'
        ], [
            'order_id.require' => '请选择订单',
            'order_id.number' => '订单id格式不正确'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $rlt = OrderMasterModel::
        setDepot($data['order_id'], $this->depot);
        if (!$rlt) {
            throw new ApiException(ErrorCode::TAKE_ORDER_FAILED);
        }
        return successWithMsg('接单成功');
    }
}