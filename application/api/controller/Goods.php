<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\Goods as G;
use app\api\model\Goods_attribute;
use app\api\model\Goods_sku;
use think\facade\Request;
use app\common\model\Sku;
use app\common\model\Value;

class Goods extends Controller
{
    // 商品搜索
    public function search(){
        $Keyword = Request::get('keyword');
        $price = Request::get('price');
        $goods = Goods::alias('g')
                        ->field('g.*,gs.price');
        $goods->where('goods_name','like',"%{$Keyword}%");
        $goods->leftJoin('goods_sku gs','g.id = gs.goods_id')
                        ->order('gs.price',"$price");

        $goods = $goods->paginate(2);
        return json_encode($goods);
    }

    // 商品详情
    public function content()
    {
        $g_id = Request::get('good_id');
        $goods = Goods::alias('g')
                        ->where('g.id','=',"$g_id")
                        ->find();
        $goods['attribute'] = Goods_attribute::
                        where('goods_id','=',$g_id)
                        ->select();
        $goods['sku'] = Goods_sku::
                        where('goods_id','=',$g_id)
                        ->select();
        // var_dump($goods);
        return json_encode($goods);
    }
    
    /**
     * 商品详情
     * 
     * @param $goods_id
     */
    public function details($goods_id = NULL)
    {
        // $result = G::getGoodsDetails($goods_id);
        // if(!$result) {
        //     return error('没有找到该商品');
        // } 
        // return ok($result);
        $row = G::alias('a')
                ->field('a.*,group_concat(b.image) path')
                ->where('a.id', $goods_id)
                ->join('goods_img b', 'a.id = b.goods_id')
                ->find();
        if(!$row)
            return error('没有找到该商品');
        $row['path'] = explode(',', $row['path']);
        return success($row);
    }

    /**
     * 商品属性
     * 
     * @param $goods_id 
     */
    public function attribute($goods_id = NULL)
    {
        // 获取属性值ID
        $result = Sku::getAttrLists($goods_id);
        if(!$result)
            return error('No Results were found');
        return ok($result);
    }

    public function getSkuInfo($skus = NULL)
    {
        $result = Sku::field('id,goods_id,stock,price,name')
            ->where('attribute', $skus)
            ->find();
        if(!$result)
            return error('No Results were found');
        return success($result);
    }
}