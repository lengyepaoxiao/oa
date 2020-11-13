<?php

namespace App\Libs;
use Illuminate\Http\Request;
use Monolog\Logger;
use Session;
use Monolog\Handler\StreamHandler;

class Common
{

    public static function mkPathDir($path){
        if (!is_dir($path)) {
            mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
        }
        return $path;
    }

    /**
     * 验证日期是否合法
     *
     */
    public static function isDate($dateString) {
        return strtotime( date('Y-m-d', strtotime($dateString)) ) === strtotime( $dateString );
    }

    /**
     * 输出JSON字符串
     * @param int $status
     * @param string $error_code
     * @param void $data
     * @return string
     */
    public static function outputJson($status,$msg="",$jumpUrl="",$data=array(),$extenArray = array()){

        $lists["status"] = $status;
        $lists["msg"] = $msg;
        $lists['jumpurl'] = $jumpUrl;
        $lists["data"] = $data;

        if(is_array($extenArray)){
            $lists = array_merge($lists,$extenArray);
        }else{
            $tempArray = explode("^|^",$extenArray);
            $lists[$tempArray[0]] = $tempArray[1];
        }

        $result = json_encode($lists);
        echo $result;
        exit();
    }

    /**
     * 验证参数是否为空
     * @param array $postParamArray POST参数
     * @param array $srcParamArray  需要验证的参数
     */
    public static function validationParams($postParamArray ,$srcParamArray){

        //验证key/value的是否非空值
        foreach ($srcParamArray as $value){
            if(!isset($postParamArray[$value])){
                return false;
            }
            if(empty($postParamArray[$value])){
                return false;
            }
        }
        return true;
    }

    /**
     * 验证签名是否正确
     * @param type $paramArray
     * @param type $sign
     * @return boolean
     */
    public static function  validationSign($appkey,$paramArray, $sign){
        $paramSign = self::md5Sign($appkey,$paramArray);

        if($paramSign != $sign){
            return false;
        }
        return true;
    }

    /**
     * md5签名
     * @param array $paramArray
     * @return string
     */
    public static function md5Sign($appkey,$paramArray){

        //去除sign参数
        if(isset($paramArray["sign"])){
            unset($paramArray["sign"]);
        }
        $paramString = Common::paramSort($paramArray);
        $sign = md5($paramString . $appkey);
        //fwrite(fopen("/data1/www/api-service_yftechnet_com/storage/logs/sign.txt","w"),$paramString . $appkey."---".$sign);
        return $sign;
    }

    /**
     * 将key/value参数 a 到 z 的顺序排序
     * @param array $params
     * @return string
     */
    public static function paramSort($paramArray){

        $tempParamArray = array();
        $arrayKey = array_keys($paramArray);
        sort($arrayKey);
        foreach ($arrayKey as $value){
            if(empty($paramArray[$value])){
                continue;
            }
            $tempParamArray[] = $value."=".$paramArray[$value];
        }
        $paramString = implode("&", $tempParamArray);
        return $paramString;
    }

    /**
     * 生成商户消费订单号36位
     * @param type $uid
     * @return string
     */
    public static function getOrderNo(){

        $milliSecond =  floor(microtime() * 1000);
        $strNo = date("ymdHis") . $milliSecond.rand(0,9);
        $len = strlen($strNo);
        if($len < 16){
            $strUid = "";
            for($i = 0;$i < 16 - $len;$i++){
                $strUid .= rand(0,9);
            }
            $strNo = $strNo.$strUid;
        }
        return $strNo;
    }

    /**
     * 价格格式化输出
     * @param $price
     */
    public static function price_format($price){
        return number_format($price,2,".","");
    }

    /**
     * 获取平台商户号
     * @param type $uid
     * @return string
     */
    public static function getMierchantNo(){

        $milliSecond =  floor(microtime() * 1000);
        $strNo = date("ymdHis") . $milliSecond.rand(100,999);
        return $strNo;
    }

