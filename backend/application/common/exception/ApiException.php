<?php


namespace app\common\exception;
use app\common\error\ErrorCode;
use think\Exception;
use Throwable;

class ApiException extends Exception
{
    public $code = 10000;
    public $message = "";
    public $status = 400;

    function __construct($code = 10000, $status = 400, $message = "")
    {
//        parent::__construct($message, $code);
        $this->status = $status;
        $this->message = $this->getMessageByCode($code) ?? $message;
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 根据业务状态码获取业务错误信息
     * @param $code
     * @return string
     */
    private function getMessageByCode($code)
    {
        return isset(ErrorCode::$ERROR_MSG[$code]) ? ErrorCode::$ERROR_MSG[$code] : '';
    }

}