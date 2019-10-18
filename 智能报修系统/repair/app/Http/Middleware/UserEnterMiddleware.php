<?php

namespace App\Http\Middleware;

use App\Common;
use Closure;
use App\Users;

class UserEnterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $openid = $request -> input('openid');
        $user = new Users();
        $result = $user->useTest($openid);
        if($result){
            return Common::dataFormat($result=1,$remind = '登录成功',$data = null);
        }else{
            return $next($request);
        }

    }
}
