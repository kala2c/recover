<?php

namespace app\admin\controller;

use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\exception\DataException;
use app\common\model\Area as AreaModel;
use think\Exception\DbException;
use think\exception\ValidateException;
use think\facade\Request;
use app\common\model\Depot as DepotModel;
use think\facade\Validate;

class Depot extends Base
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

    //返回站点列表
    public function depotList()
    {
        // 用于站点真实姓名的模糊查询
        $username = '%' . Request::param('username') . '%';
        // 用于站点手机号的模糊查询
        $mobile = '%' . Request::param('mobile') . '%';
        // 用于返回指定状态的用户,-1表示返回所有的用户
        $status = Request::param('status', '-1');
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取站点信息
        if ($status) {
            $query = DepotModel::pageUtil($pagenum, [['username', 'like', $username], ['mobile', 'like', $mobile], ['status', '=', $status]], $pagesize)->with(['area'])->select();
        } else {
            $query = DepotModel::pageUtil($pagenum, [['username', 'like', $username], ['mobile', 'like', $mobile]], $pagesize)->with(['area'])->select();
        }
        //获取用户总数的数量
        $count = DepotModel::pageInfo()['total'];
        return success([
            'depotlist' => $query,
            'total' => $count
        ]);
    }

    /**
     * 添加负责人
     * @return mixed
     * @throws DbException
     * @throws DataException
     * @throws ApiException
     */
    public function append()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'username' => 'require',
            'password' => 'require',
            'mobile' => 'require',
            'note' => 'require'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $rlt = DepotModel::add($data);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_USER_RECORD_FAILED);
        }
        return successWithMsg('添加成功');
    }


    //修改站点状态
    public function PickmanStatus()
    {
        $id = Request::param('id');
        $status = Request::param('status');
        $depot = DepotModel::get($id);
        $depot->status = $status;
        $depot->save();
        return success();
    }

    /**
     * 为站点分配区域
     * @throws \Exception
     */
    public function setArea()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'depot_id' => 'require|number',
            'area_id' => 'require'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        // 站点id和修改后的地区id
        $area_id = $data['area_id'];
        $depot_id = $data['depot_id'];
        $depot = DepotModel::with(['area'])->get($depot_id);
        $rlt = DepotModel::update(['area_id' => $area_id], ['id' => $depot_id]);
        if (!$rlt) {
            throw new ApiException(ErrorCode::SET_AREA_ADMIN_FAILED);
        }

        return successWithMsg('修改成功');
    }

    /**
     * 搜索区域
     * @throws DbException
     */
    public function searchArea()
    {
        $param = $this->request->get();
        $validate = Validate::make([
            'key' => 'require',
            'type' => 'require'
        ]);
        if (!$validate->check($param)) {
            throw new ValidateException($validate->getError());
        }
        $map = [
            ['name', 'like', '%'.$param['key'].'%'],
        ];
        $type = $param['type'];
        $level = 0;
        if ($type == 'street') $level = AreaModel::LEVEL_JD;
        if ($type == 'community') $level = AreaModel::LEVEL_JWH;
        array_push($map, ['level', '=', $level]);
        $rlt = AreaModel::where($map)->limit(0, 30)->select();

        return success($rlt);
    }
}