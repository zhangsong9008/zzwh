<?php
/**
 * Author: zhangs
 * Date: 2019/6/14
 * Time: 11:28
 */

namespace app\admin\validate;

use think\Validate;

class BaseKnowledge extends Validate
{
    protected $rule = [
        'name|标题' => 'require|unique:base_knowledge',
        'content|内容' => 'require',
    ];

    protected $message = [
        'name.require' => '标题不能为空',
        'content.require' => '内容不能为空',
        'name.unique' => '标题已存在请不要重复添加',
    ];

    protected $scene = [
        'add' => ['name', 'content'],
        'update' => ['name', 'content']
    ];
}