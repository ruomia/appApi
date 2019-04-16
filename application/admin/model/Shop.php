<?php
namespace app\admin\model;

use think\Model;

class Shop extends Model
{
    protected $table = 'shop';

    // public function getStatusTextAttr($value,$data)
    // {
    //     $status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
    //     return $status[$data['status']];
    // }
    
}