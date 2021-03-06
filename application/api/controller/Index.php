<?php 
namespace app\api\controller;

use think\Controller;
use \Firebase\JWT\JWT;
use app\facade\Auth;
use think\facade\Env;
use app\common\model\Goods;
use think\facade\Cache;
use think\facade\Validate;
use app\common\model\User;
class Index extends Controller 
{
    public function index()
    {
        return 'aaaa';
    }
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
        $mobile = $this->request->request('mobile');
        $password = $this->request->request('password');
        $code = $this->request->request('code');
        if ($code !== Cache::get('code-'.$mobile))
        {
            return error('验证码错误');
        }
        $validate = Validate::make([
            'mobile' => 'require|mobile|unique:user,mobile',
            'password' => 'require|min:6|max:30'
        ]);
        $data = [
            'mobile' => $mobile,
            'password' => $password
        ];
        if(!$validate->check($data)) {
            return error($validate->getError());
        }
        $data['password'] = md5($data['password']);
        $user = User::create($data);
        $now = time();
        // 定义令牌中的数据
        $data = [
            'iat' => $now,
            'exp' => $now + Env::get('jwt.expire'),
            'id' => $user->id,
        ];
        // 生成令牌
        $jwt = JWT::encode($data, Env::get('jwt.key'));
        // 发给前端
        return success([
            'ACCESS_TOKEN' => $jwt
        ]);
    }
    public function search()
    {
        $keyWord = $this->request->get('keyword');
        $res = Goods::getSearchList($keyWord);
        return success($res);
    }
}