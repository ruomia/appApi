<?php
namespace app\admin\controller\shop;

use app\common\controller\Backend;
use think\facade\Request;
use app\admin\model\Shop as ShopModel;
class Shop extends Backend
{
    public function index()
    {
        if(Request::has('type'))
        {
            $list = ShopModel::where('status','in','-1,0,1')->select()->toArray();
        
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

    /**
     * 禁用
     */
    public function disabled($id = NULL)
    {
        $row = ShopModel::get($id);   
        if(!$row)
            $this->error('No Results were found.');
       
            
        $row->status = -1;
        $row->save();
        $this->success();
    }
    /**
     * 删除
     */
    public function del($id = NULL)
    {
        if($id)
        {
            ShopModel::destroy($id);
            $this->success();
        }
        $this->error();
    }
}