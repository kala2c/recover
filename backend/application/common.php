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

// 应用公共文件
// 响应的格式
function result($code = 200, $status = "success", $message = "OK", $data = [])
{
    $template = [
        "code" => $code,
        "status" => $status,
        "message" => $message,
        "data" => $data
    ];
    return json($template);
}

function successWithMsg($message = "OK", $data = []) {
    return result(200, "success", $message, $data);
}

function success($data = [])
{
    return result(200, "success", "OK", $data);
}

function error($data = [])
{
    return result(500, "error", "服务出错了，稍后再试", $data);
}