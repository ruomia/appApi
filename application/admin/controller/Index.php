<?php
namespace app\admin\controller;

<<<<<<< HEAD
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
=======
class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }
}
>>>>>>> 60b2d8c02b90b2fc18bf007c7d0e88389df51f30
