<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class AdminModel extends Model
{


    protected $table = 'admin';

    protected $fillable = [];

    //获取用户的数量
    public function counts($isdel=0){
        return $this->where('isdel', $isdel)->count();
    }

    //获取该用户下小程序列表
    public function lists($first, $pagesize, $isdel=0){
        return $this->where('isdel', $isdel)
            ->orderBy('id','desc')
            ->offset($first)
            ->limit($pagesize)
            ->get();
    }
    //数据保存添加
    public function inserts($data){
        return $this->insertGetId($data);
    }
    //判断是否有重复昵称存在
    public function checkNameExist($username){
        return $this -> where('username', $username) -> first();
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

}
?>