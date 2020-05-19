<?php

namespace app\admin\model;

use app\common\model\Base;

class Administrator extends Base
{
    /**
     * 添加新用户
     * @param $data
     * @return Administrator
     */
    public static function add($data)
    {
        $data['level'] = 500;
        $data = self::addTimeField($data);
        return self::create($data);
    }

    /**
     * 修改信息
     * @param $data
     * @return mixed
     */
    public static function set($data)
    {
        $data = self::addTimeField($data, false);
        if (!isset($data['id'])) return false;
        $id = $data['id'];
        unset($data['id']);
        return self::update($data, ['id' => $id]);
    }
    /**
     * 关联到区域
     */
    public function area()
    {
        return $this->hasMany('\\app\\common\\model\\Area');
    }
}