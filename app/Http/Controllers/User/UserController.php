<?php

namespace App\Http\Controllers\User;
use App\Models\User\UserModel;
use Illuminate\Http\Request;
use App\Libs\Common;
use App\Http\Controllers\Controller;
use Cache;

class UserController extends Controller
{
    /**
     * 用户列表
     */
    public function lists(Request $request){
        return view('user.user_lists');
    }
    /**
     * 后台首页数据获取
     */
    public function getLists(Request $request){
        $input = $request->all();
        $page = empty($input['page']) ? 1 : $input['page'];
        $pagesize = 40;
        $first = ($page-1) * $pagesize;

        $search_data = empty($input['search_data']) ? [] : $input['search_data'];

        $index = new userModel;
        $total = $index -> counts($search_data);
        $data = $index -> lists($search_data, $first, $pagesize);
        foreach($data as $k => $v){
            $data[$k]['money'] = Common::price_format($v['money'] / 100);
        }
        $externArray = ['page'=>$page, 'pagesize'=>$pagesize, 'total'=>$total];
        Common::outputJson(1,'','',$data, $externArray);
        exit();
    }
    /**
     * 后台管理添加页面
     */
    public function add(Request $request){
        return view('user.user_add');
    }
    /**
     * 后台管理添加页面
     */
    public function create(Request $request){
        $input = $request->all();
        $job_no = trim($input['job_no']);
        $data = [
            'username' => trim($input['username']),
            'job_no' => $job_no,
            'position' => trim($input['position']),
            'mobile' => trim($input['mobile']),
            'email' => trim($input['email']),
            'isdel'  => 0,
            'created_at' => date('Y-m-d H:i:s', time(0))
        ];
        $index = new UserModel;

        if($index -> checkNameExist($job_no)){
            Common::outputJson(2, '工号之前已被注册');
            exit();
        }

        if($index->inserts($data)){
            Common::outputJson(1, '添加成功');
        }else{
            Common::outputJson(0, '添加失败');
        }
        exit();
    }
    /**
     * 首页编辑操作
     */
    public function edit(Request $request, $id){
        return view('user.user_edit',compact('id'));
    }
    /**
     * 首页编辑获取数据操作
     */
    public function getEdit(Request $request){
        $input = $request->all();
        //获取一条小程序信息
        $index = new UserModel;
        $id = $input['id'];
        $data = $index->getById($id);
        if($data){
            Common::outputJson(1,'','',$data);
        }else{
            Common::outputJson(0,'没有数据','',[]);
        }
        exit();
    }
    /**
     * 首页编辑保存操作
     */
    public  function updates(Request $request){
        $input = $request->all();
        $id = $input['id'];
        $job_no = trim($input['job_no']);
        $data = [
            'username' => trim($input['username']),
            'job_no' => $job_no,
            'position' => trim($input['position']),
            'mobile' => trim($input['mobile']),
            'email' => trim($input['email']),
            'updated_at' => date('Y-m-d H:i:s', time(0))
        ];
        $index = new UserModel;

        if($index -> checkOtherNameExist($id, $job_no)){
            Common::outputJson(2, '工号之前已被注册');
            exit();
        }

        if($index->updates($id, $data)){
            Common::outputJson(1, '编辑成功');
        }else{
            Common::outputJson(0, '编辑失败');
        }
        exit();
    }
    /**
     * 首页逻辑删除操作
     */
    public function deletes(Request $request){
        $input = $request->all();
        $id = $input['id'];
        $isdel = $input['isdel'];
        $index = new UserModel;
        if($index->deletes($id, $isdel)){
            Common::outputJson(1,'删除成功');
        }else{
            Common::outputJson(0, '删除失败');
        }
        exit();
    }
}
