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
//    const = 1008; 单点登录

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

    /*************Waste 废品代码**********/
    const INSERT_WASTE_FAILED = 70001;
    const UPDATE_WASTE_FAILED = 70002;
    const GET_WASTE_DETAIL_FAILED = 70003;
    const GET_WASTE_LIST_FAILED = 70004;
    const WASTE_NOT_EXISTS = 70005;




    // -----------------------error_message分割线-----------------------------------

    static $ERROR_MSG = [
        self::OK => 'OK',
        self::NET_ERROR => '网络错误',
        self::PARAM_ERROR => '参数错误',
        self::LOGIN_EXPIRED => '登录过期 请重新登录',
        self::UNAUTHORIZED => '未登录 请先登录',

        self::LOST_CONNECT => '失去DB连接',
        self::CONFIG_ERROR => 'DB配置错误',
        self::SQL_QUERY_FAILED => 'DB执行失败',

        self::GET_USER_INFO_FAILED => '获取用户信息失败',
        self::INSERT_USER_RECORD_FAILED => '插入用户记录失败',
        self::UPDATE_USER_RECORD_FAILED => '更新用户信息失败',
        self::GET_USER_INFO_EMPTY => '未找到该用户信息',
        self::WX_OAUTH_FAILED => '微信授权失败',
        self::WX_GET_INFO_FAILED => '获取微信信息失败',

        self::INSERT_FEEDBACK_FAILED => '保存反馈信息失败',

        self::INSERT_ADDRESS_FAILED => '新增地址信息失败',
        self::UPDATE_ADDRESS_FAILED => '更新地址信息失败',
        self::SER_ADDRESS_DEFAULT_FAILED => '设置默认地址失败',

        self::INSERT_ORDER_FAILED => '创建订单失败',
        self::UPDATE_ORDER_FAILED => '更新订单失败',
        self::GET_ORDER_DETAIL_FAILED => '获取订单详情失败',
        self::GET_ORDER_LIST_FAILED => '获取订单列表失败',

        self::WASTE_NOT_EXISTS => '物品类型不存在',
    ];
}