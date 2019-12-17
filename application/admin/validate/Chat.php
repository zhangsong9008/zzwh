<?php
/**
 * Author: zhangs
 * Date: 2019/7/8
 * Time: 10:18
 */

namespace app\api\validate;

use think\Validate;

class Chat extends Validate
{
    protected $rule = [
        'chat_id|会话id' => 'require',
    ];

    protected $message = [
        'chat_id.require' => '会话id不能为空',
    ];

    protected $scene = [
        'index' => ['chat_id'],
    ];
}