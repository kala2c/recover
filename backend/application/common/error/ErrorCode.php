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

    /*************Feedback 错误代码**********/
    const INSERT_FEEDBACK_FAILED = 40001;


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

        self::INSERT_FEEDBACK_FAILED => '保存反馈信息失败'
    ];
}