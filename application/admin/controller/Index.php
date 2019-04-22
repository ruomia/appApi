<?php
namespace app\admin\controller;

use app\common\controller\Backend;
use think\Validate;
use think\facade\Request;
use app\facade\Admin;
/**
 * 后台首页
 * @internal
 */

 class Index extends Backend
 {
    protected $middleware = [
        'Admin' => ['except' => ['login','upload']]
    ];
    // protected $noNeedLogin = ['login'];
    /**
     * 后台首页
     */
    public function index()
    {
        // $controllername = strtolower($this->request->controller());
        // $actionname = strtolower($this->request->action());

        // $path =  $controllername . '/1' . $actionname;
        // return $path;
        // 左侧菜单
        list($menulist, $navlist, $fixdmenu, $referermenu) = Admin::getSidebar([
            'dashboard' => 'hot',
            'addon'     => ['new','red','badge'],
            'auth/rule' => ('Menu'),
            'general'   => ['new','purple'],
        ]);
        $this->view->assign('menulist', $menulist);
        $this->view->assign('navlist', $navlist);
        // $this->view->assign('fixedmenu', $fixedmenu);
        $this->view->assign('referermenu', $referermenu);
        return $this->view->fetch();
    }

    /**
     * 管理员登录
     */
    public function login()
    {
        $url = Request::get('url');
        echo $url;
        if (Request::isPost()) {
            $username = Request::post('username');
            $password = Request::post('password');
            $captcha = Request::post('captcha');
            $validate = Validate::make([
                'username|用户名' => 'require|length:3,30',
                'password|密码' => 'require|length:3,30',
                'captcha|验证码'  => 'require|captcha', 
            ]);
            $data = [
                'username' => $username,
                'password' => $password,
                'captcha'  => $captcha,
            ];
            $result = $validate->check($data);
            if (!$result) {
                $this->error($validate->getError());
            }
            $result = Admin::login($username, $password);
            if ($result === true) {
                $this->success('Login successful', $url);
            } 
            else 
            {
                $msg = Admin::getError();
                $msg = $msg ? $msg : ('Username or password is incorrect');
                $this->error($msg);
            }

        }
        return $this->view->fetch();
    }

    /**
     * 注销登录 
     */
    public function logout()
    {

    }

    public function test()
    {
        return $this->view->fetch();
    }

    public function upload()
    {
        $file = Request::file('file');
        // 移动到框架应用根目录/uploads/目录下
        $info = $file->move('uploads');
        if($info){
            // 成功上传后，获取上传信息
            $result =  [
                'code' => 0,
                'msg'  => '',
                'data' => [
                    'src' => '/uploads/' . $info->getSaveName()
                ]
            ];
            return json($result);
        }else{
            $result =  [
                'code' => 1,
                'msg'  => $file->getError(),
                'data' => ''
            ];
            return json($result);
        }
    }
 }
