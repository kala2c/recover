<?php


namespace app\common\model;


class User extends Base
{
    static public function addUser($data)
    {
        self::addTimeField($data);
        return self::create($data);
    }
}