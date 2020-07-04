<?php


namespace app\common\error;


class ErrorCode
{
    const OK = 10000;
    const NET_ERROR = 10001;
    const PARAM_ERROR = 10003;
    const COMMON_ERROR = 10004;
    const LOGIN_EXPIRED = 10005;
    const UNAUTHORIZED = 10006;
    const TIME_OUT = 10007;
    const WX_AUTH_ERROR = 10008;
    const ACCOUNT_PASSWORD_ERROR = 10009;
//    const = 1008; 单点登录
    const USER_LOCKED = 10010;

    /*************DB 操作错误代码**************/
    const LOST_CONNECT = 20000;
    const CONFIG_ERROR = 20001;
    const SQL_QUERY_FAILED = 20002;

    /*************USER 操作类错误代码**********/
    const GET_USER_INFO_FAILED = 30001;
    const INSERT_USER_RECORD_FAILED = 30002;
    const UPDATE_USER_RECORD_FAILED = 30003;
    const GET_USER_INFO_EMPTY = 30004;
    const WX_OAUTH_FAILED = 30005;
    const WX_GET_INFO_FAILED = 30006;
    const GET_LOCATION_FAILED = 30007;
    const LOCK_USER_FAILED = 30008;
    const UNLOCK_USER_FAILED = 30009;

    const ADMIN_EXIST = 30010;



    /*************Feedback 错误代码**********/
    const INSERT_FEEDBACK_FAILED = 40001;

    /*************Address 错误代码**********/
    const INSERT_ADDRESS_FAILED = 50001;
    const UPDATE_ADDRESS_FAILED = 50002;
    const SER_ADDRESS_DEFAULT_FAILED = 50003;

    /*************Order 订单代码**********/
    const INSERT_ORDER_FAILED = 60001;
    const UPDATE_ORDER_FAILED = 60002;
    const GET_ORDER_DETAIL_FAILED = 60003;
    const GET_ORDER_LIST_FAILED = 60004;
    const TAKE_ORDER_FAILED = 60005;
    const ORDER_NOT_WAIT = 60006;
    const ORDER_NOT_GOING = 60007;
    const ORDER_NOT_RECYCLING = 60008;
    const CANT_SET_ORDER = 60009;
    const ORDER_NOT_EXIST = 60010;
    const ORDER_CANT_REPEAT_TAKE = 600011;
    const CANCEL_ORDER_FAILED = 600012;



    /*************Waste 废品代码**********/
    const INSERT_WASTE_FAILED = 70001;
    const UPDATE_WASTE_FAILED = 70002;
    const GET_WASTE_DETAIL_FAILED = 70003;
    const GET_WASTE_LIST_FAILED = 70004;
    const WASTE_NOT_EXISTS = 70005;

    /*************Pickman 回收员代码**********/
    const INSERT_PICKMAN_FAILED = 80001;
    const UPDATE_PICKMAN_FAILED = 80002;
    const GET_PICKMAN_FAILED = 80003;
    const GET_PICKMAN_LIST_FAILED = 80004;
    const PICKMAN_NOT_EXISTS = 80005;
    const PICKMAN_FORBIDDEN = 80006;
    const PICKMAN_WAIT_AUDIT = 80007;
    const PICKMAN_EXISTS = 80008;


    /*************Area 地区代码**********/
    const INSERT_AREA_FAILED = 90001;
    const DELETE_AREA_FAILED = 90002;
    const SET_AREA_ADMIN_FAILED = 90003;
    const AREA_NOT_EXISTS = 90004;
    const AREA_LEVEL_LOW = 90005;

    const SET_INDEX_PAGE_INFO_FAILED = 100001;
    const DEL_INDEX_PAGE_INFO_FAILED = 100002;


    /*************Depot 回收点代码**********/
    const INSERT_DEPOT_FAILED = 110001;
    const UPDATE_DEPOT_FAILED = 110002;
    const GET_DEPOT_FAILED = 110003;
    const GET_DEPOT_LIST_FAILED = 110004;
    const DEPOT_NOT_EXISTS = 110005;
    const DEPOT_FORBIDDEN = 110006;
    const DEPOT_WAIT_AUDIT = 110007;
    const DEPOT_EXISTS = 110008;
    const DEPOT_NOT_LOGIN = 110009;




