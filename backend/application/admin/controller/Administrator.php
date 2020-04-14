<?php


namespace app\admin\controller;
use app\common\BaseController;
use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use think\facade\Request;
use app\admin\model\Administrator as AdminModel;

class Administrator extends Base{
    //根据账号密码修改管理员密码
    public function changepassword(){
        $password = Request::param('pass');
        $newpassword = Request::param('newpass');
        $admin = AdminModel::where('username','=','admin')->find();
        if ($admin->password == $password){
            $admin->password = $newpassword;
            $admin->save();
            return success();
        }
        throw new ApiException(ErrorCode::UPDATE_USER_RECORD_FAILED);
    }
}