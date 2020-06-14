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
Route::get('/admin/user/info', 'admin/Administrator/info');
Route::post('/admin/user/changepassword', 'admin/Administrator/changePassword');
//管理员端 管理员相关
Route::get('/admin/admin/list', 'admin/Administrator/getList');
Route::post('/admin/admin/append', 'admin/Administrator/append');
Route::post('/admin/admin/setInfo', 'admin/Administrator/set');
//管理员端 城市相关
Route::get('/admin/city/list', 'admin/City/getList');
Route::post('/admin/city/append', 'admin/City/append');
Route::post('/admin/city/setInfo', 'admin/City/set');
//管理员端 用户账号相关
Route::post('/admin/user/UserList', 'admin/user/UserList');
Route::post('/admin/user/lock', 'admin/User/lock');
Route::post('/admin/user/unlock', 'admin/User/unlock');
//管理员端 废品相关
Route::get('/admin/waste/WasteList', 'admin/waste/WasteList');
Route::get('/admin/waste/WasteInfo', 'admin/waste/WasteInfo');
Route::get('/admin/waste/setWasteInfo', 'admin/waste/setWasteInfo');
//管理员端 订单相关
Route::get('/admin/order/OrderList','admin/order/OrderList');
Route::post('/admin/order/DeleteOrder','admin/order/DeleteOrder');
Route::post('/admin/order/CompleteOrder','admin/order/CompleteOrder');
//管理员端 取货员相关
Route::get('/admin/pickman/PickmanList','admin/pickman/PickmanList');
Route::post('/admin/pickman/PickmanStatus','admin/pickman/PickmanStatus');
Route::post('/admin/pickman/setArea','admin/pickman/setArea');
//管理员端 站点相关
Route::get('/admin/depot/depotList','admin/Depot/depotList');
//Route::post('/admin/depot/PickmanStatus','admin/depot/PickmanStatus');
Route::post('/admin/depot/setArea','admin/depot/setArea');
// 管理员端 地区相关
Route::get('/admin/area/list','admin/area/getList');
Route::get('/admin/area/adminlist','admin/area/getAdminList');
Route::post('/admin/area/append','admin/area/append');
Route::post('/admin/area/remove','admin/area/remove');
Route::post('/admin/area/setAdmin','admin/area/setAdmin');
// 管理员端 banner图等
Route::get('/admin/banner/list','admin/IndexPage/getBannerList');
Route::post('/admin/banner/append','admin/IndexPage/addBanner');
Route::post('/admin/banner/delete','admin/IndexPage/delBanner');

//工具相关
Route::get('/admin/dashboard', 'admin/Administrator/dashboard');
//微信接入相关
Route::get('/admin/weixin/check', '/index/Index/wxCheck');


//微信网页授权登录
Route::get('/wx/oauth', 'web/Login/oauth');
//微信js-sdk
Route::get('/wx/sdkconf', 'index/Wx/sdkConf');
/* 微信端 接口路由*/


Route::get('/web/collect/banner', 'web/Index/getBannerList');
//用户信息
Route::get('/web/user/info', 'web/User/info');
Route::get('/web/user/location', 'web/User/getLocation');
Route::get('/web/user/textloc', 'web/User/getTextLoc');
Route::get('/web/user/street', 'web/User/getStreetInfo');
Route::get('/web/pickman/list', 'web/User/getPickmanList');

Route::get('/collect/selfCommunity', 'web/User/selfCommunity');
Route::get('/collect/custom', 'web/User/custom');


// 提交反馈
Route::post('/collect/feedback', 'web/FeedBack/create');
// 地址相关
Route::get('/collect/address', 'web/Address/get');
Route::get('/collect/addresses', 'web/Address/getList');
Route::get('/collect/area', 'web/Address/getArea');
Route::get('/collect/community', 'web/Address/getCommunity');
Route::post('/collect/address/set', 'web/Address/set');
Route::post('/collect/address/default', 'web/Address/setDefault');
// 订单相关
Route::get('/collect/orderInfo', 'web/Order/orderInfo');
Route::post('/collect/order', 'web/Order/set');
Route::post('/collect/cancelorder', 'web/Order/cancel');
Route::get('/collect/order', 'web/Order/get');
Route::get('/collect/orders', 'web/Order/getList');

// 回收点信息
Route::get('/depot/info', 'web/Depot/info');
Route::post('/depot/signin', 'web/User/depotSignIn');
// 回收点订单相关
Route::get('/depot/takeorders', 'web/DepotOrder/getTakeOrders');
//Route::post('/depot/deliveredorder', 'web/TakeOrder/delivered');
Route::get('/depot/orders', 'web/DepotOrder/getList');
Route::post('/depot/order/take', 'web/DepotOrder/takeOrder');
//Route::get('/depot/navigate', 'web/Pickman/navigate');

// 取货员信息
Route::get('/pickman/info', 'web/Pickman/info');
Route::post('/pickman/signup', 'web/User/addPickman');
// 取货员订单相关
Route::get('/pick/takeorders', 'web/TakeOrder/getTakeOrders');
Route::post('/pick/deliveredorder', 'web/TakeOrder/delivered');
Route::get('/pick/orders', 'web/TakeOrder/getList');
Route::post('/pick/order/take', 'web/TakeOrder/takeOrder');
Route::get('/pick/navigate', 'web/Pickman/navigate');

return [

];
