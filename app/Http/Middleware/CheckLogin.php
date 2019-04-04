<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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
//         防非法登录的验证
        if(!session('user_name')){
            return redirect()->to('http://www.shop.com/login/login');
        }
        return $next($request);
    }
}
