<?php

namespace app\common\library;

use app\common\model\User;
use think\Db;
use think\facade\Validate;
use think\Config;

class Auth
{
    protected static $instance = null;
    protected $_error = '';

    /**
     * 
     * @param array $options 参数
     * @return Auth
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance))
        {
            self::$instance = new static($options);
        }

        return self::$instance;
    }
    public function register($username, $password, $email='', $mobile='', $extend=[])
    {
        // 检测用户名或邮箱、手机号是否存在
        if (User::getByUsername($username))
        {
            $this->setError('Usernmae already exist');
            return FALSE;
        }
        if ($email && User::getByEmail($email))
        {
            $this->setError('Emaili already exist');
            return FALSE;
        }
        if ($mobile && User::getByMobile($mobile))
        {
            $this->setError('Mobile already exist');
            return FALSE;
        }
        $data = [
            'username' => $username,
            'password' => $password,
            'email'    => $email,
            'mobile'   => $mobile,
        ];
        $data['passwrod'] = md5($data['password']);

        $user = User::create($data);
        return $user;
    }
    /**
     * 用户登录
     * 
     * @param string   $account  账号,用户名、邮箱、手机号
     * @param string   $password 密码
     * @return boolean
     */
    public function login($account, $password)
    {
        $field = Validate::is($account, 'email') ? 'email' : (Validate::regex($account, '/^1\d{10}$/') ? 'mobile' : 'username');
        $user = User::get([$field => $account]);
        if (!$user)
        {
            $this->setError('Account is incorrect');
            return FALSE;
        }
        // if ($user->status != 'normal')
        // {
        //     $this->setError('Account is locked');
        //     return FALSE;
        // }
        if ($user->password != md5($password))
        {
            $this->setError('Password is incorrect');
            return FALSE;
        }
        return $user;

    }
    /**
     * 设置错误信息
     * 
     * @param $error 错误信息
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
        return $this->_error ? $this->_error : '';
    }
}