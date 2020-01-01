<?php
/**
 * Created by PhpStorm.
 * User: asong
 * Date: 2019/12/25
 * Time: 23:41
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;

class Import extends AdminBase
{

    public function execute()
    {
        $file = $this->request->param('file');
        if (!$file) {
            $this->jsonData(-1, '参数错误');
        }
        if (!file_exists($file)) {
            $this->jsonData(-1, '文件不存在');
        }
        $this->jsonSuccess([]);
    }
}