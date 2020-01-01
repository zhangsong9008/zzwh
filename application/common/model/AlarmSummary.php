<?php
/**
 * Created by PhpStorm.
 * User: asong
 * Date: 2020/1/1
 * Time: 13:50
 */

namespace app\common\model;


use think\Model;

class AlarmSummary extends Model
{
    public function clear()
    {
        $tb = $this->getTable();
        $sql = " truncate table {$tb} ";
        return self::execute($sql);
    }


    /**
     * 获取汇总统计数据
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData()
    {
        $field = 'sum(place_num) as place_num,
                  sum(black_place_num) as black_place_num,
                  sum(report_place_num) as report_place_num,
                  sum(online_people_num) as online_people_num,
                  sum(ww_alarm_num) as ww_alarm_num,
                  sum(ww_install_num) as ww_install_num,
                  sum(ww_online_num) as ww_online_num,
                  sum(case_num) as case_num,
                  sum(xuncha_num) as xuncha_num,
                  ';
        $list = $this->field($field)->select()->toArray();
        $data = $list[0];

        $result = [
            'place_num' => $data['place_num'],
            'black_place_num' => $data['black_place_num'],
            'report_place_num' => $data['report_place_num'],
            'online_people_num' => $data['online_people_num'],
            'ww_alarm_num' => $data['ww_alarm_num'],
            'ww_install_num' => $data['ww_install_num'],
            'ww_online_num' => $data['ww_online_num'],
            'case_num' => $data['case_num'],
            'xuncha_num' => $data['xuncha_num'],
        ];
        return $result;
    }

    /**
     * add test data
     */
    public function addTest()
    {
        $result = [
            'place_num' => mt_rand(1, 10),
            'black_place_num' => mt_rand(1, 10),
            'report_place_num' => mt_rand(1, 10),
            'online_people_num' => mt_rand(1, 10),
            'ww_alarm_num' => mt_rand(1, 10),
            'ww_install_num' => mt_rand(1, 10),
            'ww_online_num' => mt_rand(1, 10),
            'case_num' => mt_rand(1, 10),
            'xuncha_num' => mt_rand(1, 10),
        ];

        for ($i = 0; $i < 40; $i++) {
            $result['areacode'] = $i;
            $this->insert($result);
        }

    }

}