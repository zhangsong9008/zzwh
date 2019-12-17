<?php
/**
 * Author: zhangs
 * Date: 2019/8/26
 * Time: 11:52
 */

namespace app\api\validate;

use think\Validate;

class Agent extends Validate
{
    protected $rule = [
        'name|公司名称' => 'require|unique:ws_app',
        'mail|联系人邮箱' => 'require|unique:ws_app',
        'phone|联系电话' => 'require|unique:ws_app',
        'id|id' => 'require',
    ];

    protected $message = [
        'name.require' => '公司名称不能为空',
        'name.unique' => '公司名称已存在请不要重复添加',
        'mail.require' => '邮箱不能为空',
        'mail.unique' => '邮箱已存在请更换',
        'phone.require' => '联系电话不能为空',
        'phone.unique' => '联系电话已存在请更换其他手机号',
        'id.require' => 'id不能为空',
    ];

    protected $scene = [
        'add' => ['name','mail','phone'],
        'update' => ['name', 'mail','phone','id'],
        'delete' => ['id'],
    ];
}