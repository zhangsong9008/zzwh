<?php
/**
 * Author: zhangs
 * Date: 2019/6/14
 * Time: 15:37
 */

namespace app\common\logic;


class BaseLogic
{
    protected $msg = '';

    protected $id = '';

    public function getMsg()
    {
        return $this->msg;
    }

    public function getId()
    {
        return $this->id;
    }


}