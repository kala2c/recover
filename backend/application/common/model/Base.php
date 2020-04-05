<?php


namespace app\common\model;


use think\Model;

class Base extends Model
{
    /**
     * 为数据添加时间戳字段
     * @param array $post 数据
     * @param bool $create 是否添加创建时间 默认-是
     * @param bool $update 是否添加修改时间 默认-是
     * @return array 添加字段后的数据
     */
    static public function addTimeField($post = [], $create = true, $update = true)
    {
        if (empty($post)) return [];
        if ($create) {
            $post['create_time'] = date("Y-m-d H:i:s");
//            $post['create_time'] = time();
        }
        if ($update) {
            $post['update_time'] = date("Y-m-d H:i:s");
//            $post['update_time'] = time();
        }
        return $post;
    }
}