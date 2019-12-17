<?php
/**
 * Author: zhangs
 * Date: 2019/7/12
 * Time: 11:25
 */

namespace app\api\validate;

use think\Validate;

class ClientBlacklist extends Validate
{
    protected $rule = [
        'client_id|客户id' => 'require|unique:client_blacklist',
    ];

    protected $message = [
        'client_id.require' => '客户id不能为空',
        'client_id.unique' => '客户id已存在不要重复添加',
    ];

    protected $scene = [
        'add' => ['client_id'],
    ];
}