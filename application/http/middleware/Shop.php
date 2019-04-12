<?php

namespace app\http\middleware;

use think\facade\Session;

class Shop
{
    public function handle($request, \Closure $next)
    {   
        // echo "1";
        $shop =Session::get("shop");
        // dump($shop);
        if(!isset($shop)){
            return redirect('/shop_login');
        }
        
        return $next($request);
    }
}
