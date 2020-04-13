<?php
namespace app\web\controller;

use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\model\Area as AreaModel;
use think\Exception\DbException;
use think\exception\ValidateException;
use think\facade\Validate;
use \app\common\model\Address as AddressModel;

class Address extends Base
{
    /**
     * 根据id获取单个地址
     */
    public function get()
    {
        $id = $this->request->get('id') ?? null;
        if (!$id) {
            throw new ValidateException('缺少id');
        }
        $address = AddressModel::get($id);

        return success($address);
    }

    /**
     * 获取用户的全部地址
     * @throws DbException
     */
    public function getList()
    {
        $user_id = $this->user_info['uid'];
        $address_list = AddressModel::where(['user_id' => $user_id])->select();
        return success($address_list);
    }

    /**
     * 添加/编辑地址
     * @throws DbException
     * @throws ApiException
     */
    public function set()
    {
        // 接收并验证参数
        $data = $this->request->post();
        $validate = Validate::make([
            'id' => 'number',
            'name' => 'require',
            'phone' => 'require',
            'area' => 'require',
            'detail' => 'require'
        ], [
            'id.number' => 'id格式不正确',
            'name.require' => '名字不可缺少',
            'phone.require' => '联系方式不可缺少',
            'area.require' => '地区不可缺少',
            'detail.require' => '地址详细信息不可缺少',
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        //判断是新增还是修改
        $user_id = $this->user_info['uid'];

        if (array_key_exists('id', $data)) {
            $id = $data['id'];
            unset($data['id']);
            $rlt = AddressModel::set($data, $id, $user_id);
            if (!$rlt) {
                throw new ApiException(ErrorCode::UPDATE_ADDRESS_FAILED);
            }
            return successWithMsg('修改成功');
        } else {
            $rlt = AddressModel::add($data, $user_id);
            if (!$rlt) {
                throw new ApiException(ErrorCode::INSERT_ADDRESS_FAILED);
            }
            return successWithMsg('添加成功');
        }
    }

    /**
     * 修改默认地址
     * @throws ApiException
     */
    public function setDefault()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'id' => 'require|number'
        ], [
            'id.require' => 'id不可缺少',
            'id.number' => 'id格式不正确'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $user_id = $this->user_info['uid'];
        $rlt = AddressModel::setDefault($data['id'], $user_id);
        if (!$rlt) {
            throw new ApiException(ErrorCode::SER_ADDRESS_DEFAULT_FAILED);
        }
        return successWithMsg('设置成功');
    }

    /**
     * 获取区域信息
     * @throws DbException
     */
    public function getArea()
    {
        $data = AreaModel::getList();
        return success($data);
    }
}