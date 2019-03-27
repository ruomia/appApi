<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
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

    }

    /**
     * 管理员登录
     */
    public function login()
    {
        $url = Request::get('url', 'index/index');
        // echo $url;
        if (Request::isPost()) {
            $username = Request::post('username');
            $password = Request::post('password');

        }
        return $this->view->fetch();
    }

    /**
     * 注销登录 
     */
    public function logout()
    {

    }
 }