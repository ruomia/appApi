<?php
namespace app\admin\controller\auth;

use think\Controller;
use app\common\controller\Backend;
use app\admin\model\AuthRule;
use think\facade\Request;
use think\facade\Validate;
use Tree;
class Rule extends Backend
{   
    protected $rulelist = [];
    public function initialize()
    {
        $rulelist =  AuthRule::select()->toArray();
        Tree::instance()->init($rulelist);
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $ruledata = [0 => '无'];
        foreach ($this->rulelist as $k => $v)
        {
            if (!$v['ismenu'])
                continue;
            $ruledata[$v['id']] = $v['title'];
        }
        $this->view->assign('ruledata', $ruledata);
    }
    public function index()
    {
        if(Request::isAjax() && Request::has('type')) {
           
            $total = AuthRule::count();
   
            $rule = AuthRule::select()
                            ->toArray();
        
            $result =  [
                'code' => 0,
                'msg'  => '',
                'count'=>$total,
                'data' => $rule
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
            $params = Request::post("row/a");
            if ($params)
            {
                if (!$params['ismenu'] && !$params['pid'])
                {
                    $this->error('The non-menu rule must have parent');
                }
                $validate = Validate::make([
                    'name|规则' => 'require|unique:AuthRule,name',
                ]);
                $result = $validate->check($params);
                if(!$result) {
                    $this->error($validate->getError());
                }
                $result = AuthRule::create($params);
                // 如果下一级规则，就使用批量添加
                if($children = Request::post("children/a")) 
                {
                    $childrenList = [];
                    $params['ismenu'] = 0;
                    $params['pid'] = $result->id;
                    $params['icon'] = 'fa fa-circle-o';
                    foreach($children as $k => $v) 
                    {
                        if ($v === 'on') {
                            $params['name'] = $result->name . '/' . $k;
                            $params['title'] = ucfirst($k);

                            $childrenList[] = $params;
                        }
                    }
                    // AuthRule::create($childrenList);
                    $authRule = new AuthRule;
                    $authRule->saveAll($childrenList);
                }
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }
    public function edit($id = NULL)
    {
        // $row = $this->model->get(['id' => $ids]);
        $row = AuthRule::get($id);

        if(!$row)
            $this->error('No Results were found');
        if (Request::isPost())
        {
            $params = Request::post();
            // dump($params);die;
            if($params)
            {
                if(count($params) == 1) {
                    $row->ismenu = $params['ismenu'];
                    $row->save();
                    $this->success();
                }
                if(!$params['ismenu'] && !$params['pid'])
                {
                    $this->error('The non-menu rule must have parent');
                }

                // 这里需要针对name做唯一验证
                $validate = Validate::make([
                    'name|规则' => 'require|unique:AuthRule,name,' . $row->id,
                ]);
                $result = $validate->check($params);
                if(!$result) {
                    $this->error($validate->getError());
                }
                AuthRule::where('id',$row->id)->update($params);
                $this->success();
            }
            $this->error();
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();

    }
    
    /**
     * 删除
     */
    public function del($ids = "")
    {
        if($ids)
        {
            // $delIds = [];
            // foreach (explode(',', $ids) as $k => $v)
            // {
            //     $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, TRUE));
            // }
            // dump($ids);
            // $delIds = array_unique($delIds);
            // dump($delIds);
        //     $count = AuthRule::where('id', 'in', $delIds)->delete();
            // $count =AuthRule::destroy($ids);
            $count = AuthRule::where('id', $ids)
                            ->whereOr('pid', $ids)
                            ->delete();
            if ($count)
            {
                $this->success();
            }
        }
        $this->error();
    }
}