<?php
namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Order as O;
use think\facade\Request;

class Order extends Api
{
    /**
     * 获取订单信息
     * 
     * @param $user_id
     */
    public function index($user_id)
    {
        $row = O::alias('a')
        ->field("a.id,number,b.name,tel,province,city,area,address,
            c.goods_id,d.goods_name,c.name value_name,a.real_payment,
            d.shop_id,e.shop_name,
            a.create_time,a.payment_time,a.delivery_time")
        ->where('a.user_id',$user_id)
        ->join('user_address b', 'a.user_address_id = b.id')
        ->join('goods_sku c', 'a.sku_id = c.id')
        ->join('goods d', 'c.goods_id = d.id')
        ->join('shop e', 'd.shop_id = e.id')
        // ->group('c.goods_id')
        ->find();

        return ok($row);
    }

    /**
     * 添加订单
     */
    public function save()
    {
        if(Request::isPost())
        {
            $params = Request::post();
            if($params)
            {
                $validate = new \app\api\validate\Order;
                if(!$validate->check($params)){
                    return error($validate->getError());
                }
                $params['number'] = time();
                $params['status'] = 1;
                $params['user_id'] = $this->request->user_id;
                O::create($params);
                return success();
            }
        }
    }
}