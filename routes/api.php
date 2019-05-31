<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    // 路由前缀
    'prefix' => 'api',
    // 指定空间
    'namespace' => 'App\Http\Controllers\Api',
    // 中间件：transformer数据结构转换   节流   跨域
    'middleware' => ['serializer:array', 'bindings', 'cors']
],function ($api){
    //获取token路由
    $api->post('authorizations','AuthorizationsController@store')
        ->name('api.authorizations.store');
    //刷新token路由
    $api->put('authorizations/current','AuthorizationsController@update')
        ->name('api.authorizations.update');
    //删除token路由
    $api->delete('authorizations/current','AuthorizationsController@delete')
        ->name('api.authorization.delete');
    $api->group([
        'middleware'=>'api.throttle',
        'limit'=>config('api.rate_limits.access.limit'),
        'expires'=>config('api.rate_limits.access.expires'),
    ],function ($api){
        //游客登录接口


        //需要token验证的接口
        $api->group(['middleware'=>'api.auth'],function ($api){
            //当前登录用户信息
            $api->get('user','UserController@me')
                ->name('api.user.me');
        });
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});