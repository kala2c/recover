<?php


namespace app\admin\controller;

use app\common\BaseController;
use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\model\OrderMaster as OrderModel;
use app\common\model\Pickman as PickmanModel;
use app\common\model\Depot as DepotModel;
use app\common\model\User as UserModel;
use app\common\model\Waste as WasteModel;
use think\exception\DbException;
use think\exception\ValidateException;
use think\facade\Request;
use app\admin\model\Administrator as AdminModel;
use think\facade\Validate;

class Administrator extends Base
{
    /**
     * 根据账号密码修改管理员密码
     * @throws ApiException
     * @throws DbException
     */
    public function changePassword()
    {
        $password = Request::param('pass');
        $newpassword = Request::param('newpass');
        $admin = AdminModel::where('username', '=', 'admin')->find();
        if ($admin->password == $password) {
            $admin->password = $newpassword;
            $admin->save();
            return success();
        }
        throw new ApiException(ErrorCode::UPDATE_USER_RECORD_FAILED);
    }

    /**
     * 获取管理员信息
     * @param Request $request
     * @return mixed
     */
    public function info(Request $request)
    {
        $admin = $this->self;
        return success([
            'roles' => explode(',', $admin['permission']),
            'introduction' => $admin['note'],
            'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            'name' => $admin['username'],
        ]);

    }

    /**
     * 获取负责人列表
     * @throws DbException
     */
    public function getList()
    {
        $param = $this->request->get();
        $validate = Validate::make([
            'pagenum' => 'number',
            'pagesize' => 'number'
        ]);
        if (!$validate->check($param)) {
            throw new ValidateException($validate->getError());
        }
        $page = $param['pagenum'] ?? 1;
        $pageSize = $param['pagesize'] ?? 10;
        $top_id = $this->self->id;
        $map = "top_id=$top_id";
        if (isset($param['query']) && !empty($param['query'])) {
            $query = $param['query'];
            $map .= " AND (username like '%$query%' OR mobile like '%$query%' OR note like '%$query%')";
        }
        $admin_list = AdminModel::pageUtil($page, $map, $pageSize)->with(['area'])->hidden(['password'])->select();
        return success([
            'list' => $admin_list,
            'meta' => AdminModel::pageInfo()
        ]);
    }

    /**
     * 添加负责人
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
        $data['top_id'] = $this->self->id;
        $rlt = AdminModel::add($data);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_USER_RECORD_FAILED);
        }
        return successWithMsg('添加成功');
    }

    /**
     * 修改负责人信息
     * @throws ApiException
     */
    public function set()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'id' => 'number',
//            'username' => 'require',
//            'password' => 'require',
//            'mobile' => 'require',
//            'note' => 'require'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $rlt = AdminModel::set($data);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_USER_RECORD_FAILED);
        }
        return successWithMsg('修改成功');
    }

    //后台首页数据聚合
    public function dashboard()
    {
        //废品种类总数
        $wastecount = WasteModel::count();
        //用户总数
        $allusercount = UserModel::count();
        //今日新增用户数
        $todayusercount = UserModel::whereTime('create_time', 'today')->count();

        //回收点总数
        $depotcount = DepotModel::where('status', '=', 0)->count();
        //总成交订单数
        $allordercount = OrderModel::where('status', '=', OrderModel::STATUS_SUCCESS)->count();
        //今日成交订单数
        $todayordercount = OrderModel::where('status', '=', OrderModel::STATUS_SUCCESS)->whereTime('create_time', 'today')->count();
        //今日下单数
//        $todayordercount = OrderModel::where('','=',OrderModel::STATUS_SUCCESS)->count();
        //今日成交额
//        $todayordersum = OrderModel::where('status','=',OrderModel::STATUS_SUCCESS)->whereTime('create_time','today')->count()
        $result_map = [
            'dashboarddata' => [
                'wastecount' => $wastecount,
                'allusercount' => $allusercount,
                'depotcount' => $depotcount,
                'todayusercount' => $todayusercount,
                'allordercount' => $allordercount,
                'todayordercount' => $todayordercount
            ]
        ];
        return success($result_map);
    }
}