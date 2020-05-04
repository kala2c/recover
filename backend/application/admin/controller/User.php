<?php


namespace app\admin\controller;

use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use think\Controller;

use app\common\model\User as UserModel;
use think\exception\DbException;
use think\exception\ValidateException;
use think\facade\Request;
use think\facade\Validate;

class User extends Base
{
    /**
     * 用户列表
     * @throws DbException
     */
    public function UserList()
    {
        // 用于微信名称的模糊查询
        $username = Request::param('username') . '%';
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取所有的用户信息
        $query = UserModel::pageUtil($pagenum, [['username', 'like', $username]], $pagesize)
                                ->with(['address'])
                                ->append(['statusText'])
                                ->select();
        //获取用户总数的数量
        $count = UserModel::pageInfo()['total'];
        return success([
            'userlist' => $query,
            'total' => $count
        ]);
    }

    /**
     * 禁用用户
     * @throws ApiException
     */
    public function lock()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'user_id' => 'require|number'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $map = ['id' => $data['user_id']];
        $scope = ['status' => UserModel::STATUS_FORBIDDEN];
        $rlt = UserModel::update($scope, $map);
        if (!$rlt) {
            throw new ApiException(ErrorCode::LOCK_USER_FAILED);
        }
        return successWithMsg('操作成功');
    }

    /**
     * 解除禁用
     * @throws ApiException
     */
    public function unlock()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'user_id' => 'require|number'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $map = ['id' => $data['user_id']];
        $scope = ['status' => UserModel::STATUS_NORMAL];
        $rlt = UserModel::update($scope, $map);
        if (!$rlt) {
            throw new ApiException(ErrorCode::UNLOCK_USER_FAILED);
        }
        return successWithMsg('操作成功');
    }
}