<?php


namespace app\common\model;


class User extends Base
{
    const STATUS_NORMAL = 0;
    const STATUS_FORBIDDEN = 1;

    static public $STATUS_MSG = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_FORBIDDEN => '用户已封禁'
    ];

    static public function addUser($data)
    {
        $data = self::addTimeField($data);
        return self::create($data);
    }
}