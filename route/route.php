<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});
Route::post('api/login', 'api/user/login');
Route::post('api/register', 'api/user/register');
Route::get('hello/:name', 'index/hello');
Route::group('api', function (){

})->middleware(app\http\middleware\Check::class)
  ->allowCrossDomain();
return [

];
