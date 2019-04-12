<?php
namespace app\shop\controller;

use think\Controller;
use think\facade\Session;

class Index extends Controller
{
    protected $middleware = [
            'Shop' => ['except' => ['index_home'] ]
    ];

    public function index(){
        $shop = Session::get("shop");
        $this->assign('shop_user',$shop);
        return $this->fetch('index');
    }   
    public function index_home(){
        return $this->fetch('index_home');
    }   
}