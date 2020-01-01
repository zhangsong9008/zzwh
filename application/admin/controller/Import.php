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

    private $config = [
        'alarm_summary' => [
            'A' => ['name' => '区域', 'field' => 'area_code'],
            'B' => ['name' => '场所数量', 'field' => 'place_num'],
            'C' => ['name' => '黑名单数量', 'field' => 'black_place_num'],
            'D' => ['name' => '被举报场所数', 'field' => 'report_place_num'],
            'E' => ['name' => '实时上网人数', 'field' => 'online_people_num'],
            'F' => ['name' => '文网卫士报警场所', 'field' => 'ww_alarm_num'],
            'G' => ['name' => '已安装文网卫士家数', 'field' => 'ww_install_num'],
            'H' => ['name' => '文网卫士在线家数', 'field' => 'ww_install_num'],
            'I' => ['name' => '案件办理数', 'field' => 'case_num'],
            'J' => ['name' => '日常巡查数', 'field' => 'xuncha_num']
        ],
        'place_type' => [
            'A' => ['name' => '场所类型', 'field' => 'type'],
            'B' => ['name' => '场所数量', 'field' => 'num']
        ],
        'place_month' => [
            'A' => ['name' => '2019-1月', 'field' => '201901'],
            'B' => ['name' => '2019-2月', 'field' => '201902'],
            'C' => ['name' => '2019-3月', 'field' => '201903'],
            'D' => ['name' => '2019-4月', 'field' => '201904'],
            'E' => ['name' => '2019-5月', 'field' => '201905'],
            'F' => ['name' => '2019-6月', 'field' => '201906'],
            'G' => ['name' => '2019-7月', 'field' => '201907'],
            'H' => ['name' => '2019-8月', 'field' => '201908'],
            'I' => ['name' => '2019-9月', 'field' => '201909'],
            'J' => ['name' => '2019-10月', 'field' => '201910'],
            'K' => ['name' => '2019-11月', 'field' => '201911'],
            'L' => ['name' => '2019-12月', 'field' => '201912']
        ],
        'content_alarm' => [
            'A' => ['name' => '监管项目', 'field' => 'type'],
            'B' => ['name' => '周一', 'field' => 'w1'],
            'C' => ['name' => '周二', 'field' => 'w2'],
            'D' => ['name' => '周三', 'field' => 'w3'],
            'E' => ['name' => '周四', 'field' => 'w4'],
            'F' => ['name' => '周五', 'field' => 'w5'],
            'G' => ['name' => '周六', 'field' => 'w6'],
            'H' => ['name' => '周日', 'field' => 'w7'],
        ],
        'alarm_music' => [
            'A' => ['name' => '违禁曲目名称', 'field' => 'name']
        ]
    ];


    public function execute()
    {
        $file = $this->request->param('file');
        $type = $this->request->param('type');
        if (!$file || !$type) {
            $this->jsonData(-1, '参数错误');
        }
        if (!file_exists($file)) {
            $this->jsonData(-1, '文件不存在');
        }

        $excel = new \Excel();
        $format = $this->_getFormat($type);
        $checkFormat = $this->_getCheckFormat($type);

        $data = $excel->readUploadFile($file, $format, 2000, $checkFormat);

        dump($data);
        exit;


        if ($data['status']) {
            dump($data);
            exit;
        }

        $this->jsonSuccess($data);
    }


    /**
     * 获取返回数据格式
     * @param $type
     * @return array
     */
    private function _getFormat($type)
    {
        $return = [];
        $data = $this->config[$type];
        foreach ($data as $k => $v) {
            $return[$k] = $v['field'];
        }
        return $return;
    }

    /**
     * 获取检查数据格式数组
     * @param $type
     * @return array
     */
    private function _getCheckFormat($type)
    {
        $return = [];
        $data = $this->config[$type];
        foreach ($data as $k => $v) {
            $return[$k] = $v['name'];
        }
        return $return;
    }
}