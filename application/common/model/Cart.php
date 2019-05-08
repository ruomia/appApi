<?php
namespace app\common\model;

use think\Model;

class Cart extends Model 
{
    protected $table  = 'cart';

    protected $autoWriteTimestamp = 'timestamp';
}