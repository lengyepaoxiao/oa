<?php

namespace App\Libs;
use Illuminate\Support\Facades\Input;
use App\Models\Api;
class Upload{

    protected $project = '';

    public function __construct($project='')
    {
        $this->project = $project;
    }

    public function upload($request){
        //上传目录
        $src = 'v1/media/upload';
        $file = Input::file('file');
        if(!$file->isValid()){
            Common::outputJson(0,'上传无效');
            exit();
        }
        $type = $_FILES['file']['type'];
        if(!in_array($type,config('app.picture.type'))){
            Common::outputJson(0,'上传类型不符合');
            exit();
        }
        $extension = strtolower($file -> getClientOriginalExtension());
        if(!in_array($extension,config('app.picture.ext'))){
            Common::outputJson(0,'不支持扩展名');
            exit();
        }
        if($file->getSize() > config('app.picture.maxSize')){
            Common::outputJson(0,'上传文件不能超过2M');
            exit();
        }
        $uid = $request->session()->get('uid');
        $newName = $uid.'_'.time().mt_rand(0,99999).'.'.$extension;
        $UploadFile = $file->move(storage_path().'/uploads',$newName);
        $param['type'] = 'image';
        $param['media'] = $UploadFile;
        $api = new Api();
        $result = $api->uploadimgApi($src,$param);
        unlink($UploadFile);

        if($result['status'] == 0){
            Common::outputJson(0,'上传失败','');
            exit();
        }
        $data['img'] = $result['data']['file'];
        $data['prefix'] = config('app.img_url');
        Common::outputJson(1,'上传成功','',$data);
        exit();
    }
    //图片缩略上传
    public function thumb_upload($request){
        //上传目录
        $src = 'media/upload';
        $file = Input::file('file');
        if(!$file->isValid()){
            Common::outputJson(0,'上传无效');
            exit();
        }
        $type = $_FILES['file']['type'];
        if(!in_array($type,config('app.picture.type'))){
            Common::outputJson(0,'上传类型不符合');
            exit();
        }
        $extension = strtolower($file -> getClientOriginalExtension());
        if(!in_array($extension,config('app.picture.ext'))){
            Common::outputJson(0,'不支持扩展名');
            exit();
        }
        if($file->getSize() > config('app.picture.maxSize')){
            Common::outputJson(0,'上传文件不能超过2M');
            exit();
        }
        $uid = $request->session()->get('uid');
        $newName = 'thumb_'.$uid.'_'.time().mt_rand(0,99999).'.'.$extension;
        $OriginalFile = $file->move(storage_path().'/uploads',$newName);
        $UploadFile = Common::reSizeImg($OriginalFile,100,100);
        $param['project'] = $this->project;
        $param['type'] = 'image';
        $param['media'] = $UploadFile;
        $api = new Api();
        $result = $api->uploadimgApi($src,$param);
        unlink($OriginalFile);
        unlink($UploadFile);
        if($result['status'] == 0){
            Common::outputJson(0,'上传失败','');
            exit();
        }
        $data['img'] = $result['data']['file'];
        $data['prefix'] = config('app.imgUrl');
        Common::outputJson(1,'上传成功','',$data);
        exit();
    }
    //原图加缩略一起上传
    public function uploadAndThumb($request){
        //上传目录
        $src = 'media/upload';
        $file = Input::file('file');
        if(!$file->isValid()){
            Common::outputJson(0,'上传无效');
            exit();
        }
        $type = $_FILES['file']['type'];
        if(!in_array($type,config('app.picture.type'))){
            Common::outputJson(0,'上传类型不符合');
            exit();
        }
        $extension = strtolower($file -> getClientOriginalExtension());
        if(!in_array($extension,config('app.picture.ext'))){
            Common::outputJson(0,'不支持扩展名');
            exit();
        }
        if($file->getSize() > config('app.picture.maxSize')){
            Common::outputJson(0,'上传文件不能超过2M');
            exit();
        }
        $uid = $request->session()->get('uid');
        $newName = $uid.'_'.time().mt_rand(0,99999).'.'.$extension;
        $UploadFile = $file->move(storage_path().'/uploads',$newName);
        $param['project'] = $this->project;
        $param['type'] = 'image';
        $param['media'] = $UploadFile;
        $api = new Api();
        $result = $api->uploadimgApi($src,$param);
        if($result['status'] == 0){
            unlink($UploadFile);
            Common::outputJson(0,'上传失败','');
            exit();
        }
        $data['img'] = $result['data']['file'];
        $data['prefix'] = config('app.imgUrl');

        //进行缩略处理
        $thumb_width = config('app.thumb_pic.width');
        $thumb_height = config('app.thumb_pic.height');
        $UploadThumbFile = Common::reSizeImg($UploadFile,$thumb_width,$thumb_height);
        $param['project'] = $this->project;
        $param['type'] = 'image';
        $param['media'] = $UploadThumbFile;
        $result = $api->uploadimgApi($src,$param);
        if($result['status'] == 0){
            unlink($UploadFile);
            Common::outputJson(0,'上传失败','');
            exit();
        }
        $data['img_thumb'] = $result['data']['file'];
        unlink($UploadThumbFile);

        unlink($UploadFile);
        Common::outputJson(1,'上传成功','',$data);
        exit();
    }
}