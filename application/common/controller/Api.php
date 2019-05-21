<?php
namespace app\common\controller;

use think\Controller;

class Api extends Controller
{
    protected $middleware = ['Check'];
}