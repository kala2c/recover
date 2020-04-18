<?php


namespace app\common\model;


use app\common\error\ErrorCode;
use app\common\exception\DataException;

class Pickman extends Base
{

    const STATUS_FORBIDDEN = -1;
    const STATUS_WAIT = 0;
    const STATUS_NORMAL = 1;

    public static $STATUS_MSG = [
        self::STATUS_FORBIDDEN => '封禁',
        self::STATUS_WAIT => '待审核',
        self::STATUS_NORMAL => '正常',
    ];

    /**
     * 增加回收员
     * @param $data
     * @throws DataException
     * @return mixed
     */
    public static function add($data)
    {
        $pickman = self::get(['openid' => $data['openid']]);
        if ($pickman) {
            throw new DataException(ErrorCode::PICKMAN_EXISTS);
        }
        $data['password'] = encrypt($data['password']);
        $data['status'] = self::STATUS_WAIT;
        $data = self::addTimeField($data);
        return self::create($data);
    }
    /**
     * 关联到区域 多对多
     * @return mixed
     */
    public function Area()
    {
        return $this->belongsToMany('Area');
    }
}