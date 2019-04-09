<?php

namespace app\shop\model;
use think\facade\Session;

use think\Model;

class Shop extends Model
{
    protected $pk = 'id';
    protected $_error = '';

    /**
     * 管理员登录
     * 
     * @param  string $useranme 用户名
     * @param  string $password 密码
     * @param  int    $keeptime 有效时长
     * @return boolean
     */
    public function login($username, $password, $keeptime=0)
    {
        $shop = Shop::get(['mobile' => $username]);
        if (!$shop) {
            $this->setError('账户错误');
            return false;
        }
        // if ($shop['s'])
        if ($shop->password != md5($password))
        {
            $this->setError('密码错误');
            return false;
        }
        Session::set("shop", $shop->toArray());
        return true;
    }

        /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return Auth
     */
    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->_error ? ($this->_error) : '';
    }
}
