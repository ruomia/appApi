<?php
namespace app\admin\model;

use think\Model;
use think\Session;

class Admin extends Model
{
    protected $table = 'admin';
    
    public function group()
    {
        return $this->belongsToMany('AuthGroup','auth_group_access', 
            'group_id', 'uid');
    }
}