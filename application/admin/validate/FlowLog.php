<?php
/**
 * Author: zhangs
 * Date: 2019/7/3
 * Time: 14:06
 */

namespace app\api\validate;

use think\Validate;

class FlowLog extends Validate
{
    protected $rule = [
        'wid|工单id' => 'require',
        'from_id|转接人id' => 'require',
        'to_id|接收人id' => 'require',
        'mark|备注' => 'require',

    ];

    protected $message = [
        'wid.require' => '工单id不能为空',
        'from_id.require' => '转接人id不能为空',
        'to_id.require' => '接收人id不能为空',
        'mark.require' => '备注不能为空',
    ];

    protected $scene = [
        'transfer' => ['wid', 'from_id', 'to_id', 'mark'],
        'finish' => ['wid'],
    ];
}