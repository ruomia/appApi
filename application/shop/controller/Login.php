<?php

namespace app\shop\controller;

use think\Controller;
use think\facade\Request;
use app\shop\model\Shop;
use think\Validate;

class Login extends Controller
{   
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $url = Request::get('url');
        echo $url;
        if (Request::isPost()) {
            $mobile = Request::post('mobile');
            $password = Request::post('password');
            $captcha = Request::post('captcha');
            $validate = Validate::make([
                'mobile|账户' => 'require|length:3,30|mobile',
                'password|密码' => 'require|length:3,30',
                'captcha|验证码'  => 'require|captcha', 
            ]);
            $data = [
                'mobile' => $mobile,
                'password' => $password,
                'captcha'  => $captcha,
            ];
            $result = $validate->check($data);
            if (!$result) {
                $this->error($validate->getError());
            }
            $shop = new Shop;
            $result = $shop->login($mobile, $password);
            if ($result === true) {
                $this->success('登陆成功', $url);
            } 
            else 
            {
                $msg = $shop->getError();
                $msg = $msg ? $msg : ('账户或密码出错');
                $this->error($msg);
            }

        }
        return $this->view->fetch('index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
