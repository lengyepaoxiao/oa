<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\AreaModel;
use App\Models\Api;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $api;

    public function __construct(Request $request){

        $this->api = new Api();
    }

    public function getProvinceLists(){

        $provinceLists = array();
        $result = AreaModel::getProvinceList();
        $result = $result->toArray();
        if($result){
            $provinceLists = $result;
        }

        return $provinceLists;
    }

    public function getCityLists($provinceId){

        $cityLists = array();
        $result = AreaModel::getCityList($provinceId);
        $result = $result->toArray();
        if($result){
            $cityLists = $result;
        }

        return $cityLists;
    }



}
