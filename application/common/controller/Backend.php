<?php
namespace app\common\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Validate;
use think\facade\Session;
use app\facade\Auth;
/**
 * 后台控制器基类
 */
class Backend extends Controller
{
    protected $middleware = ['Admin','AuthCheck'];

    /**
     * 是否开启Validate验证
     */
    protected $modelValidate = false;
    /**
     * 是否开启模型场景验证
     */
    protected $modelSceneValidate = false;
    /**
     * 无需登录的方法，同时也就不需要鉴权了
     * @var array
     */
    // protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法，但需要登录
     * @var array
     */
    // protected $noNeedRight = [];

    // public function initialize()
    // {
    //     $moduleName = Request::module();
    //     $controllerName = strtolower(Request::controller());
    //     $actionName = strtolower(Request::action());

    //     $path = $controllerName . '/' . $actionName;
    //     // $result = Auth::check($path, Session::get('admin.id'));
    //     // return $result;
    //     if(Auth::check($path, Session::get('admin.id'))) {
    //         $this->error('你没有权限');
    //     }
    // }
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        // $this->request->filter(['strip_tags']);
        if (Request::has('page')) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            // if ($this->request->request('keyField')) {
            //     return $this->selectpage();
            // }

            // list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $page = $this->request->get('page');
            $limit = $this->request->get('limit');
            $total = $this->model
                // ->where($where)
                // ->order($sort, $order)
                ->count();

            $list = $this->model
                // ->where($where)
                // ->order($sort, $order)
                ->page($page)
                ->limit($limit)
                ->select();

            $list = $list->toArray();

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
        if ($this->request->isPost()) {
            $params = $this->request->post();
            if ($params) {
                // if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                //     $params[$this->dataLimitField] = $this->auth->id;
                // }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error('Parameter %s can not be empty', '');
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($id = NULL)
    {
        $row = $this->model->get($id);
        if (!$row)
            $this->error(__('No Results were found'));
        // $adminIds = $this->getDataLimitAdminIds();
        // if (is_array($adminIds)) {
        //     if (!in_array($row[$this->dataLimitField], $adminIds)) {
        //         $this->error(__('You have no permission'));
        //     }
        // }
        if ($this->request->isPost()) {
            $params = $this->request->post();
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error('Parameter %s can not be empty', '');
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            // $pk = $this->model->getPk();
            // $adminIds = $this->getDataLimitAdminIds();
            // if (is_array($adminIds)) {
            //     $count = $this->model->where($this->dataLimitField, 'in', $adminIds);
            // }
            $list = $this->model->where('id', 'in', $ids)->select();
            $count = 0;
            foreach ($list as $k => $v) {
                $count += $v->delete();
            }
            if ($count) {
                $this->success();
            } else {
                $this->error('No rows were deleted');
            }
        }
        $this->error('Parameter %s can not be empty', 'ids');
    }
}
