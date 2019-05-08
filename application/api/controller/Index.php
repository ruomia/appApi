<?php 
namespace app\api\controller;

use think\Controller;
use \Firebase\JWT\JWT;
use app\facade\Auth;
use think\facade\Env;

class Index extends Controller 
{
    public function login()
    {
        $account = $this->request->request('account');
        $password = $this->request->request('password');
        if (!$account || !$password)
        {
            $this->error('Invalid parameters');
        }
        $ret = Auth::login($account, $password);
        if ($ret)
        {
            // 把用户的信息保存到令牌（JWT）中，然后把令牌发给前端
            $now = time();
            // 定义令牌中的数据
            $data = [
                'iat' => $now,
                'exp' => $now + Env::get('jwt.expire'),
                'id' => $ret['id'],
            ];
            // 生成令牌
            $jwt = JWT::encode($data, Env::get('jwt.key'));
            // 发给前端
            return ok([
                'ACCESS_TOKEN' => $jwt
            ]);

        }
        else 
        {
            return error(Auth::getError());
        }
        
    }
    public function register()
    {
        $username = $this->request->request('username');
        $password = $this->request->request('password');
        $email = $this->request->request('email');
        $mobile = $this->request->request('mobile');
        if (!$username || !$password)
        {
            return error('Invalid parameters');
        }
        if ($email && !Validate::is($email, "email"))
        {
            return error('Email is incorrect');
        }
        if ($mobile && !Validate::regex($mobile, "^1\d{10}$"))
        {
            return error('Mobile is incorrent');
        }
        $ret = Auth::register($username, $password, $email, $mobile, []);
        if ($ret)
        {
            return ok($ret);
        }
        else 
        {
            return error($this->auth->getError());
        }   
    }
}