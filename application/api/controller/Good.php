<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\Goods;
use app\api\model\Goods_attribute;
use app\api\model\Goods_sku;
use think\facade\Request;

class Good extends Controller
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
}