<?php

namespace app\admin\controller;

use app\common\model\pivot\Pickman_Area;
use think\Exception\DbException;
use think\exception\ValidateException;
use think\facade\Request;
use app\common\model\Pickman as PickmanModel;
use think\facade\Validate;
use think\response\Json;

class Pickman extends Base
{
    const STATUS_INIT = 0;
    const STATUS_NORMAL = 1;
    const STATUS_SUBAUTH = 2;
    const STATUS_FREEZE = 3;

    const PICK_FAST = 1;
    const PICK_SUB = 2;

    static public $PICKMAN_STATUS = [
        self::STATUS_INIT => '未提交认证',           //即刚新建的账号
        self::STATUS_NORMAL => '正常',              //通过认证的
        self::STATUS_SUBAUTH => '已提交认证',        //已提交认证信息但管理员还没有审核通过
        self::STATUS_FREEZE => '冻结'
    ];

    //返回取货员列表
    public function PickmanList()
    {
        // 用于取货员真实姓名的模糊查询
        $realname = '%' . Request::param('realname') . '%';
        // 用于取货员手机号的模糊查询
        $phone = '%' . Request::param('phone') . '%';
        // 用于返回指定状态的用户,-1表示返回所有的用户
        $status = Request::param('status', '-1');
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取取货员信息
        if ($status) {
            $query = PickmanModel::pageUtil($pagenum, [['realname', 'like', $realname], ['phone', 'like', $phone], ['status', '=', $status]], $pagesize)->select();
        } else {
            $query = PickmanModel::pageUtil($pagenum, [['realname', 'like', $realname], ['phone', 'like', $phone]], $pagesize)->with(['area'])->select();
        }
        //获取用户总数的数量
        $count = PickmanModel::pageInfo()['total'];
        return success([
            'pickmanlist' => $query,
            'total' => $count
        ]);
    }

    //修改取货员状态
    public function PickmanStatus()
    {
        $id = Request::param('id');
        $status = Request::param('status');
        $pickman = PickmanModel::get($id);
        $pickman->status = $status;
        $pickman->save();
        return success();
    }

    /**
     * 为取货员分配区域
     * @throws \Exception
     */
    public function setArea()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'pickman_id' => 'require',
            'area_id' => 'require'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        // 取货员id和修改后的地区id
        $pickman_id = $data['pickman_id'];
        $new_area = explode(',', $data['area_id']); // [0, 1, 2] 地区id列表
        // 取货员原本的地区
        $pickman_area_pivot = new Pickman_Area();
        $area_list = $pickman_area_pivot->where('pickman_id', $pickman_id)->select(); // 中间表数据集
        // 要删除的地区和要新增的地区
        $old_area = []; // [0, 1, 2] 地区id列表
        $del_list = []; // [0, 1, 2] 中间表id列表
        $add_list = []; // [ [ 'pickman_id' => 1, 'area_id' => 1 ], ... ] 要插入的数据集
        // 遍历一趟原地区
        foreach ($area_list as $area) {
            array_push($old_area, $area->area_id);
            if (!in_array($area->area_id, $new_area)) {
                array_push($del_list, $area->id);
            }
        }
        // 遍历一趟新地区
        foreach ($new_area as $id) {
            if (!in_array($id, $old_area)) {
                array_push($add_list, [ 'pickman_id' => $pickman_id, 'area_id' => $id ]);
            }
        }
//        exit;
        // 集中写入
        Pickman_Area::destroy($del_list);
        $pickman_area_pivot->saveAll($add_list);
        return successWithMsg('修改成功');
    }
}