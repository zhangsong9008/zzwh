<?php
/**
 * Author: zhangs
 * Date: 2019/7/10
 * Time: 14:34
 */

namespace app\api\validate;

use think\Validate;

class Group extends Validate
{
    protected $rule = [
        'name|分组名称' => 'require|unique:group',
        'id|分组id' => 'require',
    ];

    protected $message = [
        'name.require' => '分组名称不能为空',
        'name.unique' => '分组名称已存在',
        'id.require' => '分组id不能为空',

    ];

    protected $scene = [
        'add' => ['name'],
        'update' => ['name', 'id'],
    ];
}