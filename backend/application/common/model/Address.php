<?php


namespace app\common\model;


use app\common\BaseModel;
use think\Db;
use think\Exception;
use think\Exception\DbException;

class Address extends Base
{
    const STATUS_NORMAL = 0;
    const STATUS_DEFAULT = 1;

    static public $STATUS_MSG = [
        self::STATUS_NORMAL => '普通',
        self::STATUS_DEFAULT => '默认地址'
    ];

    /**
     * 新增地址
     * @param $data int
     * @param $user_id int
     * @return Address
     * @throws DbException
     */
    static public function add($data, $user_id)
    {
        //找寻用户已有的地址
        $address_list = self::where(['user_id' => $user_id])->select();
        //用户没有地址设新增地址为默认 已有地址则为普通
        if ($address_list->isEmpty()) {
            $data['status'] = self::STATUS_DEFAULT;
        } else {
            $data['status'] = self::STATUS_NORMAL;
        }
        $data['user_id'] = $user_id;
        $data = self::addTimeField($data);
        return self::create($data);
    }

    /**
     * 修改地址
     * @param $data array
     * @param $id int 地址id
     * @param $user_id int 用户id
     * @return Address|Boolean
     */
    static public function set($data, $id, $user_id)
    {
        $address = self::get($id);
        if (!$address) return false;
        if ($address->user_id != $user_id) return false;
        $data = self::addTimeField($data, false);
        $address->data($data);
        return $address->save($data);
    }

    /**
     * 获取默认地址
     * @param $user_id
     * @throws DbException
     * @return mixed
     */
    static public function getDefaultAddress($user_id)
    {
        $map = [
            'user_id' => $user_id,
            'status' => self::STATUS_DEFAULT
        ];
        return self::where($map)->find();
    }

    /**
     * @param $id int 要设为默认的地址id
     * @param $user_id int
     * @return Boolean
     */
    public static function setDefault($id, $user_id)
    {
        // 开启事务
        Db::startTrans();
        try {
            // 获取要修改的地址
            $offset_address = self::get($id);
            if (!$offset_address) return false;
            // 本来就是默认 直接返回true
            if ($offset_address->status == self::STATUS_DEFAULT) return true;

            // 获取原来的默认地址

            $default_address = self::getDefaultAddress($user_id);
            // 更新为普通
            $default_address->status = self::STATUS_NORMAL;
            $rlt1 = $default_address->save();
            // 不是默认 更新为默认
            $offset_address->status = self::STATUS_DEFAULT;
            $rlt2 = $offset_address->save();
            // 提交事务
            Db::commit();

            if ($rlt1 && $rlt2) {
                return true;
            }
        } catch (DbException $e) {
            Db::rollback();
        } catch (Exception $e) {
            Db::rollback();
        }
        return false;
    }

    /**
     * 关联到User
     */
    public function User()
    {
        return $this->belongsTo('User');
    }
}