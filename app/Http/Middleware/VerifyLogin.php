<?php

namespace App\Http\Middleware;

use Closure;
use app\Libs\Common;

class VerifyLogin
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
        $_token = config("session.oauth_token");
        if(!$request->session()->has($_token)){
            if($request->getMethod() == 'POST'){
                Common::outputJson(0,'登陆超时',url("/"));
            }else{
                return redirect("login");
            }
        }
        return $next($request);
    }
}
