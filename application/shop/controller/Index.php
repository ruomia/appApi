<?php
namespace app\shop\controller;

use think\Controller;
class Index extends Controller
{
    public function index(){
        return $this->fetch();
    }   
    public function index_home(){
        return $this->fetch('index_home');
    }   
}