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
        // 是否直接绑定小区
        if (isset($data['area_id'])) {
            $area = Area::get($data['area_id']);
            if (!$area) throw new DataException(ErrorCode::AREA_NOT_EXISTS);
        }
        // 是否同时新增小区
        if (isset($data['area_name'])) {
            $top_id = $data['top_id'] ?? null;
            if (!($top_id)) throw new DataException(ErrorCode::PARAM_ERROR);
            $area = Area::add($top_id, $data['area_name']);
            $data['area_id'] = $area->id;
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