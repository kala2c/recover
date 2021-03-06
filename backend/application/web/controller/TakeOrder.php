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
 * 取货员接单 历史订单信息等
 * Class TakeOrder
 * @package app\web\controller
 */
class TakeOrder extends Pickman
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
        // 获取回收员负责的区域
        $area_list = $this->pickman->area;
        $area_id_list = [];
        foreach ($area_list as $area) {
            $area_id_list[] = $area->id;
        }
        // 区域为空 直接返回空
        if (empty($area_id_list)) {
            return success([
                'list' => [],
                'status' => OrderMasterModel::$STATUS_MSG,
                'pageMax' => 1
            ]);
        }
        $map = [
            ['area_id', 'in', $area_id_list],
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
        $pickman_id = $this->pickman->id;
        $map = [
            'pickman_id' => $pickman_id,
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
     * 接单
     * @throws ApiException
     * @throws DataException
     */
    public function takeOrder()
    {
        // 审查账号审核是否通过
        if ($this->pickman->status != PickmanModel::STATUS_NORMAL) {
            throw new ApiException(ErrorCode::PICKMAN_WAIT_AUDIT);
        }
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
        setPickman($data['order_id'], $this->pickman);
        if (!$rlt) {
            throw new ApiException(ErrorCode::TAKE_ORDER_FAILED);
        }
        return successWithMsg('接单成功');
    }

    /**
     * 标记订单已送达
     * @return mixed
     * @throws ApiException
     * @throws DataException
     */
    public function delivered()
    {
        // 审查账号审核是否通过
        if ($this->pickman->status != PickmanModel::STATUS_NORMAL) {
            throw new ApiException(ErrorCode::PICKMAN_WAIT_AUDIT);
        }
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
        $rlt = OrderMasterModel::delivered($data['order_id'], $this->pickman);
        if (!$rlt) {
            throw new ApiException(ErrorCode::UPDATE_ORDER_FAILED);
        }
        return successWithMsg('操作成功');
    }
}