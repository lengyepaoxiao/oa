<?php namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Closure;

class AdminAccess
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //判断是否为超级管理员
        if($request->session()->get(config('app.supperAuthKey')) === 1){
            return $next($request);
        }

        $permit = $this->getPermission($request);
        $access = $request->session()->get(config('app.access'));

        // 如果此方法为不需要权限，可以直接使用
        $notCheckAction = config('app.notCheckAction');
        if(is_string($notCheckAction)){
            //如果免检控制器是一个字符串
            $notCheckAction = explode(',',$notCheckAction);
        }
        foreach($notCheckAction as $v){
            if(strtolower($permit) == strtolower($v)){
                return $next($request);
            }
        }

        //如果分配了角色
        if($access){
            $access = $access['action'];
            // 只要有一个有权限，就可以进入这个请求
            foreach ($access as $v) {
                if ($v == strtolower($permit)) {
                    return $next($request);
                }
            }
        }
        echo "没有权限，请联系管理员";exit;
    }

    // 获取当前路由需要的权限
    public  function getPermission(Request $request)
    {
        $action = $request->route()->getActionName();
        list($class, $action) = explode('@', $action);
        $controller = substr(strrchr($class,'\\'),1);
        $controller = substr($controller,0,-10);
        return $controller;
    }
}