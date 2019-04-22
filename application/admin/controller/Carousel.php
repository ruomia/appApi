<?php
namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\model\Carousel as C;
use think\facade\Request;
use think\facade\Validate;
class Carousel extends Backend
{
    protected $model = null;
    public function initialize()
    {
        $this->model = model('app\common\model\Carousel');
    }
}