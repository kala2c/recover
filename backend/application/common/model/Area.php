<?php


namespace app\common\model;


use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
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
        $list = self::with('pickman')->order('level', 'desc')->order('top_id')->select()->toArray();
        $qu = [];
        $jd = [];
        $rlt = [];
        foreach ($list as $area) {
            if ($area['level'] == self::LEVEL_QU) {
                $qu[$area['id']] = $area;
            } elseif ($area['level'] == self::LEVEL_JD) {
                if (array_key_exists($area['top_id'], $qu)) {
                    $area['top'] = $qu[$area['top_id']];
                }
                $jd[$area['id']] = $area;
            } elseif ($area['level'] == self::LEVEL_JWH) {
                if (array_key_exists($area['top_id'], $jd)) {
                    $area['top'] = $jd[$area['top_id']];
                }
                $rlt[] = $area;
            }
        }
        return $rlt;
    }

    /**
     * 获取树形区域列表 上级.child = 下级
     * @param string $field
     * @return array
     * @throws DbException
     * @return mixed
     */
    const FRONTEND_INFO = 'id,level,top_id,name';
    static public function getTree($field = '*')
    {
        $list = self::field($field)->order('level')->select()->toArray();
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

//    /**
//     * 通过区域名字找到对应取货员
//     * @param string $area 例：芝罘区-世回尧街道-上尧
//     * @throws DbException
//     */
//    static public function getPickManByAreaName($area = '')
//    {
//        $area_info = explode('-', $area);
//        $map = [];
//        $area_data = self::where($map)->select();
//    }

    /**
     * 关联到取货员
     */
    public function Pickman()
    {
        return $this->belongsToMany('Pickman', 'pickman_area');
    }
}