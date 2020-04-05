<?php


namespace app\common\model;


class OrderMaster extends Base
{
    const STATUS_CANCEL = -1;
    const STATUS_WAIT = 0;
    const STATUS_GOING = 1;
    const STATUS_SUCCESS = 2;

    const STATUS_MSG = [
        self::STATUS_CANCEL => '已取消',
        self::STATUS_WAIT => '待处理',
        self::STATUS_GOING => '服务中',
        self::STATUS_SUCCESS => '已完成'
    ];

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