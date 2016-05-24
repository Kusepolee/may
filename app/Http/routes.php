<?php
/*
|--------------------------------------------------------------------------
| RestRose Wechat Application [FooWeChat]
|--------------------------------------------------------------------------
| restrose.net
| hi@restrose.net
| apr, 2016
|
*/


/*
|--------------------------------------------------------------------------
| [登录/退出]
|
| - table: members -> 用户表
|
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('login');
});
Route::post('/login', 'MemberController@login');
Route::get('/logout', 'MemberController@logout');


/*
|--------------------------------------------------------------------------
| 中间件1: wechat_or_login 功能: 使用微信,或者登录
|
|        使用微信 ---> 是 ---> 换取用户信息---> 继续
|                 |
|                 |-> 否 ---> 登录 ---> 成功 ---> 继续
|
| 中间件2: available 功能: 账户未被锁定, 未被软删除: state === 0, show === 0
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['wechat_or_login', 'available']], function () {

	//初始化
	Route::get('/member/init', 'MemberController@weChatInitUsers');
	Route::get('/department/init', 'DepartmentController@weChatInitDepartments');

	//用户
	Route::get('/member', 'MemberController@index');
	Route::get('/member/create', 'MemberController@create');
	Route::post('/member/store', 'MemberController@store');
	Route::get('/member/show/{id}', 'MemberController@show');
	Route::get('/member/edit/{id}', 'MemberController@edit');
	Route::post('/member/update/{id}', 'MemberController@update');
	Route::get('/member/delete/{id}', 'MemberController@deleteNote');
	Route::get('/member/delete_do/{id}', 'MemberController@delete');
	Route::get('/member/lock/{id}', 'MemberController@lock');
	Route::get('/member/unlock/{id}', 'MemberController@unlock');
	Route::get('/member/admin_get/{id}', 'MemberController@adminGet');
	Route::get('/member/admin_lost/{id}', 'MemberController@adminLost');
	
	//资源
	Route::get('/resource', 'ResourceController@index');
	Route::get('/resource/create', 'ResourceController@create');
	Route::post('/resource/store', 'ResourceController@store');
	Route::get('/resource/out/{id}', 'ResourceController@out');
	Route::post('/resource/out/store', 'ResourceController@outStore');
	Route::get('/resource/in/{id}', 'ResourceController@in');
	Route::post('/resource/in/store', 'ResourceController@inStore');
	Route::get('/resource/list/{id}', 'ResourceController@getList');

	//产品
	Route::get('/product', 'ProductController@index');
	Route::get('/product/create', 'ProductController@create');
	Route::post('/product/store', 'ProductController@store');
	Route::get('/product/quota/{id}', 'ProductController@quota');

	//OA
	Route::get('/oa/qrcode/{id?}', 'OaController@qrcode');

});


/*
|--------------------------------------------------------------------------
| 测试
|--------------------------------------------------------------------------
*/

Route::get('/test1', function () {

$a = new FooWeChat\Selector\Select;
$b = new FooWeChat\Core\WeChatAPI;

//$arr = ['user'=>'8', 'department'=>'市场部|技术部', 'seek'=>'>=:经理@生产部', 'self'=>'sub+'];
//$arr = ['user'=>'1|15'];

$arr = ['user'=>'1|8', 'department'=>'技术部|市场部'];
$send = $a->select($arr);
$body = "测试消息非常非常星光大jidp蝴蝶蝴蝶甲基橙 中中中 中吕吕中中中 吕骄傲了适当放宽姐";

$b->angentID = 0;
$b->sendText($send, $body);

});

Route::get('/t', function(){

 $h = new FooWeChat\Core\WeChatAPI;

// $s = $h->getDepartmentsInUse();
// $b = $h->getPositionsInUse();
// print_r($b);

 echo $h->getJsapiTicket();

//zb_JkYfEy9q0rcPy9_rf

});

Route::get('/test', 'ExcelController@getMembers');


















