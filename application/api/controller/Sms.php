<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Cache;
use think\Request;
use think\facade\Validate;
use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
class Sms extends Controller 
{
    protected $config = [
        'accessKeyId'    => 'LTAIBwmZ58DmKXCr',
        'accessKeySecret' => 'fKoMy431s90RjAtf66Q3qmaQiSIaHX',
    ];
    public function send(Request $req)
    {// 判断手机号码是否正确
        if ($req->mobile && !Validate::regex($req->mobile, "^1\d{10}$"))
        {
            return error('Mobile is incorrent');
        }
        // 生成6位随机数
        $code = rand(100000,999999);
        // 缓存时的名字
        $name = 'code-'.$req->mobile;
        // 把随机数缓存起来（60秒）
        Cache::set($name, $code, 120);
        
        $client  = new Client($this->config);
        $sendSms = new SendSms;
        $sendSms->setPhoneNumbers($req->mobile);
        $sendSms->setSignName('胡亚鹏');
        $sendSms->setTemplateCode('SMS_135430007');
        $sendSms->setTemplateParam(['code' => $code]);
        $sendSms->setOutId('demo');

        $client->execute($sendSms);
        return success();
    }
}