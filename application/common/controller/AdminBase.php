<?php

namespace app\common\controller;


use app\admin\traits\JsonResponse;
use think\App;
use think\Controller;

class AdminBase extends Controller
{
    use JsonResponse;
    public $pageName = '';

    protected $logLogic = '';

    /*    protected $middleware = [
            'Validate' => ['except' => ['index']],
            'AdminAuth' => ['except' => ['login', 'logout', 'index']]
        ];*/

    protected $viewPage = '';

    protected $user = [];

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->view->filter(function ($content) {
            return $this->fetchVersion($content);
        });

        $module = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action = strtolower(request()->action());
        $this->assign([
            'module' => $module,
            'controller' => $controller,
            'action' => $action,
        ]);
        $this->user = session('login_role');

        if (!$this->user) {
            if ($action != 'login') {
                 $this->redirect(url('admin/index/login'));
                  exit;
            }
        }
    }

    //获取页面列表

    /**
     * 为img，css，js添加版本号
     * @param $content
     * @return string|string[]|null
     */
    private function fetchVersion($content)
    {
        if (empty($content)) {
            return $content;
        }
        $version = config('template.version');
        $content = preg_replace('/<script(.*?)src="(.*?)\.(js)"(.*?)><\/script>/i',
            '<script$1src="$2.$3?v=' . $version . '"$4></script>', $content);
        $content = preg_replace('/<img(.*?)src="(.*?)\.(jpg|gif|png|jpeg)"(.*?)>/i',
            '<img$1src="$2.$3?v=' . $version . '"$4>', $content);
        $content = preg_replace('/<link(.*?)href="(.*?)\.(css)"(.*?)>/i', '<link$1href="$2.$3?v=' . $version . '"$4>',
            $content);
        return $content;
    }

    //新增数据

    public function index()
    {
        if ($this->request->isAjax()) {
            try {
                list($map, $rows, $order) = ViewHelper::makeSearch();
                $logic = app((string)'app\\' . request()->module() . '\logic\\' . request()->controller() . 'Logic');
                $list = $logic->getList($map, $rows, $order);

                // dump2($list);exit;

                if (method_exists($this, '_format')) {
                    $list = $this->_format($list);
                }

                // dump2($list);exit;

                return ViewHelper::bootStrapPage($list);
            } catch (\Exception $e) {
                $this->error($e->getMessage(), 'error');
            }
        }
        if (method_exists($this, '_before_index')) {
            $this->_before_index();
        }
        return $this->fetch($this->viewPage);
    }

    //修改数据

    public function add()
    {
        if ($this->request->isAjax()) {
            $res = false;
            try {
                $class = 'app\\' . request()->module() . '\\logic\\' . request()->controller() . 'Logic';
                $data = input();
                $logic = app((string)$class);

                if (class_exists($class) && method_exists($logic, 'add')) {
                    $res = $logic->add($data);
                    $msg = $logic->getMsg();
                    $id = $logic->getId();
                } else {
                    $model = $this->_model();
                    $pk = $model->getPk();
                    $res = $model::create($data);
                    $id = $res->$pk;
                    SysLogLogic::sysLog($model, '', $this->pageName);
                    if ($res->$pk) {
                        $msg = '添加成功';
                    } else {
                        $msg = '添加失败';
                    }
                }

                if (method_exists($this, '_after_add')) {
                    $data['id'] = $id;
                    $this->_after_add($data);
                }

            } catch (\Exception $e) {
                $this->error($e->getMessage(), null, ['file' => $e->getFile(), 'line' => $e->getLine()]);
            }
            $this->returnMsg($res, $msg);
        }

        if (method_exists($this, '_before_add')) {
            $this->_before_add();
        }
        return $this->fetch($this->viewPage);
    }

    //删除数据

    protected function returnMsg($res, $msg = '', $url = '')
    {
        if ($res) {
            $this->success($msg ?: '操作成功', $url);
        } else {
            $this->error($msg ?: '操作失败', $url);
        }
    }

    //修改状态

    public function update()
    {
        if ($this->request->isAjax()) {
            $res = false;
            try {
                $class = 'app\\' . request()->module() . '\\logic\\' . request()->controller() . 'Logic';
                $data = input();
                $logic = app((string)$class);
                if (class_exists($class) && method_exists($logic, 'update')) {
                    $res = $logic->update($data);
                    $msg = $logic->getMsg();
                } else {
                    $model = $this->_model();
                    $res = $model::update($data);
                    SysLogLogic::sysLog($model, '', $this->pageName);
                    if ($res) {
                        $msg = '修改成功';
                    } else {
                        $msg = '修改失败';
                    }
                }
                if (method_exists($this, '_after_update')) {
                    $this->_after_update($data);
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->returnMsg($res, $msg);
        }
        $this->viewPage = 'add';

        /*        if (method_exists($this, '_before_update')) {
                    $this->_before_update();
                }*/

        $model = $this->_model();
        $data = input();

        $pk = $model->getPk();
        if (!$pk || !isset($data[$pk])) {
            $this->returnMsg(false, '参数错误', '');
        }
        $list = $model::where($pk, $data[$pk])->findOrEmpty()->toArray();
        if (method_exists($this, '_before_update')) {
            $list = $this->_before_update($list);
        }

        $this->assign('list', $list);
        return $this->fetch($this->viewPage);
    }

    public function delete()
    {
        $res = false;
        try {
            $class = 'app\\' . request()->module() . '\\logic\\' . request()->controller() . 'Logic';
            $logic = app((string)$class);
            $data = input();

            if (class_exists($class) && method_exists($logic, 'delete')) {
                $res = $logic->delete($data);
                $msg = $logic->getMsg();
            } else {
                $model = $this->_model();
                $pk = $model->getPk();

                if (!$pk || !isset($data[$pk])) {
                    $msg = '参数错误';
                } else {
                    $res = $model::where($pk, $data[$pk])->delete();
                    if ($res) {
                        SysLogLogic::sysLog($model, '', $this->pageName);
                        $msg = '删除成功';
                    } else {
                        $msg = '删除失败';
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->returnMsg($res, $msg);
    }

    public function switchStatus()
    {
        $res = false;
        try {
            $logic = app((string)'app\\' . request()->module() . '\\logic\\' . request()->controller() . 'Logic');
            $res = $logic->switchStatus(input());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        if (method_exists($this, '_before_delete')) {
            $this->_before_delete();
        }
        $this->returnMsg($res, $logic->getMsg());
    }

    public function _empty()
    {
        return '页面不存在';
    }


    /**
     * 魔术方法获取成员变量
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }
}