<?php

namespace App\Libs;
use Illuminate\Http\Request;
class Category{
    //按照每一个家族树的顺序排列
    static public function unlimitedForLevel($data,$html='-----',$pid=0,$level=0){
        $arr = array();
        foreach($data as $k=>$v){
            if($v->parent_id == $pid){
                $v->level = $level;
                $v->html = str_repeat($html,$level);
                $arr[] = $v;
                $arr = array_merge($arr,Category::unlimitedForLevel($data,$html,$v->id,$level+1));
            }
        }
        return $arr;
    }

    //把子分类放在父类中
    static public function unlimitedForLayer($data=array(),$pid=0){
        $arr = array();
        foreach ($data as $v) {
            if ($v['parent_id'] == $pid) {
                $v['child'] = Category::unlimitedForLayer($data, $v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }
    //只去父级分类
    static public function unlimitedForParent($data=array()){
        $arr = array();
        foreach($data as $v){
            if($v['parent_id'] == 0){
                $arr[] = $v;
            }
        }
        return $arr;
    }
    //获取父类id
    static public function getParentId($data=array(),$cid,$arr=array()){
        foreach($data as $v){
            if($v['id']==$cid){
                $arr[] = $v;
                $arr = array_merge(Category::getParentId($data,$v['parent_id']),$arr);
            }
        }
        return $arr;
    }
}
?>