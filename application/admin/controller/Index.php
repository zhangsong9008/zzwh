<?php


namespace app\admin\controller;


use app\admin\logic\LoginLogic;
use app\common\controller\AdminBase;

class Index extends AdminBase
{

    public function login()
    {
        if ($this->request->isAjax()) {
            $logic = new LoginLogic();
            $res = $logic->doLogin($this->request->param());
            if (!$res) {
                $this->returnMsg(false, $logic->getMsg());
            }
            $role = session('login_role');
            if ($role == 'admin') {
                $redirect = url('admin/index/index');
            } else {
                $redirect = url('portal/index/index');
            }
            $this->returnMsg(true, 'success', $redirect);
        }
        return $this->fetch();
    }

    public function index()
    {
        return $this->fetch();
    }

    public function logout(){
        session('login_role',null);
    }

    public function upload(){
        return $this->fetch();
    }

}