    /**
     * 字符串RSA加密
     * @param type $originalData   用于加密的字符串
     * @param type $privateKeyString    密钥文件内容
     * @return void
     */
    public static function RsaEncrypt($originalData, $privateKeyString){

        $privateKeyString = openssl_pkey_get_private($privateKeyString);
        if (openssl_private_encrypt($originalData, $encryptData, $privateKeyString)){
            $encryptData = base64_encode($encryptData);
            return $encryptData;
        }else{
            return false;
        }
    }

    /**
     * 字符串RSA解密
     * @param type $encryptData
     * @param type $publicKeyString
     * @return void
     */
    public static function RsaDecrypt($encryptData, $publicKeyString){

        $publicKeyString = openssl_pkey_get_public($publicKeyString);
        $encryptData = base64_decode($encryptData);
        if (openssl_public_decrypt($encryptData, $originalData, $publicKeyString)) {
            return $originalData;
        } else {
            return false;
        }
    }

    /**
     * 写日志文件 common::writeLogs("error",0,json_encode($_REQUEST));
     * @param $type  日志类型
     * @param  $code 状态码
     * @param $msg   信息
     * @return bool
     */
    public static function writeLogs($type,$code,$msg){

        //日志类型
        $logType = array(
            "info"=>Logger::INFO,
            "error"=>Logger::ERROR,
            "notice"=>Logger::NOTICE,
            "warning"=>Logger::WARNING,
            "critical"=>Logger::CRITICAL,
            "alert"=>Logger::ALERT,
            "emergency"=>Logger::EMERGENCY,
            "debug"=>Logger::DEBUG);
        if(!in_array($type,array_keys($logType))){
            return false;
        }

        $msgContents =   "||" . $code . "||" . $_SERVER["REQUEST_URI"] . "||" . $msg. "||" ;
        $logFile = storage_path() . "/logs/custom-" . date("YmdH", time()) . '.log';
        //写日志
        $log = new Logger('yftechnet');
        $log->pushHandler(new StreamHandler($logFile, $logType[$type]));
        $log->$type($msgContents);
        return true;
    }

