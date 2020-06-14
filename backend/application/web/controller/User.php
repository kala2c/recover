<?php


namespace app\web\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use app\common\exception\DataException;
use think\exception\DbException;
use think\facade\Cache;
use think\exception\ValidateException;
use think\facade\Validate;
use app\common\model\User as UserModel;
use app\common\model\Pickman as PickmanModel;
use app\common\model\Depot as DepotModel;
use think\response\Json;

class User extends Base
{
    /**
     * 获取用户信息
     * @return mixed
     */
    public function info()
    {
        $uid = $this->user_info['uid'];
        $name = Cache::get("name.$uid");
        $avatar = Cache::get("avatar.$uid");
        return success([
            'name' => $name,
            'avatar' => $avatar
        ]);
    }

    /**
     * 回收员申请
     * @throws ApiException
     * @throws DataException
     */
    public function addPickman()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'phone' => 'require',
            'password' => 'require',
            'sex' => 'require',
            'realname' => 'require',
            'age' => 'require|number',
        ], [
            'phone.require' => '手机号不可缺少',
            'password.require' => '密码不可缺少',
            'sex.require' => '性别不可缺少',
            'realname.require' => '姓名不可缺少',
            'age.require' => '年龄不可缺少'
        ]);

        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $data['openid'] = $this->user_info['openid'];
        $rlt = PickmanModel::add($data);
        if (!$rlt) {
            throw new ApiException(ErrorCode::INSERT_PICKMAN_FAILED);
        }
        return successWithMsg('申请成功');
    }


    /**
     * 回收点登录
     * @throws
     */
    public function depotSignIn()
    {
        $data = $this->request->post();
        $validate = Validate::make([
            'username' => 'require',
            'password' => 'require'
        ])->message([
            'username.require' => '请填写用户名',
            'password.require' => '请填写密码'
        ]);
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $depot = DepotModel::where('username', $data['username'])->whereOr('mobile', $data['username'])->find();
        if (!$depot) {
            throw new ApiException(ErrorCode::DEPOT_NOT_EXISTS);
        }
        if ($depot->password != $data['password']) {
            throw new ApiException(ErrorCode::ACCOUNT_PASSWORD_ERROR);
        }
        $openid = $this->user_info['openid'];
        $depot->openid = $openid;
        if ($depot->save()) {
            return successWithMsg('登录成功');
        } else {
            throw new ApiException(ErrorCode::UPDATE_DEPOT_FAILED);
        }
    }

    /**
     * 获取附近回收员
     * @throws DbException
     */
    public function getPickmanList()
    {
        $list = PickmanModel::with(['area'])->where('status', 0)->limit(0, 10)->select();
        return success($list);
    }

    /**
     * 将前台传过来的经纬度解析为文字地址描述
     * 高德地图接口 含有街道办事处信息
     * @return Json
     */
    public function getStreetInfo()
    {
        $location = $this->request->get('location');
        $user = UserModel::get($this->user_info['uid']);
        $openid = $user->openid;
//        $address = Cache::get("$openid.addressGd");
//        if (!$address) {
            $data = $this->pos2addressGaode($location, $openid);
            return success($data);
//        } else {
//            $address = json_decode($address, true);
//            list($new_lat, $new_lng) = explode(',', $location);
//            $old_lat = $address['location']['lat'];
//            $old_lng = $address['location']['lng'];
//            地址变化时才会重新获取
//            if (abs($new_lat-$old_lat) > 0.01 || abs($new_lng-$old_lng) > 0.01) {
//                $data = $this->pos2address($location, $openid);
//                $address = $data;
//            }
//            return success($address);
//        }
    }

    /**
     * 将前台传过来的经纬度解析为文字地址描述
     * 腾讯地图接口 不含街道办事处信息
     * @return Json
     */
    public function getTextLoc()
    {
        $location = $this->request->get('location');
        $user = UserModel::get($this->user_info['uid']);
        $openid = $user->openid;
        $address = Cache::get("$openid.address");
        if (!$address) {
            $data = $this->pos2address($location, $openid);
            return success($data);
        } else {
            $address = json_decode($address, true);
            list($new_lat, $new_lng) = explode(',', $location);
            $old_lat = $address['location']['lat'];
            $old_lng = $address['location']['lng'];
//            地址变化时才会重新获取
            if (abs($new_lat-$old_lat) > 0.01 || abs($new_lng-$old_lng) > 0.01) {
                $data = $this->pos2address($location, $openid);
                $address = $data;
            }
            return success($address);
        }
    }

    /**
     * 获取用户地址
     */
    public function getLocation()
    {
//        $param = $this->request->get();
//        $validate = Validate::make([
//            'location' => 'require'
//        ]);
//        if (!$validate->check($param)) {
//            throw new ValidateException($validate->getError());
//        }
//        $location = $param['location']; //39.984154,116.307490
        $user = UserModel::get($this->user_info['uid']);
        $openid = $user->openid;
        $location = Cache::get("$openid.location");
        if (!$location) {
            return success([
                'errMsg' => '获取位置信息失败'
            ]);
        }
        $address = Cache::get("$openid.address");
        if (!$address) {
            $data = $this->pos2address($location, $openid);
            return success($data);
        } else {
            $address = json_decode($address, true);
            list($new_lat, $new_lng) = explode(',', $location);
            $old_lat = $address['location']['lat'];
            $old_lng = $address['location']['lng'];
//            地址变化时才会重新获取
            if (abs($new_lat-$old_lat) > 0.01 || abs($new_lng-$old_lng) > 0.01) {
                $data = $this->pos2address($location, $openid);
                $address = $data;
            }
            return success($address);
        }

    }

    /**
     * 腾讯地图接口
     * 将缓存的经纬度转换为文字坐标
     * @param $location string 经纬坐标 36.108678,149.585123
     * @param $openid string openid
     * @return mixed
     */
    private function pos2address($location, $openid)
    {
        $key = config('secret.qqMap.key');
//        $secret = 'd1BPD2s8xaKvn6Xi6alsE7cQS7opU0R';
//        $raw_string = "/ws/geocoder/v1?key=$key&location=$location".$secret;
//        $sig = md5($raw_string);
//        $api = "https://apis.map.qq.com/ws/geocoder/v1/?key=$key&location=$location&sig=$sig";
        $api = "https://apis.map.qq.com/ws/geocoder/v1/?key=$key&location=$location";

        $response = \Requests::get($api);
        $data = json_decode($response->body, true);
        $address = $data['result'];
        if ($address) {
            Cache::set("$openid.address", json_encode($address));
        }
        return $address;
    }

    /**
     * 高德地图接口
     * 将经纬度转换为文字坐标
     * @param $location string 经纬坐标 36.108678,149.585123
     * @param $openid string openid
     * @return mixed
     */
    private function pos2addressGaode($location, $openid)
    {
        $key = config('secret.gdMap.key');
        $latlng = explode(',', $location);
        $location = $latlng[1].','.$latlng[0];
        $api = "https://restapi.amap.com/v3/geocode/regeo?key=$key&location=$location";

        $response = \Requests::get($api);
        $data = json_decode($response->body, true);
        $address = $data;
        if ($address) {
            Cache::set("$openid.addressGd", json_encode($address));
        }
        return $address;
    }
}