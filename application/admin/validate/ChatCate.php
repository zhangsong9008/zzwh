<?php
/**
 * Author: zhangs
 * Date: 2019/8/19
 * Time: 10:37
 */

namespace app\api\validate;

use think\Validate;

class ChatCate extends Validate
{
    protected $rule = [
        'name|名称' => 'require|unique:chat_tag',

    ];

    protected $message = [
        'name.require' => '分类名称不能为空',
        'name.unique' => '名称已存在请不要重复添加',
        'id.require' => '分类id不能为空',
    ];

    protected $scene = [
        'add' => ['name'],
        'update' => ['name','id'],
        'delete' => ['id']
    ];
}