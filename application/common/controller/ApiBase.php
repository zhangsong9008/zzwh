<?php
/**
 * Author: zhangs
 * Date: 2019/6/20
 * Time: 15:24
 */

namespace app\common\controller;

use app\api\traits\JsonResponse;
use think\App;
use think\Controller;

class ApiBase extends Controller
{

    use JsonResponse;

    protected $extMap = [];

    protected $page;
    protected $limit;
    protected $order = '';
    protected $param = [];
    protected $isH5 = false;


    function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->page = input('page', 1, 'intval');
        $this->limit = min(input('limit', 20, 'intval'), 100);
        $this->order = input('order', '');

        $input = $this->request->param();
        unset($input['/' . $this->request->path()]);
        unset($input['s']);
        $this->param = $input;
        $this->isH5 = is_h5();

    }

    //获取页面列表
    public function index()
    {
        try {
            /*$controller = app((string)'app\\' . request()->module() . '\controller\\' . request()->controller());
            $reflection = new \ReflectionMethod($controller, 'index');
            $doc_str = $reflection->getDocComment();
            $doc = new DocParser();
            $methodDoc = $doc->parse($doc_str);
            $search = isset($methodDoc['search']) ? $methodDoc['search'] : [];

            $map = [];
            foreach ($search as $item) {
                $type = trim($item['type']);
                $name = trim($item['name']);
                $map[$type][] = $name;
            }*/

            list($map, $rows, $order, $page) = ViewHelper::makeSearch();
            //dump2(input());
            $logic = app((string)'app\\' . request()->module() . '\logic\\' . request()->controller() . 'Logic');

            $param = $this->extMap;
            if ($param) {
                $map = $param;
            }


            $list = $logic->getList($map, $rows, $order, $page);

            //$controller = app((string)'app\\' . request()->module() . '\controller\\' . request()->controller());
            if (method_exists($this, '_format')) {
                $list = $this->_format($list);
            }


            $list = ViewHelper::bootStrapPage($list);
            $this->jsonData(0, 'SUCCESS', $list);
        } catch (\Exception $e) {
            $this->jsonData(1004, $e->getMessage());
        }
    }

    //新增数据
    public function add()
    {
        $res = false;
        $msg = '';
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
            $this->jsonData(1005, $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
        }
        $this->jsonData($res ? 0 : 1005, $msg, ['id' => $id]);
    }

    //修改数据
    public function update()
    {
        $res = false;
        $msg = '';
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
            $this->jsonData(1006, $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
        }
        $this->jsonData($res ? 0 : 1006, $msg);
    }

    //删除数据
    public function delete()
    {
        $res = false;
        $msg = '';
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
                        $msg = '删除成功';
                    } else {
                        $msg = '删除失败';
                    }
                }
            }

            if (method_exists($this, '_after_delete')) {
                $this->_after_delete($data);
            }

        } catch (\Exception $e) {
            $this->jsonData(1007, $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
        }
        $this->jsonData($res ? 0 : 1007, $msg);
    }


    function __get($name)
    {
        return $this->$name;
    }

}