<?php


namespace app\common\model;


use app\common\error\ErrorCode;
use app\common\exception\DataException;

class OrderMaster extends Base
{
    const STATUS_CANCEL = -1;
    const STATUS_WAIT = 0;
    const STATUS_GOING = 1;
    const STATUS_RECYCLING = 2;
    const STATUS_SUCCESS = 3;

    const PICK_FAST = 1;
    const PICK_SUB = 2;

    static public $STATUS_MSG = [
        self::STATUS_CANCEL => '已取消',
        self::STATUS_WAIT => '待取货',
        self::STATUS_GOING => '取货中',
        self::STATUS_RECYCLING => '回收中',
        self::STATUS_SUCCESS => '已完成'
    ];

    static public $PICK_MSG = [
        self::PICK_FAST => '尽快上门',
        self::PICK_SUB => '预约时间'
    ];

//    /**
//     * 定义字段获取器 将status => 文本
//     * 在toArray()或toJson()时自动触发 非显式方法
//     * @param $value
//     * @param $data
//     * @return mixed
//     */
//    public function getStatusAttr($value, $data)
//    {
//        return self::$STATUS_MSG[$data['status']];
//    }
//    public function getStatusMsgAttr($value, $data)
//    {
//        return self::$STATUS_MSG[$data['status']];
//    }

    /**
     * 新增订单
     * @param $data
     * @param $user_id
     * @return mixed
     * @throws DataException
     */
    static public function add($data, $user_id)
    {
        $data['user_id'] = $user_id;
        $waste = Waste::get($data['waste_id']);
        if (!$waste) throw new DataException(ErrorCode::WASTE_NOT_EXISTS);
        if ($data['pick_fast'] == self::PICK_FAST) {
            $data['pick_time'] = date("Y-m-d H:i:s");
        } else {
            $data['pick_time'] = date("Y-m-d H:i:s", $data['pick_time']);
        }
        $data['waste_price'] = $waste->price;
        $data['order_no'] = self::createOrderNo();
        $data['status'] = self::STATUS_WAIT;
        $data = self::addTimeField($data);
        return self::create($data);
    }

    /**
     * 生成十八位订单编号
     * @return string
     */
    static private function createOrderNo()
    {
        return date('YmdHis').rand(1000, 9999);
    }

    /**
     * 取消订单
     * @param $order_id
     * @param $user_id
     * @return OrderMaster
     * @throws DataException
     */
    public static function cancel($order_id, $user_id)
    {
        $order = OrderMaster::get($order_id);
        if ($order->user_id != $user_id) {
            throw new DataException(ErrorCode::COMMON_ERROR, 400, '该订单不是您的订单');
        }
        if ($order->status != self::STATUS_WAIT) {
            throw new DataException(ErrorCode::COMMON_ERROR, 400, '该订单不可取消');
        }
        $data = ['status' => self::STATUS_CANCEL];
        $data = self::addTimeField($data, false);
        return self::update($data, ['id' => $order_id]);
    }

    /**
     * 为订单设置取货员
     * @param $order_id
     * @param $pickman_id
     * @throws DataException
     * @return OrderMaster
     */
    static public function setPickman($order_id, $pickman)
    {
        $order = OrderMaster::get($order_id);
        if ($order->status != self::STATUS_WAIT) {
            if ($order->pickman_id == $pickman->id) {
                throw new DataException(ErrorCode::ORDER_CANT_REPEAT_TAKE);
            } else {
                throw new DataException(ErrorCode::ORDER_NOT_WAIT);
            }
        }
        $data = ['pickman_id' => $pickman->id, 'status' => self::STATUS_GOING];
        $data = self::addTimeField($data, false);
        return self::update($data, ['id' => $order_id]);
    }

    /*
    * 关联到user
    */
    public function User()
    {
        return $this->belongsTo('User');
    }

    /**
     * 关联到取货员
     */
//    public function Pickman()
//    {
//        return $this->belongsTo('Pickman');
//    }

    /**
     * 关联到waste
     */
    public function Waste()
    {
        return $this->belongsTo('Waste');
    }
}