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
    const ACCOUNT_PASSWORD_ERROR = 10009;
//    const = 1008; 单点登录
    const WX_AUTH_ERROR = 10008;

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




    // -----------------------error_message分割线-----------------------------------

    static $ERROR_MSG = [
        self::OK => 'OK',
        self::NET_ERROR => '网络错误',
        self::PARAM_ERROR => '参数错误',
        self::LOGIN_EXPIRED => '登录过期 请重新登录',
        self::UNAUTHORIZED => '未登录 请先登录',
        self::ACCOUNT_PASSWORD_ERROR => '账号或密码错误',

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
        self::PICKMAN_EXISTS => '已经注册过回收员'
    ];
}