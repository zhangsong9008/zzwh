<?php
/**
 * Author: zhangs
 * Date: 2019/6/27
 * Time: 16:56
 */


namespace app\api\exception;

use app\api\apiConst\ApiCodeMsg;
use app\api\traits\JsonResponse;
use think\exception\Handle;

class HttpException extends Handle
{
    use JsonResponse;

    public function render(\Exception $e)
    {
        // 参数验证错误
        if ($e instanceof ApiException) {
            $code = $e->getCode();
            $message = $e->getMessage();
        } else {
            $code = $e->getCode();
            if (!$code || $code < 0) {
                $code = ApiCodeMsg::UNKNOWN_ERROR[0];
            }
            $message = $e->getMessage() . '代码行号：' . $e->getLine() ?: ApiCodeMsg::UNKNOWN_ERROR[1];
        }
        $this->jsonData($code, $message, []);
    }
}