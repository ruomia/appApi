<?php
namespace app\admin\controller;

use app\common\controller\Backend;
use app\admin\model\Category as Cate;
use think\facade\Request;
use Tree;
use think\facade\Validate;
class Category extends Backend
{
    public function initialize()
    {
        $list =  Cate::select()->toArray();
        Tree::instance()->init($list);
        $this->list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $listData = [0 => '无'];
        foreach ($this->list as $k => $v)
        {
            $listData[$v['id']] = $v['name'];
        }
        $this->view->assign('listData', $listData);
    }
    public function index()
    {
        if(Request::has('type')){
            $list = Cate::select()->toArray();
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
            $params = Request::post("row/a");
            if ($params)
            {
                $validate = Validate::make([
                    'name|规则' => 'require|unique:Category,name',
                ]);
                $result = $validate->check($params);
                if(!$result) {
                    $this->error($validate->getError());
                }
                $result = Cate::create($params);
                // 如果下一级规则，就使用批量添加
                
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();
    }

    public function edit($id = NULL)
    {
        $row = Cate::get($id);
        if(!$row)
            $this->error('No Results were found');
        if(Request::isPost()) {
            $params = Request::post("row/a");
            if ($params)
            {
                $validate = Validate::make([
                    'name|规则' => 'require|unique:Category,name,' . $row->id,
                ]);
                $result = $validate->check($params);
                if(!$result) {
                    $this->error($validate->getError());
                }
                $row->save($params);
                // 如果下一级规则，就使用批量添加
                $this->success();
            }
            $this->error();
        }
        $this->view->assign('row', $row);
        return $this->view->fetch();
    }

    public function del($id = NULL)
    {
        if($id)
        {
            $count = Cate::where('id', $id)
                        ->whereOr('pid', $id)
                        ->delete();
            if ($count)
            {
                $this->success();
            }
        }
        $this->error();
    }
}