<?php


namespace app\web\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\model\Pickman as PickmanModel;
use app\admin\model\Administrator as AdminModel;
use think\Controller;

class Depot extends Base
{
    protected $depot;

    /**
     * 初始化
     * @throws ApiException
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub

        $openid = $this->user_info['openid'];
        $this->depot = AdminModel::get(['openid' => $openid]);
        if (!$this->depot) {
            throw new ApiException(ErrorCode::DEPOT_NOT_LOGIN);
        }
        if ($this->depot->level != AdminModel::LEVEL_DEPOT) {
            throw new ApiException(ErrorCode::DEPOT_NOT_EXISTS);
        }
    }

    /**
     * 获取回收点信息
     * return mixed
     */
    public function info()
    {
        $depot = $this->depot;
        unset($depot['id']);
        unset($depot['openid']);
        unset($depot['password']);

        return success($depot);
    }

}