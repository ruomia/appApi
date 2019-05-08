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
        $row = G::alias('a')
                ->field('a.*,group_concat(b.image) path')
                ->where('a.id', $goods_id)
                ->join('goods_img b', 'a.id = b.goods_id')
                ->find();
        if(!$row)
            return error('No Results were found');
        $row['path'] = explode(',', $row['path']);
        return ok($row);
    }

    /**
     * 商品属性
     * 
     * @param $goods_id 
     */
    public function attribute($goods_id = NULL)
    {
        // 获取属性值ID
        $attr = Sku::field('attribute')->where('goods_id',$goods_id)->select();
        // $attr = $attr->toArray();
        $attrList = [];
        foreach($attr as $k => $v){
            $attrList = array_merge(explode(',',$v['attribute']), $attrList);
        }
        $attrList = array_unique($attrList);
        $row = Value::alias('a')
                    ->field("group_concat(concat(a.id,':',a.value)) value,name")
                    ->where('a.id','in',$attrList)
                    ->join('attribute_name b', 'a.name_id = b.id')
                    ->group('b.id')
                    ->select();

        foreach($row as $k => $v) {
            // $row[$k] = explode(':',explode(',',$v['value']));
            $value = explode(',', $v['value']);
            foreach($value as $k1 => $v1) {
                $value[$k1] = explode(':', $v1);
            }
            $row[$k]['value'] = $value;
        }
        if(!$row)
            return error('No Results were found');
        return ok($row);
    }
}