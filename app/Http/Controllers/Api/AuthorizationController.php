<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use Dingo\Api\Http\Middleware\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorizationController extends Controller
{
    //生成token
    public function store(AuthorizationRequest $request){
        $name=$request->name;
        filter_var($name,FILTER_VALIDATE_EMAIL) ;
        $creditials['email']=$request->email;
        $creditials['password']=$request->password;
        if (!$token=Auth::guard('api')->attempt($creditials)){
            return $this->response->errorUnauthorized('用户名或密码错误');
        }
        return $this->respnse->array([
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'expires_in'=>Auth::guard('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }
    //刷新token
    public function update(){
        $token=Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }
    //删除token
    public function destroy(){
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }
    //返回token的公共函数
    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
}
