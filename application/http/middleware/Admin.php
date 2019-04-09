<?php

namespace app\http\middleware;

use think\facade\Session;
class Admin
{
    public function handle($request, \Closure $next)
    {
        if (!Session::has('admin')) {
            return redirect('index/login');
        }
        return $next($request);

    }
}
