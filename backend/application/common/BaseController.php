<?php


namespace app\common;


use app\common\error\ErrorCode;
use app\common\exception\ApiException;
use Firebase\JWT\JWT;
use think\Controller;
use think\facade\Cache;

class BaseController extends Controller
{
    protected $user_info;

    public function initialize()
    {
        $token = $this->request->header('authorization') ?? null;
        if (isset($token)) {
            $token = explode(' ', $token);
            if (count($token) > 1) {
                if (strtolower($token[0]) === 'bearer') {
                    $token = $token[1];
                } else {
                    $token = null;
                }
            } else {
                $token = null;
            }
        }
        $local_key = !$token ? false : Cache::get($token);
        if (!$token || !$local_key) {
            throw new ApiException(ErrorCode::UNAUTHORIZED,401);
        }

        $user_info       = JWT::decode($token, 'recover', ['HS256']);
        $this->user_info = (array)$user_info;
    }
}