<?php


namespace app\common\model;


use think\Exception\DbException;

class City extends Base
{

    /**
     * 添加城市
     * @param $data
     * @return mixed
     */
    public static function add($data)
    {
        $data = self::addTimeField($data);
        return self::create($data);
    }

    /**
     * 修改城市
     * @param $data
     * @return City|bool
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
     * 通过城市描述获取城市
     * @param string $str
     * @throws DbException
     * @return mixed
     */
    public function getCityByText($str = "")
    {
        // 处理$name
        return self::where(['name' => 'name'])->find();
    }
}