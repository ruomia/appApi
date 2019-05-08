<?php
namespace app\api\controller;

use think\Controller;
use app\common\model\Shop as ShopModel;
use app\common\model\Goods;
use think\facade\Request;
class Shop extends Controller 
{
    public function goods($goods_id = NULL)
    {
        $params = Request::get();
        if(!isset($params['shop_id']))
            return error('数据错误');
        $result = Goods::getShopGoods($params);
     
        return success($result);
    }
}