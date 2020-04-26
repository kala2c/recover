<?php


namespace app\common\model;


use app\common\error\ErrorCode;
use app\common\exception\DataException;
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
        return self::list2tree($list);
    }

    static private function list2tree($list) {
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

    /**
     * 获取带有管理员信息的地区信息
     * @return array
     * @throws DbException
     */
    static public function getTreeWithAdmin() {
        $list = self::with('administrator')->order('level')->select();
        return self::list2tree($list);
    }

    /**
     * 新增区域
     * @param $top_id
     * @param $name
     * @return Area
     * @throws DataException
     */
    public static function add($top_id, $name)
    {
        $top_area = self::get($top_id);
        if (!$top_area) {
            throw new DataException(ErrorCode::AREA_NOT_EXISTS);
        }
        if ($top_area->level <= self::LEVEL_JWH) {
            throw new DataException(ErrorCode::AREA_LEVEL_LOW);
        }
        $data = [
            'top_id' => $top_id,
            'name' => $name,
            'administrator_id' => $top_area->administrator_id,
            'level' => $top_area->level-self::LEVEL_STEP
        ];
        $data = self::addTimeField($data);
        return self::create($data);
    }

    /**
     * 变更该地区及所属地区的管理员
     * @param $area_id
     * @param $admin_id
     * @return Area
     */
    public static function setAdmin($area_id, $admin_id)
    {
        $data = ['admin_id' => $admin_id];
        $data = self::addTimeField($data, false);
        return self::update($data, ['id' => $area_id]);
    }

//    public static function findChild($id_list = [])
//    {
//        $area = [];
//
//    }

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
     * 多对多关联
     */
    public function Pickman()
    {
        return $this->belongsToMany('Pickman', 'pickman_area');
    }

    /**
     * 关联到管理员
     * 多对一关联
     */
    public function Administrator()
    {
        return $this->belongsTo('\\app\\admin\\model\\Administrator');
    }
}