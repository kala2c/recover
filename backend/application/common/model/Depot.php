<?php


namespace app\common\model;


use app\common\error\ErrorCode;
use app\common\exception\DataException;
use think\exception\DbException;

class Depot extends Base
{

    /**
     * 添加新用户
     * @param $data
     * @return Depot
     * @throws DataException
     * @throws DbException
     */
    public static function add($data)
    {
        $depot = self::where('username', $data['username'])->whereOr('mobile', $data['mobile'])->find();
        if ($depot) {
            throw new DataException(ErrorCode::ADMIN_EXIST);
        }
        $data['status'] = 0;
        $data = self::addTimeField($data);
        return self::create($data);
    }

    public function area()
    {
        return $this->belongsTo('Area');
    }
}