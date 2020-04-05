<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
//header('Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, PUT');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Max-Age: 3600');
//header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
//header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,authorization');

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
// 执行应用并响应
Container::get('app')->run()->send();