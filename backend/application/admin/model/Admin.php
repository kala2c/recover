<?php


namespace app\admin\model;


use think\Model;
use app\common\exception\ApiException;
use app\common\error\ErrorCode;

class Admin extends Model
{
    //登录验证
      public function LoginVerify($username,$password)
      {
        $reslut = $this->where("username='$username' and password='$password'")->find();
        if($reslut){
          return $reslut;
        }else{
         throw new ApiException(ErrorCode::GET_USER_INFO_EMPTY,30004);
        }
      }
}