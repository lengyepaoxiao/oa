<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
//登陆
Route::group(['domain' => 'oa.brgrand.cn','middleware' => ['web']], function () {
    //登录路由
    Route::get('login','PublicController@login');
    Route::post('login/check','PublicController@check');
    Route::get('login/quit','PublicController@quit');
});
//首页
Route::group(['domain' => 'oa.brgrand.cn', 'middleware' => ['web', 'verifylogin']], function (){
    //访问首页
    Route::get('index','IndexController@index');
    Route::get('welcome','IndexController@welcome');
});
//后台用户管理
Route::group(['domain' => 'oa.brgrand.cn', 'namespace'=> 'Admin','middleware' => ['web', 'verifylogin']], function (){
    Route::get('admin/lists', 'AdminController@lists');
    Route::post('admin/get_lists', 'AdminController@getLists');
    Route::get('admin/add', 'AdminController@add');
    Route::post('admin/create', 'AdminController@create');
    Route::get('admin/edit/{id}', 'AdminController@edit');
    Route::post('admin/get_edit', 'AdminController@getEdit');
    Route::post('admin/updates', 'AdminController@updates');
    Route::post('admin/deletes', 'AdminController@deletes');
});
//任务管理
Route::group(['domain' => 'oa.brgrand.cn', 'namespace'=> 'Task','middleware' => ['web', 'verifylogin']], function (){
    Route::get('task/lists', 'TaskController@lists');
    Route::post('task/get_lists', 'TaskController@getLists');
    Route::get('task/add', 'TaskController@add');
    Route::post('task/create', 'TaskController@create');
    Route::get('task/edit/{id}', 'TaskController@edit');
    Route::post('task/get_edit', 'TaskController@getEdit');
    Route::post('task/updates', 'TaskController@updates');
    Route::post('task/find', 'TaskController@find');
    Route::post('task/get_address', 'TaskController@getAddress');
    Route::post('task/deletes', 'TaskController@deletes');
    Route::get('task/make_form/{id}/{type}', 'TaskController@makeForm');
    Route::post('task/create_form', 'TaskController@createForm');
});
//员工管理
Route::group(['domain' => 'oa.brgrand.cn', 'namespace'=> 'User','middleware' => ['web', 'verifylogin']], function (){
    Route::get('user/lists', 'UserController@lists');
    Route::post('user/get_lists', 'UserController@getLists');
    Route::get('user/add', 'UserController@add');
    Route::post('user/create', 'UserController@create');
    Route::get('user/edit/{id}', 'UserController@edit');
    Route::post('user/get_edit', 'UserController@getEdit');
    Route::post('user/updates', 'UserController@updates');
    Route::post('user/deletes', 'UserController@deletes');
});
//主顾管理
Route::group(['domain' => 'oa.brgrand.cn', 'namespace'=> 'Customer','middleware' => ['web', 'verifylogin']], function (){
    Route::get('customer/lists', 'CustomerController@lists');
    Route::post('customer/get_lists', 'CustomerController@getLists');
    Route::get('customer/add', 'CustomerController@add');
    Route::post('customer/create', 'CustomerController@create');
    Route::get('customer/edit/{id}', 'CustomerController@edit');
    Route::post('customer/get_edit', 'CustomerController@getEdit');
    Route::post('customer/updates', 'CustomerController@updates');
    Route::post('customer/deletes', 'CustomerController@deletes');
});




