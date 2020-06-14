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
//    static public function getList()
//    {
//        $list = self::with('pickman')->order('level', 'desc')->order('top_id')->select()->toArray();
//        $qu = [];
//        $jd = [];
//        $rlt = [];
//        foreach ($list as $area) {
//            if ($area['level'] == self::LEVEL_QU) {
//                $qu[$area['id']] = $area;
//            } elseif ($area['level'] == self::LEVEL_JD) {
//                if (array_key_exists($area['top_id'], $qu)) {
//                    $area['top'] = $qu[$area['top_id']];
//                }
//                $jd[$area['id']] = $area;
//            } elseif ($area['level'] == self::LEVEL_JWH) {
//                if (array_key_exists($area['top_id'], $jd)) {
//                    $area['top'] = $jd[$area['top_id']];
//                }
//                $rlt[] = $area;
//            }
//        }
//        return $rlt;
//    }

    /**
     * 获取树形区域列表 上级.child = 下级
     * @param string $field
     * @param bool $area 是否只选择两级 默认否
     * @return array
     * @throws DataException
     * @throws DataNotFoundException
     * @throws DbException
     * @return mixed
     */
    const FRONTEND_INFO = 'id,level,top_id,name';
    static public function getTree($city_id, $area = false)
    {
        $list = self::field(self::FRONTEND_INFO)
            ->where('city_id', $city_id)
            ->order('level')
            ->select()->toArray();
        if ($area) {
            return self::list2tree($list);
        } else {
            return self::list2tree2($list);
        }
    }

    /**
     * 将线性表转为树 三级 区-街道-小区
     * @param $list
     * @return array
     */
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
            }
            elseif ($area['level'] == self::LEVEL_JWH) {
                $jd[$area['top_id']][] = $area;
            }
        }
        return $rlt;
    }
    
    /**
     * 将线性表转为树 二级 区-街道
     * @param $list
     * @return array
     */
    static private function list2tree2($list) {
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
        $list = self::with('administrator')->where('city_id', 1)->order('level')->select();
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
            'city_id' => 1,
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
     * @throws DbException
     */
    static private $area_id_list = [];
    public static function setAdmin($area_id, $admin_id)
    {
        array_push(self::$area_id_list, $area_id);
//        self::$area_id_list[] = $top_id;
        self::findChild([$area_id]);

//        $data = [];
//        foreach (self::$area_id_list as $id) {
//            array_push($data, self::addTimeField([
//                'admin_id' => $admin_id,
//                'id' => $id
//            ], false));
//        }
        $data = self::addTimeField([
            'administrator_id' => $admin_id
        ], true);
        return self::where('id', 'in', self::$area_id_list)->update($data);
    }

    /**
     * 递归查找所有子地区
     * @param array $top_id
     * @return bool
     * @throws DbException
     */
    public static function findChild($top_id = [])
    {

        if (empty($top_id)) {
            return false;
        } else {
            $data = self::where('top_id', 'in', $top_id)->select()->toArray();
            $top_id = array_column($data, 'id');
//            self::$area_id_list[] = $top_id;
            self::$area_id_list = array_merge(self::$area_id_list, $top_id);
            self::findChild($top_id);
        }
        return true;
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
     * 多对多关联
     */
    public function Pickman()
    {
        return $this->belongsToMany('Pickman', 'pickman_area');
    }

    /**
     * 关联到城市
     */
    public function city()
    {
        return $this->belongsTo('City');
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