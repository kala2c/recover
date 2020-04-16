<?php


namespace app\common\exception;


use app\common\error\ErrorCode;
use think\Exception;
use think\exception\DbException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;

class ExceptionHandle extends Handle
{
    public function render(\Exception $e)
    {
        if ($e instanceof ValidateException) {
            return result(ErrorCode::PARAM_ERROR, 400, $e->getError(), []);
        } elseif ($e instanceof ApiException) {
            return result($e->getCode(), $e->getStatus(), $e->getMessage(), []);
        } elseif ($e instanceof DataException) {
            return result($e->getCode(), $e->getStatus(), $e->getMessage(), []);
        }
        elseif ($e instanceof HttpException) {
            return result(ErrorCode::NET_ERROR, $e->getStatusCode(), $e->getMessage(), []);
        } elseif ($e instanceof DbException) {
            dump($e->getMessage());
        }
//        return error();
        return parent::render($e);
    }

}