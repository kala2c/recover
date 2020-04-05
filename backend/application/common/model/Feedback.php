<?php


namespace app\common\model;


class Feedback extends Base
{
    /**
     * 关联到user
     */
    public function User()
    {
        return $this->hasOne('User');
    }
}