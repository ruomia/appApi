<?php
namespace app\admin\controller\shop;

use app\common\controller\Backend;
use app\admin\model\Shop;
use think\facade\Request;
class Verify extends Backend
{
    public function index()
    {
        if(Request::has('type'))
        {
            // 获取待审核的商铺记录
            $list = Shop::where('status',2)->select()->toArray();
            $total = count($list);

            $result =  [
                'code' => 0,
                'msg'  => '',
                'count'=>$total,
                'data' => $list
            ];
            return json($result);
        }
        return $this->view->fetch();
    }
}