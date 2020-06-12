<?php

namespace app\admin\model;

use app\common\error\ErrorCode;
use app\common\exception\DataException;
use app\common\model\Base;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Administrator extends Base
{

    const LEVEL_DEPOT = 100;

    /**
     * 添加新用户
     * @param $data
     * @return Administrator
     * @throws DataException
     * @throws DbException
     */
    public static function add($data)
    {
        $admin = self::where('username', $data['username'])->whereOr('mobile', $data['mobile'])->find();
        if ($admin) {
            throw new DataException(ErrorCode::ADMIN_EXIST);
        }
        if (!isset($data['level'])) {
            $data['level'] = 500;
        }
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