<?php
namespace app\admin\controller;

use think\Controller;
use think\Validate;
use think\facade\Request;
use app\facade\Admin;
/**
 * 后台首页
 * @internal
 */

 class Index extends controller
 {
    /**
     * 后台首页
     */
    public function index()
    {
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
 }
