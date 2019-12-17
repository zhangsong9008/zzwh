<?php
/**
 * Author: zhangs
 * Date: 2019/6/14
 * Time: 11:35
 */

namespace app\admin\logic;


use app\common\logic\BaseLogic;
use app\common\model\Admins as AdminModel;
use app\common\model\RoleUser;
use think\captcha\Captcha;

class LoginLogic extends BaseLogic
{

    /**
     * 登录验证
     * @param $data
     * @return array|bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function doLogin($data)
    {

        $adminUser = 'admin';
        $visitUser = 'visit';

        $token = '123456';

        if (!$data['code']) {
            $this->msg = '验证码不能为空';
            return false;
        }
        $captcha = new Captcha();
        if (!$captcha->check($data['code'])) {
            $this->msg = '验证码错误';
            return false;
        }

        if (!$data['user_name'] || !$data['password']) {
            $this->msg = '用户名或密码不能为空';
            return false;
        }
        $userName = $data['user_name'];
        if (!in_array($userName, [$adminUser, $visitUser])) {
            $this->msg = '用户名密码不正确';
            return false;
        }
        if ($data['password'] != $token) {
            $this->msg = '用户名密码不正确';
            return false;
        }

        $role = 'visit';

        if ($userName == $adminUser) {
            $role = 'admin';
        }
        session('login_role', $role);
        $this->msg = '登录成功';
        return true;
    }
}