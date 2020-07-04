<?php


namespace app\common\model;


use app\common\error\ErrorCode;
use app\common\exception\DataException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Area extends Base
{
    const LEVEL_COMMUNITY = 1;
    const LEVEL_STREET  = 2;
    const LEVEL_COUNTY  = 3;

    const LEVEL_STEP = 1; // 等级步长 即上级减下级

    public static $LEVEL_MSG = [
        self::LEVEL_COMMUNITY => '小区',
        self::LEVEL_STREET => '街道',
        self::LEVEL_COUNTY => '县(区)'
    ];

    const OTHER_COMMUNITY_ID = 1;
    const TOP_START_ID = 0;
    const STATUS_NORMAL = 0;
    const STATUS_DELETE = 1;
    const CITY_YANTAI = 1;
    const SUPER_ADMIN_ID = 1;

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
    const SHOW_TWO_LEVEL_AREA = true;
    const SHOW_ALL_LEVEL_AREA = false;
    static public function getTree($city_id, $area = false)
    {
        if ($area) {
            $map = [
                ['level', 'in', [self::LEVEL_STREET, self::LEVEL_COUNTY]]
            ];
        } else {
            $map = [
                ['id', '<>', self::OTHER_COMMUNITY_ID]
            ];
        }
        $list = self::field(self::FRONTEND_INFO)
            ->where([
                ['city_id', '=', self::CITY_YANTAI],
                ['status', '=', self::STATUS_NORMAL]
            ])
            ->where($map)
            ->order('level')
            ->select()->toArray();

        return self::arr2tree($list, self::TOP_START_ID);
    }

    /**
     * 根据城市id获取区域线性表
     * @param $city_id
     * @return mixed
     * @throws DbException
     */
    static public function getList($city_id)
    {
        $list = self::with(['administrator'])->where('city_id', $city_id)->select()->toArray();
        return $list;
    }

    /**
     * 线性表转树
     * @param $arr
     * @param $pid
     * @return array
     */
    static public function arr2tree($arr, $pid) {
        $pid_key_text = 'top_id';
        $primary_key_text = 'id';
        $child_text = 'child';
        $temp = [];
        $treeArr = $arr;
        foreach ($treeArr as $index => $item) {
            if ($item[$pid_key_text] === $pid) {
                $a2t = self::arr2tree($treeArr, $treeArr[$index][$primary_key_text]);
                if (count($a2t) > 0) {
                    // 递归
                    $treeArr[$index][$child_text] = $a2t;
                }
                array_push($temp, $treeArr[$index]);
            }
        }
        return $temp;
    }

    /**
     * 获取带有管理员信息的地区信息
     * @param $admin_id
     * @return array
     * @throws DbException
     */
    static public function getTreeWithAdmin($admin_id) {
        $map = [
            ['city_id', '=', self::CITY_YANTAI],
            ['status', '=', self::STATUS_NORMAL]
        ];
//        如果不是超管 只能看自己代理地区
        if ($admin_id != self::SUPER_ADMIN_ID) {
            array_push($map, ['administrator_id', '=', $admin_id]);
        }
        $list = self::with('administrator')
                ->where($map)
                ->order('level')
                ->order('id', 'desc')
                ->select();
        return self::arr2tree($list, self::TOP_START_ID);
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
//        添加县区时不需要判断
        if ($top_id != self::TOP_START_ID && !$top_area) {
            throw new DataException(ErrorCode::AREA_NOT_EXISTS);
        }
        if ($top_id != self::TOP_START_ID && $top_area->level <= self::LEVEL_COMMUNITY) {
            throw new DataException(ErrorCode::AREA_LEVEL_LOW);
        }
//        不设置管理员就是超管的
        $administrator_id = $data['admin_id'] ?? self::SUPER_ADMIN_ID;
        $data = [
            'top_id' => $top_id,
            'name' => $name,
            'city_id' => self::CITY_YANTAI,
            'administrator_id' =>
                $top_id != self::TOP_START_ID
                    ? $top_area->administrator_id
                    : $administrator_id,
            'level' =>
                $top_id != self::TOP_START_ID
                    ? $top_area->level-self::LEVEL_STEP
                    : self::LEVEL_COUNTY
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
//        递归查找下级存储在类属性中
        self::findChild([$area_id]);

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