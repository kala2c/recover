<?php


namespace app\common\model;


use app\common\error\ErrorCode;
use app\common\exception\DataException;

class OrderMaster extends Base
{
    const STATUS_CANCEL = -1;
    const STATUS_WAIT = 0;
    const STATUS_GOING = 1;
    const STATUS_SUCCESS = 2;

    const PICK_FAST = 1;
    const PICK_SUB = 2;

    static public $STATUS_MSG = [
        self::STATUS_CANCEL => '已取消',
        self::STATUS_WAIT => '待处理',
        self::STATUS_GOING => '服务中',
        self::STATUS_SUCCESS => '已完成'
    ];

    static public $PICK_MSG = [
        self::PICK_FAST => '尽快上门',
        self::PICK_SUB => '预约时间'
    ];

    /**
     * 新增订单
     * @param $data
     * @param $user_id
     * @return mixed
     * @throws DataException
     */
    static public function set($data, $user_id)
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