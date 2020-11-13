<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Libs\Common;
use App\Models\PublicModel;
class PublicController extends Controller{
    /**
        后台登陆页面
     */
    public function login(Request $request){
        $input = $request->all();
        $data = [];
        return view('login',compact('data'));
    }
    /**
      后台登陆页面数据验证
     */
    public function check(Request $request){
        $input = $request->all();
        $username = trim($input['username']);
        $password = trim($input['password']);
        $admin = new PublicModel;
        $admin_data = $admin->getInfo($username);
        //判断用户是否存在
        if(!$admin_data){
            Common::outputJson(0,'用户不存在');
            exit();
        }
        $password = md5(md5($password));
        //判断用户密码是否存在
        if($password != $admin_data['password']){
            Common::outputJson(0,'用户密码错误请重试');
            exit();
        }
        $stat = md5(md5($admin_data['id']));
        $request->session()->put('uid',$admin_data['id']);
        $request->session()->put('username',$username);
        $request->session()->put(config('session.oauth_token'),$stat);
        $request->session()->save();
        Common::outputJson(1,'登陆成功');
        exit();
    }
    /**
      后台登出页面
     */
    public function quit(Request $request){
        $request->session()->forget('uid');
        $request->session()->forget('username');
        $request->session()->forget(config('session.oauth_token'));
        return redirect('login');
    }
}
?>