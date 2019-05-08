<?php
namespace app\common\controller;

use think\Controller;

class Api extends Controller
{
    protected $middleware = ['Check'];

    public $user_id;
    public function initialize()
    {
        // if($this->request->jwt) {
        // }
        // $this->user_id = $this->request->jwt->id;
        trace($this->request->param('jwt'), 'info apiJwt');

    }
}