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

// 应用公共文件
function ok($data)
{
    $result = [
        'status' => 0,
        'msg'    => 'ok',
        'data' => $data
    ];
    return json_encode($result);
}
function error($msg, $data=[])
{
    $result = [
        'status' => -1,
        'msg'  => $msg,
        'data' => $data      
    ];
    return json_encode($result);

}
