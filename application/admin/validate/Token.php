<?php

namespace app\api\validate;

use think\Validate;

/**
 * 生成token参数验证器
 */
class Token extends Validate
{

    protected $rule = [
        'request_url' => 'require',
        'timestamp' => 'require|number|checkTime:30',
    ];

    protected $message = [
        'request_url.require' => '请求url不能为空',
        'timestamp.number' => '时间戳格式错误',
    ];

    protected function checkTime($value, $rule, $data = [])
    {
        if (abs($value - time()) > $rule) {
            return '请求时间戳与服务器时间戳异常！' . 'timestamp：' . time();
        }
        return true;
    }
}