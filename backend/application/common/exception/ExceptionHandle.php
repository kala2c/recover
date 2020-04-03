<?php


namespace app\common\exception;


use think\Exception;
use think\exception\DbException;
use think\exception\Handle;
use think\exception\ValidateException;

class ExceptionHandle extends Handle
{
    public function render(\Exception $e)
    {
        if ($e instanceof ValidateException) {
            return result(10001, $e->getError(), [], 400);
        } elseif ($e instanceof ApiException) {
            return result($e->code, $e->message, [], $e->status);
        } elseif ($e instanceof DbException) {
            dump($e->getMessage());
        }
//        return error();
        return parent::render($e);
    }

}