<?php
namespace app\admin\controller\user;

use app\common\controller\Backend;
use app\common\model\User as UserModel;
use think\facade\Request;
class User extends Backend
{
    public function index()
    {
        if(Request::has('page'))
        {
            $page = Request::get('page');
            $limit = Request::get('limit');
            // 分页，因为table插件的分页功能会传page=1&limit=10的参数；
            $list = UserModel::page($page)->limit($limit)->select()->toArray();
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
        $row = UserModel::get($id);   
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
            UserModel::destroy($id);
            $this->success();
        }
        $this->error();
    }
}