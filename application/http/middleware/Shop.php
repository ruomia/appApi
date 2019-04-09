<?php

namespace app\http\middleware;

use think\facade\Session;

class Shop
{
    public function handle($request, \Closure $next)
    {   
        // echo "1";
        $shop =Session::get("shop");
        if(!isset($shop)){
            return redirect('/shop/login');
        }
        
        return $next($request);
    }
}
