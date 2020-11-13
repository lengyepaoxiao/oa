<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class UserModel extends Model
{


    protected $table = 'user';

    protected $fillable = [];

    //获取用户的数量
    public function counts($search_data, $isdel=0){
        $where = '';
        if($search_data){
            $where = $this->getWhere($search_data);
        }
        return $this->whereRaw(DB::Raw('isdel = '.$isdel.$where))->count();
    }

    //获取该用户下小程序列表
    public function lists($search_data, $first, $pagesize, $isdel=0){
        $where = '';
        if($search_data){
            $where = $this->getWhere($search_data);
        }
        return $this->whereRaw(DB::Raw('isdel = '.$isdel.$where))
            ->orderBy('id','asc')
            ->offset($first)
            ->limit($pagesize)
            ->get();
    }

    //  搜索栏语句的拼接
    public function getWhere($datas){
        $sql = '';
        foreach ($datas as $k=>$v){
            if($v != null){
                if($k == 'start'){
                    $sql .= ' and unix_timestamp(created_at) > unix_timestamp("'.$v.'")';
                }
                if($k == 'end'){
                    $sql .= ' and unix_timestamp(created_at) < unix_timestamp("'.$v.'")';
                }
                if($k == 'job_no'){
                    $sql .= ' and  job_no like "%'.$v.'%"';
                }
                if($k == 'username'){
                    $sql .= ' and  username like "%'.$v.'%"';
                }
                if($k == 'mobile'){
                    $sql .= ' and  mobile like "%'.$v.'%"';
                }
                if($k == 'cate_id'){
                    $sql .= ' and  cate_id = '.$v;
                }
                if($k == 'status'){
                    $sql .= ' and  status = '.$v;
                }
                if($k == 'is_top'){
                    $sql .= ' and  is_top = '.$v;
                }
                if($k == 'is_recommend'){
                    $sql .= ' and  is_recommend = '.$v;
                }
                if($k == 'is_ad'){
                    $sql .= ' and  is_ad = '.$v;
                }
            }
        }
        return $sql;
    }
    //数据保存添加
    public function inserts($data){
        return $this->insertGetId($data);
    }
    //判断是否有重复工号存在
    public function checkNameExist($job_no){
        return $this -> where('job_no', $job_no) -> first();
    }
    //判断是否出自己外有工号存在有重复昵称存在
    public function checkOtherNameExist($id, $job_no){
        return $this -> where('id', '!=', $id) -> where('job_no', $job_no) -> first();
    }
    //根据id获取一条数据
    public function getById($id){
        return $this->where('id', $id)->where('isdel', 0)->first();
    }
    //数据编辑保存
    public function updates($id, $data){
        return $this->where('id', $id)->update($data);
    }
    //数据逻辑删除
    public function deletes($id, $isdel){
        $isdel = 1 - $isdel;
        return $this->where('id', $id)->update(['isdel'=>$isdel]);
    }
    //获取所有任务列表
    public function getTaskList($isdel = 0){
        return $this -> select(['id', 'username', 'job_no'])->where('isdel', $isdel) -> get();
    }
    //获取[ids]的所有任务
    public function getTaskByIds($ids, $isdel = 0){
        return $this -> select(['id', 'username']) -> where('isdel', $isdel) -> whereIn('id', $ids) -> get();
    }
}
?>