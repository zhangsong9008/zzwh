<?php
/**
 * Author: zhangs
 * Date: 2019/7/3
 * Time: 14:06
 */

namespace app\api\validate;

use think\Validate;

class Flow extends Validate
{
    protected $rule = [
        'name|用户名' => 'require',
        'description|工单描述' => 'require',
        'cate_id|分类' => 'require',
        'priority_level|优先级' => 'in:1,2,3,4',
        'id|工单id' => 'require',
        'deadline' => 'require'
    ];

    protected $message = [
        'name.require' => '工单名称不能为空',
        'description.require' => '工单描述不能为空',
        'cate_id.require' => '工单分类不能为空',
        'priority_level.in' => '优先级值不正确',
        'id.require' => '工单id不能为空',
        'deadline.require' => "截至时间不能为空"
    ];

    protected $scene = [
        'create' => ['name', 'description', 'cate_id', 'priority_level', 'deadline'],
        'ignore' => ['id'],
        'transfer' => ['id'],
        'finish' => ['id'],
    ];
}