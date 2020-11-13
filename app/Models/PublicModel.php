<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PublicModel extends Model
{


    protected $table = 'admin';

    protected $fillable = [];

    //根据用户名获取信息
    public  function getInfo($username){
        return $this->where('username', $username)->where('isdel', 0)->first();
    }

}
?>