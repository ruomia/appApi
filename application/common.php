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
function ok($data=[])
{
    $result = [
        'status' => 0,
        'msg'    => 'ok',
        'data' => $data
    ];
    return json_encode($result);
}
function success($data=[])
{
    $result = [
        'code' => 0,
        'msg'    => 'ok',
        'data' => $data
    ];
    return json_encode($result);
}
function error($msg, $data=[])
{
    $result = [
        'code' => -1,
        'msg'  => $msg,
        'data' => $data      
    ];
    return json_encode($result);

}
function generateTree($array){
    //第一步 构造数据
    $items = array();
    foreach($array as $value){
        // $value['son'] = []; 
        $items[$value['id']] = $value;
    }
    //第二部 遍历数据 生成树状结构
    $tree = array();
    foreach($items as $key => $value){
        // 如果pid这个节点存在
        if(isset($items[$value['pid']])){
            if(isset($items[$value['pid']]['children'])) {
                $items[$value['pid']]['children'][] = &$items[$key];
            } else {
                $items[$value['pid']]['children'] = [&$items[$key]];
            }
           
        }else{
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}
