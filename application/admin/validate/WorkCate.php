<?php
/**
 * Author: zhangs
 * Date: 2019/7/8
 * Time: 11:14
 */

namespace app\api\validate;

use think\Validate;

class WorkCate extends Validate
{
    protected $rule = [
        'name|分类名称' => 'require|unique:work_cate',
    ];

    protected $message = [
        'name.require' => '分类名称不能为空',
        'name.unique' => '分类名称已存在请不要重复添加',
    ];

    protected $scene = [
        'add' => ['name'],
        'update' => ['name', 'id'],
        'delete' => ['id'],
    ];
}