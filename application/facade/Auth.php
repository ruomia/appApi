<?php
namespace app\facade;
use think\Facade;

class Auth extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\common\library\Auth';
    }
}