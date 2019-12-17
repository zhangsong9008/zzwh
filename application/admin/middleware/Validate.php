<?php

namespace app\admin\middleware;

use think\Controller;

class Validate extends Controller
{
    /**
     * 默认返回资源类型
     * @return mixed
     * @throws \Exception
     * @var string $name
     * @var \think\Request $request
     * @var mixed $next
     */
    public function handle($request, \Closure $next, $name)
    {
        //只能ajax请求生效
        if (!$request->isAjax()) {
            //return $next($request);
        }
        //获取当前参数
        $params = $request->param();

        //获取访问模块
        $module = $request->module();
        //获取访问控制器
        $controller = ucfirst($request->controller());
        //获取操作名,用于验证场景scene
        $scene = $request->action();
        $validate = "app\\" . $module . "\\validate\\" . $controller;

        #格式化参数 定义的beforeValidate方法必须为public
        $class = "app\\" . $module . "\\controller\\" . $controller;
        if (method_exists($class, 'beforeValidate')) {
            $params = call_user_func([new $class, 'beforeValidate'], $params);
        }

        //仅当验证器存在时 进行校验
        if (class_exists($validate)) {
            $v = $this->app->validate($validate);
            if ($v->hasScene($scene)) {
                //仅当存在验证场景才校验
                $result = $this->validate($params, $validate . '.' . $scene);
                if (true !== $result) {
                    //校验不通过则直接返回错误信息
                    return json(['code' => 1, 'msg' => $result, 'data' => []]);
                }
            }
        }

        return $next($request);
    }
}