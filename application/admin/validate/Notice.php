<?php
/**
 * Author: zhangs
 * Date: 2019/7/11
 * Time: 10:23
 */

namespace app\api\validate;

use think\Validate;

class Notice extends Validate
{
    protected $rule = [
        'title|标题' => 'require',
        'content|内容' => 'require',
        'type|发送类型' => 'require',
    ];

    protected $message = [
        'title.require' => '标题不能为空',
        'content.require' => '内容不能为空',
        'type.require' => '发送类型不能为空',
    ];

    protected $scene = [
        'add' => ['title', 'content', 'type'],
    ];
}