<?php
namespace app\admin\controller\auth;

use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use app\common\controller\Backend;
use Tree;
use think\facade\Request;
use think\facade\Validate;
/**
 * 角色组
 * 
 * @icon fa fa-group
 * @remark 角色组可以有多个，角色有上下级层级关系，如果子角色有角色组和管理员的权限则可以派生属于自己组别下级的角色组或管理员
 */
class Group extends Backend
{
    // 当前登录管理员所有自组别
    protected $middleware = [
        'Admin',
        'AuthCheck' => ['except' => ['getAuthRule']]
    ];
    public function initialize()
    {
        parent::initialize();

        // $list = AuthRule::field('id,title as name,pid')->select()->toArray();
        // $group = AuthGroup::select();
        // return 
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
        if(Request::has('table')) 
        {
            $list = AuthGroup::select()->toArray();
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
     * 添加
     */
    public function add()
    {
        if (Request::isPost())
        {
            $params = Request::post();
            // $params['authids'] = json_decode($params['authids'], true);
            // return $params['authids'];
            $params['rules'] = implode(',', $params['rules']);
            $validate = Validate::make([
                'pid|父级权限' => 'require',
                'name|名称' => 'require',
                'rules|权限' => 'require',
            ]);
            $result = $validate->check($params);
            if(!$result) {
                $this->error($validate->getError());
            }
            AuthGroup::create($params);
            $this->success();
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($id = NULL) 
    {
        $row = AuthGroup::get($id);
        if (!$row)
            $this->error('No Results were found');
        // 超级管理员组不允许修改
        if ($row->rules === '*') {
            return false;
        }
        if (Request::isPost())
        {
            $params = Request::post();
            // 父节点不能是它自身和它的子节点
            
            // $params['rules']
            $parentModel = AuthGroup::get($params['pid']);
            if (!$parentModel)
            {
                $this->error('The parent group can not found');
            }
            // 父级别的规则节点
            $parentRules = explode(',', $parentModel->rules);
            // 当前组别的规则节点
            $currentRules = $row->rules;
            $rules = $params['rules'];
            // 如果父组不是超级管理员则需要过滤规则节点，不能超过父组别的权限
            $rules = in_array('*', $parentRules) ? $rules : array_intersect($parentRules, $rules);
            
            $params['rules'] = implode(',', $rules);
            if ($params)
            {
                $row->save($params);
                $this->success();
            }
            $this->error();
            return;


        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
    public function getAuthRule($id = NULL)
    {
        // 修改权限时，需要知道管理员所在权限组所拥有的权限
        $row = AuthGroup::get($id);
        $checkedId = [];
        if($row) {
            $checkedId = explode(',', $row->rules);
            foreach($checkedId as $k => $v) {
                $checkedId[$k] = (int)$v;
            }
        }
        $list = AuthRule::field('id,title,pid');
        // 获取的权限必须是上一级权限组所拥有的权限
        if($pid = Request::request('pid')) {
            $upperAuth = AuthGroup::get($pid);
            $ruleIds = $upperAuth->rules;
            if($ruleIds != '*') {
                $list = $list->where('id', 'in', $ruleIds);
            }
        }
        $list = $list->select()->toArray();
        // $group = AuthGroup::select();
        // return 
        $total = count($list);
        $result =  [
            'code' => 0,
            'msg'  => 'ok',
            'data' => [
                'list' => $list,
                "checkedId" => $checkedId
            ],
        ];
        return json($result);
    }

}