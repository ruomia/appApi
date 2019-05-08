<?php
namespace app\common\model;

use think\Model;

/**
 * 会员模型
 */
class User extends Model
{
    protected $table = 'user';

    public function goods()
    {
        return $this->belongsToMany('goods', 'user_collection');
    }

    public function shop()
    {
        return $this->belongsToMany('shop', 'user_follow');
    }
}