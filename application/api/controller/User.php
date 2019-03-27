<?php 
namespace app\api\controller;

use think\Controller;
use think\Validate;
use \Firebase\JWT\JWT;

class User extends Controller 
{
    public function login()
    {
        $account = $this->request->request('account');
        $password = $this->request->request('password');
        $rule = [
            'account|账号'   => 'require|length:3,50',
            'password|密码'  => 'require|length:6,30',
        ];

        $data = [
            'account'   => $account,
            'password'  => $password,
        ];
        $validate = new Validate($rule);
        $result = $validate->check($data);
        if (!$result) {
            return json([
                'status'=> -1,
                'data' => [],
                'msg' => $validate->getError()
            ]);
        }
        $user = Db::table('user')->field('id,password')
                  ->where('email', $account)
                  ->find();
        if($user) 
        {
            // 判断密码
            if($user['password'] == md5($password))
            {
                // 把用户的信息保存到令牌（JWT）中，然后把令牌发给前端
                $now = time();
                // 定义令牌中的数据
                $data = [
                    'iat' => $now,
                    'exp' => Env::get('jwt.expire'),
                    'id' => $user['id'],
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
                return error('密码不正确！');
            }
        }
        else
        {
            return error('用户名不存在');
        }
        
    }
    public function register()
    {
        $username = $this->request->request('username');
        $password = $this->request->request('password');
        $validate = new Validate([
                'email|账号' => 'require|min:6|max:18|unique:users',
                'password|密码' => 'require|min:6|max:18',

            ]);
        $data = [
            'email' => $username,
            'password' => $password
        ];
        if(!$validate->check($data)) {
            // json([
            //     'status'
            // ])
            // return $validate->getError();
            $error = $validate->getError();
            return error($error);
        }
       $data = ur::create([
            'email' => $username,
            'password' => md5($password)
        ]);
        return ok($data);   
    }
}