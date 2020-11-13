<?php
/**
 * 调用接口API.
 * User: tony.feng
 * Date: 2016/12/10
 * Time: 19:13
 */

namespace App\Models;
use App\Libs\Common;


class Api{

    private $curl;
    private $appid;
    private $appkey;
    private $getArray;

    function __construct(){

        $this->appid = config("app.appid");
        $this->appkey = config("app.appkey");
        $this->getArray = array("appid"=>$this->appid,"version"=>"v1","time"=>time());
    }

    public function requestApi($apiName,$data){

        $paramArray = array_merge($this->getArray,$data);
        $this->getArray["sign"] = Common::md5Sign($this->appkey,$paramArray);
        $paramString = Common::paramSort($this->getArray);
        $url = config("app.api"). "/".$apiName . "?" . $paramString;
        $body = json_encode($data);
        $result = Common::requestByCurl($url,$body);

        return $result;
    }

    public function uploadimgApi($apiName,$data){

        $data['media'] = new \CURLFile(realpath($data['media']));
        $paramArray = array_merge($this->getArray,$data);
        unset($paramArray['media']); //去掉上传的图片文件地址
        $this->getArray["sign"] = Common::md5Sign($this->appkey,$paramArray);
        $paramString = Common::paramSort($this->getArray);
        $url = config("app.api"). "/".$apiName . "?" . $paramString;
        $result = Common::requestByCurl($url,$data);

        return $result;
    }

    public function OauthAPi($apiName,$data){

        $paramArray = array_merge($this->getArray,$data);
        $this->getArray["sign"] = Common::md5Sign($this->appkey,$paramArray);
        $paramString = Common::paramSort($this->getArray);
        $url = config("app.api"). "/".$apiName . "?" . $paramString;
        $body = json_encode($data);
        $result = Common::requestByCurl($url,$body,0);

        return $result;
    }



}
