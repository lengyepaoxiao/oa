<?php

namespace App\Http\Controllers\Customer;
use App\Models\Customer\CustomerModel;
use Illuminate\Http\Request;
use App\Libs\Common;
use App\Http\Controllers\Controller;
use Cache;

class CustomerController extends Controller
{
    /**
     * 用户列表
     */
    public function lists(Request $request){
        return view('customer.customer_lists');
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

        $index = new CustomerModel;
        $total = $index -> counts($search_data);
        $data = $index -> lists($search_data, $first, $pagesize);

        $externArray = ['page'=>$page, 'pagesize'=>$pagesize, 'total'=>$total];
        Common::outputJson(1,'','',$data, $externArray);
        exit();
    }
    /**
     * 后台管理添加页面
     */
    public function add(Request $request){
        return view('customer.customer_add');
    }
    /**
     * 后台管理添加页面
     */
    public function create(Request $request){
        $input = $request->all();
        $name = trim($input['name']);
        $data = [
            'name' => $name,
            'linkman' => trim($input['linkman']),
            'phone' => trim($input['phone']),
            'trade' => trim($input['trade']),
            'address' => trim($input['address']),
            'lng' => trim($input['lng']),
            'lat' => trim($input['lat']),
            'isdel'  => 0,
            'created_at' => date('Y-m-d H:i:s', time(0))
        ];
        $index = new CustomerModel;

        if($index -> checkNameExist($name)){
            Common::outputJson(2, '此客户名称已存在');
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
        return view('customer.customer_edit',compact('id'));
    }
    /**
     * 首页编辑获取数据操作
     */
    public function getEdit(Request $request){
        $input = $request->all();
        //获取一条小程序信息
        $index = new CustomerModel;
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
        $name = trim($input['name']);
        $data = [
            'name' => $name,
            'linkman' => trim($input['linkman']),
            'phone' => trim($input['phone']),
            'trade' => trim($input['trade']),
            'address' => trim($input['address']),
            'lng' => trim($input['lng']),
            'lat' => trim($input['lat']),
            'updated_at' => date('Y-m-d H:i:s', time(0))
        ];
        $index = new CustomerModel;

        if($index -> checkOtherNameExist($id, $name)){
            Common::outputJson(2, '此客户名称已存在');
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
        $index = new CustomerModel;
        if($index->deletes($id, $isdel)){
            Common::outputJson(1,'删除成功');
        }else{
            Common::outputJson(0, '删除失败');
        }
        exit();
    }
}