    /**
     * POST请求
     * @param $remote_server
     * @param $post_string
     * @return array
     */
    public static function requestByCurl($remote_server, $post_string){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "POST" );
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,3);
        curl_setopt($ch,CURLOPT_TIMEOUT,5);
        $data = curl_exec($ch); //echo $data;exit();
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($code != 200){
            return array("status"=>0,"error_code"=>20502,"data"=>array());
        }
        $info = json_decode($data,true);
        if(!$info){
            $info = $data;
        }
          
        return $info;
        
    }

    /**
     * 等比例生成缩略图
     * @param $imgSrc
     * @param $resize_width
     * @param $resize_height
     * @param $isCut
     * @author james.ou 2011-11-1
     */
    public static function reSizeImg($imgSrc, $resize_width, $resize_height, $isCut = false) {
        //图片的类型
        $type = substr(strrchr($imgSrc, "."), 1);
        //初始化图象
        if ($type == "jpg") {
            $im = imagecreatefromjpeg($imgSrc);
        }
        if ($type == "gif") {
            $im = imagecreatefromgif($imgSrc);
        }
        if ($type == "png") {
            $im = imagecreatefrompng($imgSrc);
        }
        //目标图象地址
        $full_length = strlen($imgSrc);
        $type_length = strlen($type);
        $name_length = $full_length - $type_length;
        $name = substr($imgSrc, 0, $name_length - 1);
        $dstimg = $name . "_" . $resize_width . 'x' . $resize_height . '.' . $type;

        $width = imagesx($im);
        $height = imagesy($im);

        //生成图象
        //改变后的图象的比例
        $resize_ratio = ($resize_width) / ($resize_height);
        //实际图象的比例
        $ratio = ($width) / ($height);
        if (($isCut) == 1) { //裁图
            if ($ratio >= $resize_ratio) { //高度优先
                $newimg = imagecreatetruecolor($resize_width, $resize_height);
                imagecopyresampled($newimg, $im, 0, 0, 0, 0, $resize_width, $resize_height, (($height) * $resize_ratio), $height);
                ImageJpeg($newimg, $dstimg);
            }
            if ($ratio < $resize_ratio) { //宽度优先
                $newimg = imagecreatetruecolor($resize_width, $resize_height);
                imagecopyresampled($newimg, $im, 0, 0, 0, 0, $resize_width, $resize_height, $width, (($width) / $resize_ratio));
                ImageJpeg($newimg, $dstimg);
            }
        } else { //不裁图
            if ($ratio >= $resize_ratio) {
                $newimg = imagecreatetruecolor($resize_width, ($resize_width) / $ratio);
                imagecopyresampled($newimg, $im, 0, 0, 0, 0, $resize_width, ($resize_width) / $ratio, $width, $height);
                ImageJpeg($newimg, $dstimg);
            }
            if ($ratio < $resize_ratio) {
                $newimg = imagecreatetruecolor(($resize_height) * $ratio, $resize_height);
                imagecopyresampled($newimg, $im, 0, 0, 0, 0, ($resize_height) * $ratio, $resize_height, $width, $height);
                ImageJpeg($newimg, $dstimg);
            }
        }
        ImageDestroy($im);
        return $dstimg;
    }

    //GET请求
    public static function getRequestByCurl($url){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        $info = json_decode($data,true);
        if(!$info){
            $info = $data;
        }
        return $info;
    }

    /**
     * 获取客户端IP
     * @return string
     */
    public static function getIP(){
        global $ip;
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknow";

        return $ip;
    }

    /**
     * 获取随机字符串
     * @param $length
     * @return string
     */
    public static function randomkeys($length){
        $key = "";
        $pattern='12345678900';
        for($i=0;$i < $length;$i++){
            $key .= $pattern{mt_rand(0,10)};    //生成php随机数
        }
        return $key;
    }

    /**
     * 发送短信
     * @param $smsContent
     * @param $mobile
     * @return bool
     */
    public static function sendSMS($smsContent,$mobile){
        $accountSid = '';
        $timestamp = date('YmdHis');
        $sig = md5($accountSid . '325756da04ad497e84d5ce4e2eaa334b' . $timestamp);
        $params = 'accountSid='.$accountSid.'&smsContent='.$smsContent.'&to='.$mobile.'&timestamp='.$timestamp.'&sig='.$sig;
        $headers = array('Content-type:application/x-www-form-urlencoded');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.miaodiyun.com/20150822/industrySMS/sendSMS');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec ($ch);
        $res = json_decode($res,true);
        if($res['respCode'] == '00000'){
            return true;
        }
        return false;
    }


    //获取时间格式化
    public static function formatDate($time){  
        
        $rtime = date ( "m-d H:i", $time );  
        $htime = date ( "H:i", $time );  
          
        $time = time () - $time;  
          
        if ($time < 60) {  
            $str = '刚刚';  
        } elseif ($time < 60 * 60) {  
            $min = floor ( $time / 60 );  
            $str = $min . '分钟前';  
        } elseif ($time < 60 * 60 * 24) {  
            $h = floor ( $time / (60 * 60) );  
            $str = $h . '小时前 ' . $htime;  
        } elseif ($time < 60 * 60 * 24 * 3) {  
            $d = floor ( $time / (60 * 60 * 24) );  
            if ($d == 1)  
                $str = '昨天 ' . $rtime;  
            else  
                $str = '前天 ' . $rtime;  
        } else {  
            $str = $rtime;  
        }  
        return $str;    
    }  

    //多维数组排序
    public static function arrayMultisort($data,$sortOrderField,$sortOrder=SORT_ASC,$sortType=SORT_NUMERIC){
        foreach($data as $val){
        $key_arrays[] = $val[$sortOrderField];
        }
        array_multisort($key_arrays,$sortOrder,$sortType,$data);
        return $data;
    }
        

}
