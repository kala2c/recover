<?php


namespace app\common\exception;
use think\Exception;
use Throwable;

class ApiException extends Exception
{
    public $code = 10000;
    public $message = "";
    public $status = 400;

    function __construct($message = "", $code = 0, $status = 400)
    {
        $this->status = $status;
        $this->message = $message;
        $this->code = $code;
//        parent::__construct($message, $code, $previous);
    }
}