<?php

namespace app\http\middleware;

use app\facade\Admin;
use think\facade\Request;
use think\facade\Session;
class AuthCheck
{
    public function handle($request, \Closure $next)
    {
        $controllerName = strtolower(Request::controller());
        $actionName = strtolower(Request::action());

        $path = str_replace('.', '/', $controllerName) . '/' . $actionName;
        // $result = Auth::check($path, Session::get('admin.id'));
        // return $result;
        if(!Admin::check($path, Session::get('admin.id'))) {
            return redirect('index/index');
            // return false;
        }
        return $next($request);
    }
}
