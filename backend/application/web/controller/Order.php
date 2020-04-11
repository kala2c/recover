<?php


namespace app\web\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\exception\DataException;
use app\common\model\Waste as WasteModel;
use app\common\model\Address as AddressModel;
use app\index\controller\Wx;
use think\Exception\DbException;
use think\exception\ValidateException;
use think\facade\Validate;
use app\common\model\OrderMaster as OrderMasterModel;

class Order extends Base
{
    /**
     * 创建订单
     * @return mixed
     * @throws ApiException
     * @throws DataException
     */
    public function set()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'waste_id' => 'require',
            'waste_number' => 'require',
            'username' => 'require',
            'phone' => 'require',
            'area' => 'require',
            'address_detail' => 'require',
//            'pick_fast' => 'number', 1-尽快上门 2-预约时间
//            'pick_time' => '',
//            'note' => ''
        ], [
            'waste_id' => '请选择物品类型',
            'waste_number.require' => '请填写预估数量',
            'address_detail.require' => '请填写取货地址',
            'area.require' => '请填写取货地址',
            'username.require' => '请填写联系人姓名',
            'phone.require' => '请填写联系人手机号'
        ]);

        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        if ($data['pick_fast'] != 1 && !$data['pick_time']) {
            throw new ValidateException('尽快上门和预约时间至少选择一个');
        }

        $rlt = OrderMasterModel::set($data, $this->user_info['uid']);
        // 通知回收员
        $this->notifyPickman($rlt);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_ORDER_FAILED);
        }
        return successWithMsg('成功');
    }

    /**
     * 获取下单前的一些信息
     * @throws DbException
     */
    public function orderInfo()
    {
        $user_id = $this->user_info['uid'];
        $waste_list = WasteModel::all();
        $default_address = AddressModel::getDefaultAddress($user_id);

        return success([
            'pick_msg' => OrderMasterModel::$PICK_MSG,
            'waste_list' => $waste_list,
            'default_address' => $default_address
        ]);
    }

    /**
     * 获取订单列表
     * @throws DbException
     * @return mixed
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
        $map = ['status' => $param['status'], 'user_id' => $this->user_info['uid']];
        $orderList = OrderMasterModel::pageUtil($page, $map)
            ->with(['waste'])
            ->select()
            ->toArray(); //触发获取器
        $pageInfo = OrderMasterModel::pageInfo();
        return success([
            'orderList' => $orderList,
            'pageMax' => $pageInfo['pageMax']
        ]);
    }

    /**
     * 通知回收员新订单
     * @param $order
     */
    private function notifyPickman($order) {
        // 根据区域信息获取分配到的回收员openid
        // $openid = "";
        // 临时先用一个openid测试推送
        $open_id = "orPPws8Mr4LO42SEJA4WMZ5tsrPo";
        $template_id = config('secret.wx.templateId.newOrderNotify');
        $data = [
            "address" => [
                "value" => $order->address_detail,
                "color" => "#173177"
            ],
            "wasteName" => [
                "value" => $order->waste->name,
                "color" => "#173177"
            ],
            "number" => [
                "value" => $order->waste_number,
                "color" => "#173177"
            ],
            "unit" => [
                "value" => $order->waste->unit,
                "color" => "#173177"
            ],
        ];
        // 接单页面
        $url = "http://testwx2.c2wei.cn/#/pick/order";
        $wx = new Wx();
        $wx->sendMessage($open_id, $template_id, $url, $data);
    }
}