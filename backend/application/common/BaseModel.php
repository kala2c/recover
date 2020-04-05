<?php


namespace app\common;

use think\Model;

class BaseModel extends Model
{
    static public $page = [];

    /**
     * @param int 当前页码
     * @param array 查询条件
     * @param int 每页数量
     * @return \think\db\Query 返回的是Query对象，不是结果集，可以继续进行排序等查询操作
     * 排序会对所有符合条件的数据排，不只对本页数据
     */
    static public function pageUtil($page = 1, $map = [], $pageCount = 10)
    {
        $total = self::where($map)->count(); //总条数
        $pageMax = ceil($total/$pageCount); //最大页码
        $offset = ($page-1)*$pageCount; //查询起始位置

        self::$page = [
            'page'       => $page,
            'pageMax'    => $pageMax,
            'total'      => $total,
            'pageCount'  => $pageCount,
        ];
        return self::where($map)->limit($offset, $pageCount);
    }

    /**
     * @return array 返回分页信息
     */
    static public function pageInfo()
    {
        return self::$page;
    }
}