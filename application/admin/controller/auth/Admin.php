<?php
namespace app\admin\controller\auth;

use app\common\controller\Backend;
use app\admin\model\Admin as AdminModel;
use think\facade\Request;
use app\admin\model\AuthGroup;
use Tree;
use app\admin\validate\Admin as AdminValidate;
class Admin extends Backend
{

    public function initialize()
    {

        $groupList = AuthGroup::select()->toArray();
        Tree::instance()->init($groupList);
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
        $groupName = [];
        foreach ($this->rulelist as $k => $v)
        {
            $groupName[$v['id']] = $v['name'];
        }
        $this->view->assign('groupdata', $groupName);
    }
    public function index()
    {
   
        if(Request::has('type')) {
           
            $total = AdminModel::count();
   
            $list = AdminModel::with('group')
                        ->hidden(['group'=>['id','rules','pid','status','pivot']])
                        ->select()
                        ->toArray();
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
     * 添加
     */
    public function add()
    {
        if (Request::isPost()) {
            
    
            $params = Request::post("row/a");
            if($params) {
                $params['password'] = md5($params['password']);
                $params['avatar'] = '/assets/img/avatar.png';
                $result = $this->validate($params, 'app\admin\validate\Admin.add');
                if (true !== $result) {
                    $this->error($result);
                }
                $admin = AdminModel::create($params);
                
                $group = Request::post('group');
                // 将获取的字符串转换成数组
                $group = explode(',', $group);

                $admin->group()->saveAll($group);

                $this->success();
            }
        }
        return $this->view->fetch();

    }

    /**
     * 编辑
     */
    public function edit($id = NULL)
    {
        $row = AdminModel::get($id);
        if (!$row) 
            $this->error('No Results were found');
        
        if (Request::isPost())
        {
            $params = Request::post("row/a");
            if ($params)
            {
                if ($params['password'])
                {
                    $params['password'] = md5($params['password']);
                }
                else 
                {
                    unset($params['password']);
                }
                $validate = new AdminValidate;
        
                $validate->rule([
                    'username' => 'require|max:50|unique:admin,username,' . $row->id,
                    'email'    => 'require|email|unique:admin,email,' . $row->id
                ]);
                if(!$validate->scene('edit')->check($params)) {
                    $this->error($validate->getError());
                }
                $row->save($params);
                $group = Request::post('group');
                // 将获取的字符串转换成数组
                $group = explode(',', $group);
                $row->group()->detach();
                $row->group()->saveAll($group);
                $this->success();
            }
        }
        $groups = $row->group;
    
        $groupids = [];
        foreach ($groups as $k => $v) 
        {
            $groupids[] = $v['id'];
        }
        $this->view->assign("row", $row);
        $this->view->assign("groupids", $groupids);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {   
        if ($ids)
        {
            // 因为只有相当高的权限才能管理管理员，除了不能删除超级管理员，不需要判断是否云泉删除管理员
            // 排除超级管理员
            if(strpos($ids, '1') !== false) {
                $this->error('不能删除超级管理员');
            }
            if (mb_strlen($ids) > 1) {
                $list = AdminModel::where('id', 'in', $ids)->select();
                foreach($list as $k => $v) {
                    $v->group()->detach();
                    $v->delete();
                }
            } else {
                $row = AdminModel::get($ids);
                // 先删除中间表shuju
                $row->group()->detach();
                $row->delete();
            }
            $this->success();

            
        }
        $this->error();
    }
}