<?php


namespace app\common\model;


class Pickman extends Base
{

    const STATUS_FORBIDDEN = -1;
    const STATUS_WAIT = 0;
    const STATUS_NORMAL = 1;

    public static $STATUS_MSG = [
        self::STATUS_FORBIDDEN => '封禁',
        self::STATUS_WAIT => '待审核',
        self::STATUS_NORMAL => '正常',
    ];

    public function set($data)
    {

    }
    /**
     * 关联到区域 多对多
     * @return mixed
     */
    public function Area()
    {
        return $this->belongsToMany('Area');
    }
}