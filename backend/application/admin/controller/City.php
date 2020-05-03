<?php


namespace app\admin\controller;


use app\common\model\City as CityModel;
use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use think\exception\DbException;
use think\exception\ValidateException;
use think\facade\Validate;

class City extends Base
{


    /**
     * 获取城市列表
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
        $map = [];
        if (isset($param['query']) && !empty($param['query'])) {
            $query = $param['query'];
            $map = "name like '%$query%' OR note like '%$query%'";
        }
        $city_list = CityModel::pageUtil($page, $map, $pageSize)->with(['administrator'])->select();
        return success([
            'list' => $city_list,
            'meta' => CityModel::pageInfo()
        ]);
    }

    /**
     * 添加城市
     * @throws ApiException
     */
    public function append()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'name' => 'require',
            'note' => 'require'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $rlt = CityModel::add($data);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_USER_RECORD_FAILED);
        }
        return successWithMsg('添加成功');
    }

    /**
     * 修改城市
     * @throws ApiException
     */
    public function set()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'id' => 'number',
//            'name' => 'require',
//            'note' => 'require',
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $rlt = CityModel::set($data);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_USER_RECORD_FAILED);
        }
        return successWithMsg('修改成功');
    }

}