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
}
