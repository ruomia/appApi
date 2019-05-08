<?php
namespace app\api\controller;

use app\common\controller\Api;
use think\facade\Request;
use app\common\model\Cart as C;
class Cart extends Api
{
    /**
     * 获取购物车信息
     * 
     * @param $user_id
     */
    public function index()
    {
        // image 应该是sku的image，先用goods的logo
        $row = C::alias('a')
                ->field('a.id,b.goods_name,a.price,a.count,b.logo image,
                    c.name attribute,d.shop_name')
                ->where('user_id', $this->request->user_id)
                ->join('goods b', 'a.goods_id = b.id')
                ->join('goods_sku c', 'a.sku_id = c.id')
                ->join('shop d', 'b.shop_id = d.id')
                ->select();
        if(!$row)
            return error('No Results were found');
        
        return ok($row);
    }
    /**
     * 添加购物车
     * 
     */
    public function save()
    {
        $params = Request::post();
        $validate = new \app\api\validate\Cart;

        if(!$validate->check($params)) {
            return error($validate->getError());
        }
        $params['user_id'] = $this->request->user_id;
        C::create($params);
        return success();        
    }

    /**
     * 删除记录
     * 
     * @param $ids
     */
    public function del($ids = NULL)
    {
        if ($ids) {
            // $pk = $this->model->getPk();
            // $adminIds = $this->getDataLimitAdminIds();
            // if (is_array($adminIds)) {
            //     $count = $this->model->where($this->dataLimitField, 'in', $adminIds);
            // }
            $list = C::where('id', 'in', $ids)->select();
            $count = 0;
            foreach ($list as $k => $v) {
                $count += $v->delete();
            }
            if ($count) {
                return ok();
            } else {
                return error('No rows were deleted');
            }
        }
       return error('Parameter ids can not be empty');
    }
}