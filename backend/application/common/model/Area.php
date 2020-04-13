<?php


namespace app\common\model;


use think\exception\DbException;

class Area extends Base
{
    const LEVEL_JWH = 1;
    const LEVEL_JD  = 2;
    const LEVEL_QU  = 3;

    const LEVEL_STEP = 1; // 等级步长 即上级减下级

    public static $LEVEL_MSG = [
        self::LEVEL_JWH => '居委会',
        self::LEVEL_JD => '街道',
        self::LEVEL_QU => '区'
    ];

    /**
     * 获取区域列表
     * @throws DbException
     */
    static public function getList()
    {
        $list = self::order('level')->select()->toArray();
        $qu = [];
        $jd = [];
        $rlt = [];
        foreach ($list as $area) {
            if ($area['level'] == self::LEVEL_QU) {
                if (array_key_exists($area['id'], $qu)) {
                    $area['child'] = $qu[$area['id']];
                }
                $rlt[] = $area;
            } elseif ($area['level'] == self::LEVEL_JD) {
                if (array_key_exists($area['id'], $jd)) {
                    $area['child'] = $jd[$area['id']];
                }
                $qu[$area['top_id']][] = $area;
            } elseif ($area['level'] == self::LEVEL_JWH) {
                $jd[$area['top_id']][] = $area;
            }
        }
        return $rlt;
    }
}