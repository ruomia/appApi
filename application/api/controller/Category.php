<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Request;
use app\common\model\Category as C;
use app\common\model\Goods;
class Category extends Controller
{

    public function index()
    {
        $list = C::getTree();
        return success($list);
    }

    /**
     * 分类推荐
     * 
     * @params $cid 分类ID
     */
    public function goods($cid = NULL)
    {   
        $row = Goods::field('id,goods_name,logo,sales_volume,price')
                ->where('cat_id', $cid)
                ->select();
        if(!$row)
            return error('No Results were found');
        
        return ok($row);
    }
}