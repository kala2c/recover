<?php


namespace app\admin\controller;
use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\exception\DataException;
use app\common\model\Area as AreaModel;
use app\admin\model\Administrator as AdminModel;
use think\exception\DbException;
use think\exception\ValidateException;
use think\facade\Validate;

class Area extends Base
{
    /**
     * 获取列表
     * 树形
     * @throws DbException
     */
    public function getList()
    {
        $uid = $this->user_info['uid'];
        $areaTable = AreaModel::getTreeWithAdmin($uid);
        return success($areaTable);
    }

    public function getAdminList()
    {
        $admin_list = AdminModel::field('id,username,note,mobile')->all();
        return success($admin_list);
    }

    /**
     * 添加地区
     * @throws ApiException
     * @throws DataException
     */
    public function append()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'top_id' => 'require',
            'name' => 'require'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $top_id = $data['top_id'];
        $name = $data['name'];
        $rlt = AreaModel::add($top_id, $name);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_AREA_FAILED);
        }
        return successWithMsg('添加成功');
    }

    /**
     * 删除地区
     * @throws ApiException
     */
    public function remove()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'id' => 'require',
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $id = $data['id'];
        $rlt = AreaModel::update(['status' => 1], ['id' => $id]);
        if (!$rlt) {
            throw new ApiException(ErrorCode::DELETE_AREA_FAILED);
        }
        return successWithMsg('删除成功');
    }

    /**
     * 变更负责人
     * @throws ApiException
     */
    public function setAdmin()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'area_id' => 'require',
            'admin_id' => 'require'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $area_id = $data['area_id'];
        $admin_id = $data['admin_id'];
        $rlt = AreaModel::setAdmin($area_id, $admin_id);
        if (!$rlt) {
            throw new ApiException(ErrorCode::SET_AREA_ADMIN_FAILED);
        }
        return successWithMsg('变更成功');
    }
}