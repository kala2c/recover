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

Route::post('/admin/user/login', 'admin/Login/login');
Route::post('/admin/user/logout', 'admin/Login/logout');
Route::get('/admin/user/info', 'admin/User/info');
Route::get('/admin/waste/getWasteList', 'admin/waste/getWasteList');
Route::get('/admin/weixin/check', '/index/weixin/check');

/* 微信端 接口路由*/
Route::post('/collect/feedback', 'web/FeedBack/create');
Route::post('/collect/order', 'web/Order/create');
Route::get('/collect/orders', 'web/Order/getList');

return [

];
