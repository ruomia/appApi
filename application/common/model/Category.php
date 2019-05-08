<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{
    protected $table = 'category';

    public static function getTree()
    {
        $data = self::select()->toArray();
        // 转换成树形数据
        $result = generateTree($data);
        return $result;
    }
}