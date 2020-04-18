<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use app\common\error\ErrorCode;
// 应用公共文件
// 响应的格式
const OK_CODE = ErrorCode::OK;
/**
 * @param int $code 业务状态码
 * @param string $status http状态码 success->200 error->500 其它为数字
 * @param string $message 返回消息
 * @param array $data 返回数据
 * @return \think\response\Json 结果是json响应
 */
function result($code = OK_CODE, $status = "success", $message = "OK", $data = [])
{
    if ($status === "success") $status = 200;
    if ($status === "error") $status = 500;
    $template = [
        "code" => $code,
        "message" => $message,
        "data" => $data
    ];
    return json($template);
//    return json($template)->code($status);
}

function successWithMsg($message = "OK", $data = []) {
    return result(OK_CODE, "success", $message, $data);
}

function success($data = [])
{
    return result(OK_CODE, "success", "OK", $data);
}

function error($data = [])
{
    return result(-1, "error", "服务出错了，稍后再试", $data);
}

function encrypt($str) {
    return sha1(md5($str));
}