<?php


namespace app\admin\controller;

use think\Controller;

use app\common\model\User as UserModel;
use think\facade\Request;

class User extends Base
{
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

    public function UserList()
    {
        // 用于微信名称的模糊查询
        $username = Request::param('username') . '%';
        //分页的大小
        $pagesize = Request::param('pagesize', '10');
        //当前在第几页
        $pagenum = Request::param('pagenum', '1');
        //获取所有的用户信息
        $query = UserModel::pageUtil($pagenum, [['username', 'like', $username]], $pagesize)->select();
        //获取用户总数的数量
        $count = UserModel::pageInfo()['total'];
        return success([
            'userlist' => $query,
            'total' => $count
        ]);
    }
}