<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Libs\Common;
class IndexController extends Controller
{
    /**
     * 后台管理首页
     */
    public function index(Request $request){
        $uid = $request->session()->get('uid');
        $username = $request->session()->get('username');
        return view('index',compact('num','uid','username'));
    }

    /**
     * 首页欢迎页面
     */
    public function welcome(){
        return view('welcome',array());
    }

}
 ?>