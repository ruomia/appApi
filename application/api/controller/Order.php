<?php
namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Order as O;
use think\facade\Request;

class Order extends Api
{
    /**
     * 获取订单详细信息
     * 
     * @param $user_id
     */
    public function details($order_id = NULL)
    {
        $row = O::alias('a')
        ->field("a.id,number,b.name,tel,province,city,area,address,
            c.goods_id,d.goods_name,c.name value_name,a.real_payment,
            d.shop_id,e.shop_name,d.logo,
            a.create_time,a.payment_time,a.delivery_time")
        ->where('a.id', $order_id)
        ->join('user_address b', 'a.user_address_id = b.id')
        ->join('goods_sku c', 'a.sku_id = c.id')
        ->join('goods d', 'c.goods_id = d.id')
        ->join('shop e', 'd.shop_id = e.id')
        // ->group('c.goods_id')
        ->find();

        return success($row);
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

    /**
     * 获取订单列表
     */
    public function getNumber()
    {
        $result = O::where('status', '<', 6)
            ->where('user_id', $this->request->user_id)
            ->field("status,count(id) count")
            ->group('status')
            ->select();
        return success($result);
    }
}