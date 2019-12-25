<?php
/**
 * Created by PhpStorm.
 * User: asong
 * Date: 2019/12/25
 * Time: 23:11
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\facade\Env;

class File extends AdminBase
{

    /**
     * 上传导入模版
     */
    public function upload(){
        $upload = new \Plupload();
        $upload->upload(['xls','xlsx']);
    }

    /**
     * 导入模板下载
     * @return \think\response\Download
     */
    public function download(){
        $name = $this->request->param('tpl_name');
        if(!$name){
            $this->jsonData(-1,'参数错误');
        }
        $file =  Env::get('root_path').'public/tpl/'.$name;
        //dump($file);exit;

        if(!file_exists($file)){
            $this->jsonData(-1,'模板文件不存在');
        }

        return download($file,$name);
    }




}