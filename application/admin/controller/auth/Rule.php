<?php
namespace app\admin\controller\auth;

use think\Controller;
use app\admin\model\AuthRule;
use think\facade\Request;
use think\facade\Validate;
class Rule extends Controller
{   
    public function index()
    {
        // $rule = AuthRule::select();
        if(Request::isAjax() && Request::has('limit')) {
            $limit = Request::request('limit');
            $page = Request::request('page');
            $total = AuthRule::count();
            // $rule = AuthRule::paginate(10);
            $rule = AuthRule::limit($limit)
                            ->page($page)
                            ->select()
                            ->toArray();
            // return $rule;
            // dump($rule);die;
            $result =  [
                'code' => 0,
                'msg'  => '',
                'count'=>$total,
                'data' => $rule
            ];
            return json($result);
        }
        // $this->view->assign('data', $rule);

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
                // $this->success();
            }
            $this->error();
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();

    }
    public function test() {
        $limit = $this->request->request('limit');
        $page = $this->request->request('page');
        // $rule = AuthRule::paginate(10);
        $rule = AuthRule::limit($limit)->page($page)->select();
        // return $rule;
        // dump($rule);die;
        return [
            'code' => 0,
            'msg'  => '',
            'count'=>16,
            'data' => $rule
        ];
    }
}