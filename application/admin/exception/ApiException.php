<?php
/**
 * Author: zhangs
 * Date: 2019/6/27
 * Time: 16:55
 */

namespace app\api\exception;
class ApiException extends \RuntimeException
{
    public function __construct(array $apiMsgCode, Throwable $previous = null)
    {
        $code = $apiMsgCode[0];
        $message = $apiMsgCode[1];
        parent::__construct($message, $code, $previous);
    }

}