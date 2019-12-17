<?php
/**
 * Author: zhangs
 * Date: 2019/9/5
 * Time: 17:47
 */

namespace app\api\validate;

use think\Validate;

class Client extends Validate
{
    protected $rule = [
        'name|公司名称' => 'require',
        'sex|性别' => 'require',
        'phone|联系电话' => 'require',
    ];

    protected $message = [
        'name.require' => '公司名称不能为空',
        'sex.require' => '性别不能为空',
        'phone.require' => '联系电话不能为空',

    ];

    protected $scene = [
        'add' => ['name','sex','phone'],
    ];
}