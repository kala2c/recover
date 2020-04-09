<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//管理员端 管理员账号相关
Route::post('/admin/user/login', 'admin/Login/login');
Route::post('/admin/user/logout', 'admin/Login/logout');
Route::get('/admin/user/info', 'admin/User/info');
//管理员端 废品相关
Route::get('/admin/waste/WasteList', 'admin/waste/WasteList');
Route::get('/admin/waste/WasteInfo', 'admin/waste/WasteInfo');
Route::get('/admin/waste/setWasteInfo', 'admin/waste/setWasteInfo');
//工具相关
Route::post('/admin/dashboard', 'admin/Base/dashboard');
//微信接入相关
Route::get('/admin/weixin/check', '/index/weixin/check');


//微信登录
Route::get('/wx/oauth', 'web/Login/oauth');
//微信js-sdk
Route::get('/wx/sdkconf', 'index/Weixin/sdkConf');
/* 微信端 接口路由*/

//用户信息
Route::get('/web/user/info', 'web/User/info');
// 提交反馈
Route::post('/collect/feedback', 'web/FeedBack/create');
// 地址相关
Route::get('/collect/address', 'web/Address/get');
Route::get('/collect/addresses', 'web/Address/getList');
Route::post('/collect/address/set', 'web/Address/set');
Route::post('/collect/address/default', 'web/Address/setDefault');
// 订单相关
Route::get('/collect/orderInfo', 'web/Order/orderInfo');
Route::post('/collect/order', 'web/Order/set');
Route::get('/collect/order', 'web/Order/get');
Route::get('/collect/orders', 'web/Order/getList');

return [

];
