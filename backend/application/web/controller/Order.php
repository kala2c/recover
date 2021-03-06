<?php


namespace app\web\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\exception\DataException;
use app\common\model\Area as AreaModel;
use app\common\model\Waste as WasteModel;
use app\common\model\Address as AddressModel;
use app\index\controller\Wx;
use think\Exception\DbException;
use think\exception\ValidateException;
use think\facade\Validate;
use app\common\model\OrderMaster as OrderMasterModel;
use app\common\model\Depot as DepotModel;
use think\facade\Request;

/**
 * 用户下单 订单进度等
 * Class Order
 * @package app\web\controller
 */
class Order extends Base
{
    /**
     * 创建订单
     * @return mixed
     * @throws ApiException
     * @throws DataException
     * @throws DbException
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
//            'address_detail' => 'require',
//            'pick_fast' => 'number', 1-尽快上门 2-预约时间
//            'pick_time' => '',
//            'note' => ''
        ], [
            'waste_id' => '请选择物品类型',
            'waste_number.require' => '请填写预估数量',
//            'address_detail.require' => '请填写取货地址',
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

        $rlt = OrderMasterModel::add($data, $this->user_info['uid']);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_ORDER_FAILED);
        }
        // 通知回收点
        $this->notifyDepot($rlt);
        // 通知回收员
//        $this->notifyPickman($rlt);
        return successWithMsg('成功');
    }

    /**
     * 获取下单前的一些信息
     * @throws DbException
     */
    public function orderInfo()
    {
        $wastekindid = Request::param('wastekindid');
        $user_id = $this->user_info['uid'];
        if ($wastekindid == ''){
            $waste_list = WasteModel::all();
        }else{
            $waste_list = WasteModel::where('wastekindid','=',$wastekindid)->select();
        }

        $default_address = AddressModel::getDefaultAddress($user_id);

        return success([
            'pick_msg' => OrderMasterModel::$PICK_MSG,
            'waste_list' => $waste_list,
            'default_address' => $default_address
        ]);
    }

    /**
     * 取消订单
     * @return mixed
     * @throws DataException
     * @throws ApiException
     */
    public function cancel()
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
        $user_id = $this->user_info['uid'];
        $rlt = OrderMasterModel::cancel($data['order_id'], $user_id);
        if (!$rlt) {
            throw new ApiException(ErrorCode::CANCEL_ORDER_FAILED);
        }
        return successWithMsg('取消成功');
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
            ->order('create_time', 'desc')
            ->select()
            ->toArray(); //触发获取器
        $pageInfo = OrderMasterModel::pageInfo();
        return success([
            'orderList' => $orderList,
            'status' => OrderMasterModel::$STATUS_MSG,
            'pageMax' => $pageInfo['pageMax']
        ]);
    }

    /**
     * 通知回收点
     * @param $order
     * @throws DbException
     */
    private function notifyDepot($order) {
        // 根据区域信息获取到回收员信息
        $area_id = $order->area_id;
        if (!$area_id) return;
        $depot = DepotModel::where('area_id', $area_id)->find();
        if (!$depot) return;
        // 组装模板信息
        $template_id = config('secret.wx.templateId.newOrderNotify');
        $time = date('m-d H:i:s', strtotime($order->pick_time));
        $data = [
            "first" => [
                "value" => '有用户下单了',
                "color" => "#173177"
            ],
            "keyword1" => [
                "value" => $order->pick_fast == 1 ? '尽快上门' : '用户预约'.$time,
                "color" => "#173177"
            ],
            "keyword2" => [
                "value" => $order->waste->name.$order->waste_number.$order->waste->unit,
                "color" => "#173177"
            ],
            "keyword3" => [
                 "value" => $order->phone,
                 "color" => "#173177"
            ],
            "remark" => [
                "value" => $order->address_detail,
                "color" => "#173177"
            ]
        ];
        // 接单页面
        $url = config('secret.wx.takeOrderUrl');
        // 根据回收员openid发送通知
        $wx = new Wx();
        $openid = $depot->openid ?? null;
        if (empty($openid)) return;
        $wx->sendMessage($openid, $template_id, $url, $data);
    }

    /**
     * 通知回收员新订单
     * @param $order
     */
    private function notifyPickman($order) {
        // 根据区域信息获取到回收员信息
        $area = AreaModel::get($order->area_id);
        if (!$area) return;
        $pickmen = $area->pickman;
        // 组装模板信息
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
        $url = config('secret.wx.takeOrderUrl');
        // 根据回收员openid发送通知
        $wx = new Wx();
        foreach ($pickmen as $pickman) {
            $openid = $pickman->openid;
            $wx->sendMessage($openid, $template_id, $url, $data);
        }
    }
}