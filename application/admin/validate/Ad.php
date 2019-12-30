<?php
/**
 * Author: zhangs
 * Date: 2019/9/3
 * Time: 9:23
 */

namespace app\api\validate;

use think\Validate;

class Ad extends Validate
{
    protected $rule = [
        'name|广告名称' => 'require',
        'description|广告文案' => 'require',
        'img|广告素材' => 'require',
        'link|连接地址' => 'require',
        'id|id' => 'require'
    ];

    protected $message = [
        'name.require' => '广告名称不能为空',
        'description.require' => '广告文案不能为空',
        'img.require' => '广告素材不能为空',
        'link.require' => '链接地址不能为空',
        'id.require' => '参数不能为空',

    ];

    protected $scene = [
        'add' => ['name', 'description', 'img', 'link'],
        'update' => ['name', 'description', 'img', 'id', 'link'],
        'delete' => ['id']
    ];
}