<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

error_reporting(E_ALL & ~E_NOTICE);
// 应用公共文件
// 应用公共文件
function dump2($var, $exit = true)
{
    if (is_bool($var) || is_null($var)) {
        var_dump($var);
    } else {
        echo "<pre style='padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;'>" . print_r($var,
                true) . "</pre>";
    }
    if ($exit) {
        die;
    }
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{

    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }

    return $tree;
}

/**
 * 二维数组根据某一个字段值排序
 * @param $arrUsers
 * @param $field
 * @param int $sort
 * @return mixed
 */
function multi_array_sort($arrUsers, $field, $sort = SORT_DESC)
{
    $arr = array();
    foreach ($arrUsers as $key => $value) {
        $arr[$key] = $value[$field];
    }
    array_multisort($arr, $sort, $arrUsers);
    return $arrUsers;
}

/**
 * 删除目录以及其下的文件
 * @param $directory
 * @return bool
 */
function removeDir($directory)
{
    if (false == is_dir($directory)) {
        return false;
    }

    $handle = opendir($directory);
    while (false !== ($file = readdir($handle))) {
        if ('.' != $file && '..' != $file) {
            is_dir("$directory/$file") ? removeDir("$directory/$file") : @unlink("$directory/$file");
        }
    }

    if (readdir($handle) == false) {
        closedir($handle);
        rmdir($directory);
    }
    return true;
}

/**
 * 判断当前用户是否可以访问某个链接
 * @param $rule
 * @return bool
 */
function auth($rule)
{
    $user = session('admin_user');
    $access = $user['access'];
    return isSuperAdmin() || in_array($rule, $access);
}

/**
 * 判断是否超级管理员
 * @param string $roleId
 * @return bool
 */
function isSuperAdmin($roleId = '')
{
    $superRoleId = config('admin.super_role_id');
    if ($roleId) {
        return $roleId == $superRoleId;
    }
    $user = session('admin_user');
    return in_array($superRoleId, $user['arrRoleId']);
}

function makePassword($pwd, $salt = '')
{
    $salt = $salt ?: config('system.password_salt');
    return md5($pwd . $salt);
}

#判断是否本地环境
function is_local()
{
    $host = \think\facade\Request::server('HTTP_HOST');
    return (false !== stripos($host, 'local') || gethostbyname($host)=='10.109.24.63');
}
