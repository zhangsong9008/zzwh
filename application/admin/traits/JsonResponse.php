<?php
/**
 * Created by PhpStorm.
 * User: zhangs
 * Date: 2019/4/23
 * Time: 9:51
 */

namespace app\admin\traits;
trait JsonResponse
{
    /**
     * @param $code
     * @param $msg
     * @param array $data
     * @param $column array 表头
     */
    public function jsonData($code, $msg, $data = [],$column=[])
    {
        $output = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        echo json_encode($output, 256);
        exit;
    }

    /**
     * 返回成功的调用
     * @param $data
     * @param $column array
     */
    public function jsonSuccess($data,$column=[])
    {
        $this->jsonData(0, 'SUCCESS', $data,$column);
    }
}