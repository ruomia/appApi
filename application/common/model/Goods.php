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

    // 首页搜索
    public static function getSearchList($keyWord)
    {
        $data = self::alias('a')->join('category b', 'a.cat_id = b.id')
            ->where('b.name', 'like', $keyWord . '%')
            ->select();
        return $data;
    }
    public static function getGoodsDetails($goods_id)
    {
        $row = self::alias('a')
                ->field('a.*,group_concat(b.image) path')
                ->where('a.id', $goods_id)
                ->join('goods_img b', 'a.id = b.goods_id')
                ->find();
        if(!$row)
            return false;
        $row['path'] = explode(',', $row['path']);
        return $row;
    }
}