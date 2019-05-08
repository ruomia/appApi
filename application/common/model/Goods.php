<?php
namespace app\common\Model;

use think\Model;

class Goods extends Model
{
    protected $table = 'goods';

    public static function getShopGoods($params)
    {
        $where = [['shop_id','=',$params['shop_id']]];
        $status = isset($params['status']) ? $params['status'] : '';
        if ($status !== ''){
            $where[] = ['status','=',intval($status)];
            $order = '';
        }
        $sortBy = isset($params['sort_by']) ? $params['sort_by'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'ASC';
        $row = self::where($where)
            ->order($sortBy, $order)
            ->select();
            
        return $row;
    }
}