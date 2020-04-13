<?php


namespace app\web\controller;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use think\facade\Cache;
use think\exception\ValidateException;
use think\facade\Validate;
use app\common\model\User as UserModel;

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
     * 获取用户地址
     * @throws ApiException
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
     * 将缓存的经纬度转换为文字坐标
     * @param $location
     * @param $openid
     * @return mixed
     */
    private function pos2address($location, $openid)
    {
        $key = 'NGLBZ-PFLAJ-N2AFY-FYYM3-IKZS7-MABJT';
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
}