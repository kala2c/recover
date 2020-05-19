<?php


namespace app\common\model;


class User extends Base
{
    const STATUS_NORMAL = 0;
    const STATUS_FORBIDDEN = 1;

    static public $STATUS_MSG = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_FORBIDDEN => '已封禁'
    ];

    public function getStatusTextAttr($value, $row)
    {
        return self::$STATUS_MSG[$row['status']];
    }

    static public function addUser($data)
    {
        $data = self::addTimeField($data);
        return self::create($data);
    }

    public function address()
    {
        return $this->hasMany('Address');
    }

    public function OrderMaster()
    {
        return $this->hasMany('OrderMaster');
    }
}