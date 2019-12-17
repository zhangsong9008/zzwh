<?php

namespace app\common\controller;

class ViewHelper
{
    public static function makeSearch()
    {
        $data = request()->get(false);

        $data = array_filter($data);
        if (isset($data['s'])) {
            unset($data['s']);
        }

        $rows = isset($data['limit']) ? $data['limit'] : 20;
        $page = isset($data['page']) ? $data['page'] : 1;
        $order = isset($data['order']) ?: '';

        unset($data['limit']);
        unset($data['page']);
        unset($data['order']);
        return [$data, $rows, $order,$page];
    }

    public static function makePage($data,$total,$limit,$page)
    {
        return ['data' => $data, 'total' => $total, 'per_page' => $limit, 'current_page' => $page, 'last_page' => 1, 'code' => 1];
    }

    public static function bootStrapPage($list)
    {
        return $list;
    }
}