<?php
/**
 * Author: zhangs
 * Date: 2019/7/8
 * Time: 14:00
 */

namespace app\api\validate;

use think\Validate;

class LeaveMessage extends Validate
{
    protected $rule = [
        'user_name|姓名必填' => 'require',
        'phone|手机号' => 'mobile',
        'content|留言内容' => 'require',
        'reply_mark|处理备注' => 'require',
        'id|留言id' => 'require',
    ];

    protected $message = [
        'user_name.require' => '姓名不能为空',
        'content.require' => '留言内容不能为空',
        'reply_mark.require' => '处理备注不能为空',
        'id.require' => '留言id不能为空',
        'phone.mobile' => '手机号格式不正确',
    ];

    protected $scene = [
        'add' => ['user_name', 'phone', 'content'],
        'reply' => ['id'],
    ];
}