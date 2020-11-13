<?php

namespace App\Http\Controllers\Task;
use App\Models\Task\TaskModel;
use App\Models\User\UserModel;
use App\Models\Customer\CustomerModel;
use Illuminate\Http\Request;
use App\Libs\Common;
use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * 用户列表
     */
    public function lists(Request $request){
        $uid = $request->session()->get('uid');
        return view('task.task_lists', compact('uid'));
    }
    /**
     * 后台首页数据获取
     */
    public function getLists(Request $request){
        $input = $request->all();
        $page = empty($input['page']) ? 1 : $input['page'];
        $pagesize = 30;
        $first = ($page-1) * $pagesize;

        $search_data = empty($input['search_data']) ? [] : $input['search_data'];

        $index = new TaskModel;
        $total = $index -> counts($search_data);
        $data = $index -> lists($search_data, $first, $pagesize);

        foreach($data as $k => $v){
           if($v['img']){
               $data[$k]['img'] = 'https://api.brgrand.cn/uploads/task/'.$v['img'];
           }else{
               $data[$k]['img'] = '';
           }
        }

        $externArray = ['page'=>$page, 'pagesize'=>$pagesize, 'total'=>$total];
        Common::outputJson(1,'','',$data, $externArray);
        exit();
    }
    /**
     * 后台管理添加页面
     */
    public function add(Request $request){
        $user = new UserModel;
        $customer = new CustomerModel;
        $user_data = $user -> getTaskList();
        $customer_data = $customer -> getCustomerList();
        $type = config('food.type');
        return view('task.task_add', compact('user_data', 'customer_data', 'type'));
    }
    /**
     * 后台管理添加页面
     */
    public function create(Request $request){
        $input = $request->all();
        $customer_id = $input['customer_id'];
        $employee = $input['employee'];
        $customer = new CustomerModel;
        $customer_data = $customer -> getById($customer_id);

        $user = new UserModel;
        $user_data = $user -> getById($employee);

        $data = [
            'customer_id' => $customer_id,
            'name' => trim($customer_data['name']),
            'phone' => trim($customer_data['phone']),
            'employee' => $employee,
            'employee_name' => trim($user_data['username']),
            'type' => $input['type'],
            'job_no' => trim($user_data['job_no']),
            'job_date' => (string)$input['job_date'],
            'stime' => (string)$input['stime'],
            'method' => (string)$input['method'],
            'address' => trim($input['address']),
            'lng' => trim($input['lng']),
            'lat' => trim($input['lat']),
            'content' => trim($input['content']),
            'isdel'  => 0,
            'created_at' => date('Y-m-d H:i:s', time(0))
        ];
        $index = new TaskModel;
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
        $user = new UserModel;
        $user_data = $user -> getTaskList();
        $index = new TaskModel;
        $task_data = $index->getById($id);
        return view('task.task_edit',compact('id', 'user_data', 'task_data'));
    }
    /**
     * 首页编辑获取数据操作
     */
    public function getEdit(Request $request){
        $input = $request->all();
        //获取一条小程序信息
        $index = new TaskModel;
        $id = $input['id'];
        $data = $index->getById($id);
        $type = config('food.type');
        $data['type'] = $type[$data['type']];
        if($data){
            $data['money'] = Common::price_format($data['money'] / 100);
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
        $data = [
            'job_date' => (string)$input['job_date'],
            'stime' => (string)$input['stime'],
            'method' => (string)$input['method'],
            'address' => trim($input['address']),
            'lng' => trim($input['lng']),
            'lat' => trim($input['lat']),
            'content' => trim($input['content']),
            'updated_at' => date('Y-m-d H:i:s', time(0))
        ];
        $index = new TaskModel;
        if($index->updates($id, $data)){
            Common::outputJson(1, '编辑成功');
        }else{
            Common::outputJson(0, '编辑失败');
        }
        exit();
    }
    /**
     * 及时获取模糊查询信息
     */
    public function find(Request $request){
        $input = $request -> all();
        $address = $input['address'];
        if($address){
            $index = new TaskModel;
            $search_data = [
                'address'=> $address
            ];
            $data = $index -> lists($search_data, 0, 10);
            $data = json_decode($data);
            if($data){
                Common::outputJson(1, '','', $data);
            }else{
                Common::outputJson(0);
            }
        }else{
            Common::outputJson(0);
        }
        exit();
    }
    /**
     * 及时获取模糊查询信息
     */
    public function getAddress(Request $request){
        $input = $request -> all();
        $id = $input['id'];
        $customer = new CustomerModel;
        $data = $customer -> getById($id);
        $data = json_decode($data);
        if($data){
            Common::outputJson(1, '', '', $data);
        }else{
            Common::outputJson(0);
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
        $index = new TaskModel;
        if($index->deletes($id, $isdel)){
            Common::outputJson(1,'删除成功');
        }else{
            Common::outputJson(0, '删除失败');
        }
        exit();
    }
    /**
     * 生成表单
     */
    public function makeForm(Request $request, $id, $type){
        $disk = Storage::disk('local');
        $curDayZeroTs = strtotime(date("Y-m-d"),time(0));
        if(!$disk->exists('count.txt')){
            $disk->put('count.txt', '10001&'.$curDayZeroTs);
        }
        $countStr = $disk -> get('count.txt');
        $countArr = explode('&', $countStr);
        $num = $countArr[0];
        $ts = $countArr[1];
        if($curDayZeroTs > $ts){
            $num = 10001;
        }
        $order_no = date('Ymd', $curDayZeroTs) . substr($num, 1);
        $index = new TaskModel;
        $data = $index -> getById($id);
//        $order_no = 100000000000 + $id;
//        $order_no = substr($order_no, 1);
        return view('form.form_'.$type, compact('data', 'order_no', 'num', 'curDayZeroTs'));
    }
    /**
     * 完成表单打印后续操作
     */
    public function createForm(Request $request){
        $input = $request->all();
        $num = $input['num'];
        $curDayZeroTs = $input['curDayZeroTs'];

        $disk = Storage::disk('local');
        if($disk->exists('count.txt')){
            ++$num;
            $disk->put('count.txt', $num.'&'.$curDayZeroTs);
        }
        Common::outputJson(1);
        exit();
    }
}
