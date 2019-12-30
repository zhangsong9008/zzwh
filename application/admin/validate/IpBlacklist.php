<?php
/**
 * Author: zhangs
 * Date: 2019/7/12
 * Time: 11:25
 */

namespace app\api\validate;

use think\Validate;

class IpBlacklist extends Validate
{
    protected $rule = [
        'start_ip|起始ip段' => 'require|ip',
        'end_ip|结束ip段' => 'require|ip|egt:start_ip',
        // 'expire_date' => 'require|dateFormat:Y-m-d'
    ];

    protected $message = [
        'start_ip.require' => '起始ip不能为空',
        'start_ip.ip' => '起始ip格式不正确',
        'end_ip.require' => '结束ip不能为空',
        'end_ip.ip' => '结束ip格式不正确',
        'end_ip.egt' => '结束ip必须大于等于起始ip',
        'expire_date.require' => '失效日期不能为空',
        'expire_date.dateFormat' => '失效日期格式不正确',
    ];

    protected $scene = [
        'add' => ['start_ip', 'end_ip', 'expire_date']
    ];
}