    // -----------------------error_message分割线-----------------------------------

    static $ERROR_MSG = [
        self::OK => 'OK',
        self::NET_ERROR => '网络错误',
        self::PARAM_ERROR => '参数错误',
        self::LOGIN_EXPIRED => '登录过期 请重新登录',
        self::UNAUTHORIZED => '未登录 请先登录',
        self::ACCOUNT_PASSWORD_ERROR => '账号或密码错误',
        self::USER_LOCKED => '账户已被封停',

        self::LOST_CONNECT => '失去DB连接',
        self::CONFIG_ERROR => 'DB配置错误',
        self::SQL_QUERY_FAILED => 'DB执行失败',

        self::GET_USER_INFO_FAILED => '获取用户信息失败',
        self::INSERT_USER_RECORD_FAILED => '插入用户记录失败',
        self::UPDATE_USER_RECORD_FAILED => '更新用户信息失败',
        self::GET_USER_INFO_EMPTY => '未找到该用户信息',
        self::WX_OAUTH_FAILED => '微信授权失败',
        self::WX_GET_INFO_FAILED => '获取微信信息失败',
        self::GET_LOCATION_FAILED => '获取位置信息失败',
        self::LOCK_USER_FAILED => '禁用用户失败',
        self::UNLOCK_USER_FAILED => '解除禁用失败',

        self::ADMIN_EXIST => '手机号或用户名已存在',

        self::INSERT_FEEDBACK_FAILED => '保存反馈信息失败',

        self::INSERT_ADDRESS_FAILED => '新增地址信息失败',
        self::UPDATE_ADDRESS_FAILED => '更新地址信息失败',
        self::SER_ADDRESS_DEFAULT_FAILED => '设置默认地址失败',

        self::INSERT_ORDER_FAILED => '创建订单失败',
        self::UPDATE_ORDER_FAILED => '更新订单失败',
        self::GET_ORDER_DETAIL_FAILED => '获取订单详情失败',
        self::GET_ORDER_LIST_FAILED => '获取订单列表失败',
        self::TAKE_ORDER_FAILED => '接单失败',
        self::ORDER_NOT_WAIT => '订单非待取货状态',
        self::ORDER_NOT_GOING => '订单非取货中状态',
        self::ORDER_NOT_RECYCLING => '订单非回收中状态',
        self::ORDER_CANT_REPEAT_TAKE => '已经接过该订单',
        self::CANCEL_ORDER_FAILED => '取消订单失败',
        self::CANT_SET_ORDER => '您不能操作该订单',
        self::ORDER_NOT_EXIST => '订单不存在',

        self::WASTE_NOT_EXISTS => '物品类型不存在',

        self::INSERT_PICKMAN_FAILED => '新建取货员失败',
        self::PICKMAN_NOT_EXISTS => '取货员不存在',
        self::PICKMAN_FORBIDDEN => '账号已封禁',
        self::PICKMAN_WAIT_AUDIT => '信息正在审核中',
        self::PICKMAN_EXISTS => '已经注册过回收员',

        self::INSERT_AREA_FAILED => '新增地区失败',
        self::DELETE_AREA_FAILED => '删除地区失败',
        self::SET_AREA_ADMIN_FAILED => '变更地区管理员失败',
        self::AREA_NOT_EXISTS => '地区不存在',
        self::AREA_LEVEL_LOW => '该地区级别低 无法添加下级',

        self::SET_INDEX_PAGE_INFO_FAILED => '设置展示信息数据失败',
        self::DEL_INDEX_PAGE_INFO_FAILED => '删除展示信息数据失败',

        self::INSERT_DEPOT_FAILED => '新建回收点失败',
        self::DEPOT_NOT_EXISTS => '回收点不存在',
        self::DEPOT_FORBIDDEN => '账号已封禁',
        self::DEPOT_WAIT_AUDIT => '信息正在审核中',
        self::DEPOT_EXISTS => '已经注册过回收点',
        self::DEPOT_NOT_LOGIN => '请先登录'
    ];
}