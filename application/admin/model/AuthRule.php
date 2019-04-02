<?php
namespace app\admin\model;

use think\Model;

class AuthRule extends Model
{
    protected $table = 'auth_rule';
    // public $icon;
    // protected static function init()
    // {
    //     self::afterWrite(function ($row) {
    //         Cache::rm('__menu__');
    //     });
    // }

    // public function getTitleAttr($value, $data)
    // {
    //     return ($value);
    // }
    public function getStatusTextAttr($value,$data)
    {
        $status = [0=>'禁用',1=>'正常'];
        return $status[$data['status']];
    }
}