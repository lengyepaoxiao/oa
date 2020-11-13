<?php

namespace App\Http\Middleware;
use App\Libs\Common;
use Closure;
use Illuminate\Session\TokenMismatchException; //TokenMismatchException;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
        //设置不验证CSRF的链接
        $notCsrfArray = array(
            "App\Http\Controllers\MediaController@uploadimg",
            );
        $controller = $request->route()->getAction()['controller'];
        if( in_array($controller,$notCsrfArray)){
            return $next($request);
        }

        if ($request->method() == 'GET')
        {
            return $next($request);
        }

        if($request->method() == 'POST' || $this->tokensMatch($request))
        {
            return $next($request);
        }

        Common::outputJson(0,"CsrfToken验证失败");
        //throw new TokenMismatchException;
    }
}
