<?php
namespace app\common\model;

use think\Model;

class Order extends Model 
{
    protected $table  = 'order';

    protected $autoWriteTimestamp = 'timestamp';
}