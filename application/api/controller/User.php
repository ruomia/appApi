<?php 
namespace app\api\controller;

use app\common\controller\Api;
use think\Validate;
use think\Db;
use app\common\model\User as U;
use think\Request;
class User extends Api 
{
    protected $middleware = [
        'Check' => ['except'=> ['login','register']]
    ];

    /**
     * 获取用户信息
     */
    public function index(Request $req)
    {
        $row = U::field('id,nickname,avatar,collection,follow')
                ->where('id', $req->user_id)->find();
        if(!$row)
            return error('No Results were found');
        
        return success($row);
    }
    /**
     * 收藏商品
     */
    public function collection(Request $req)
    {
        $row = U::field('id')->with(['goods'=>function($query){
            $query->field('goods_name,logo,price');
        }])->get($req->user_id);
        // trace(Request::jwt->id, 'info user_id');
        if(!$row)
            return error('No Results were found');
        
        return success($row);
    }
    /**
     * 关注店铺
     */
    public function follow(Request $req)
    {
        $row = U::field('id')->with(['shop'=>function($query){
            $query->field('shop_name,image');
        }])->get($req->user_id);
        // trace(Request::jwt->id, 'info user_id');
        if(!$row)
            return error('No Results were found');
        
        return success($row);
    }

    /**
     * 获取用户信息
     */
    public function info()
    {
        $row = U::get($this->request->user_id);

        if(!$row) 
            return error('该用户不存在');
            
        return success($row);
    }
    
}