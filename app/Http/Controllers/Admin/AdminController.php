<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Libs\Common;
use App\Http\Controllers\Controller;
use App\Models\Admin\AdminModel;

class AdminController extends Controller
{
    /**
     * 用户列表
     */
    public function lists(Request $request){
        return view('admin.admin_lists');
    }
    /**
     * 后台首页数据获取
     */
    public function getLists(Request $request){
        $input = $request->all();
        $page = empty($input['page']) ? 1 : $input['page'];
        $pagesize = 40;
        $first = ($page-1) * $pagesize;

        $index = new AdminModel;
        $total = $index -> counts();
        $data = $index -> lists($first, $pagesize);

        $externArray = ['page'=>$page, 'pagesize'=>$pagesize, 'total'=>$total];
        Common::outputJson(1,'','',$data, $externArray);
        exit();
    }
    /**
     * 后台管理添加页面
     */
    public function add(Request $request){
        return view('admin.admin_add');
    }
    /**
     * 后台管理添加页面
     */
    public function create(Request $request){
        $input = $request->all();
        $password = trim($input['password']);
        $password = md5(md5($password));
        $data = [
            'username' => $input['username'],
            'password' => $password,
            'isdel'  => 0,
            'created_at' => date('Y-m-d H:i:s', time(0))
        ];
        $admin = new AdminModel;

        //判断是否存在
        if($admin->checkNameExist($data['username'])){
            Common::outputJson(2, '用户已存在!');
            exit();
        }

        if($admin->inserts($data)){
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
       return view('admin.admin_edit',compact('id'));
    }
    /**
     * 首页编辑获取数据操作
     */
    public function getEdit(Request $request){
        $input = $request->all();
        //获取一条小程序信息
        $index = new AdminModel;
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
        $password = trim($input['password']);
        $password = md5(md5($password));
        $data = [
            'password' => $password,
            'updated_at' => date('Y-m-d H:i:s', time(0))
        ];
        $index = new AdminModel;
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
        $index = new AdminModel;
        if($index->deletes($id, $isdel)){
            Common::outputJson(1,'删除成功');
        }else{
            Common::outputJson(0, '删除失败');
        }
        exit();
    }

}
 ?>