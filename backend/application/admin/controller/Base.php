<?php


namespace app\admin\controller;


use app\common\exception\ApiException;
use Firebase\JWT\JWT;
use think\Controller;
use think\facade\Cache;

class Base extends Controller
{
    protected $self_info;

    public function initialize()
    {
        $token = $this->request->header('authorization') ?? null;
        if (isset($token)) {
            $token = explode(' ', $token);
            if (count($token) > 1) {
                if ($token[0] = 'Bearer') {
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
            header('HTTP/1.1 401 Unauthorized');
            header('Content-Type: application/json; charset=utf-8');
            throw new ApiException('未登录 请先登录');
        }

        $self_info       = JWT::decode($token, 'recover', ['HS256']);
        $this->self_info = (array)$self_info;
    }
}