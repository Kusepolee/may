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

//Github Webhooks
Route::any('/webhook/payload', 'WebhookController@GithubWebhook');

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
	Route::get('/init/member', 'MemberController@weChatInitUsers');
	Route::get('/init/department', 'DepartmentController@weChatInitDepartments');

	//用户
	Route::get('/member', 'MemberController@index');
	Route::post('/member/seek', 'MemberController@MemberSeek');
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
	Route::post('/member/password/reset/{id}', 'MemberController@passwordReset');
	Route::get('/member/password/form', 'MemberController@passwordForm');
	Route::get('/member/image/set', 'MemberController@image');
	Route::post('/member/image/store/{id?}', 'MemberController@imageStore');

	//OA
	Route::get('/oa/qrcode/{id?}', 'OaController@qrcode');
	Route::get('/oa/vcard/{id?}', 'OaController@vcard');

	//EXCEL
	Route::post('excel/member', 'ExcelController@getMembers');
	Route::post('excel/resource', 'ExcelController@getResources');

	//Notice 通知
	Route::post('notice/member', 'NoticeController@member');

	//资源
	Route::get('/resource', 'Resource\ResourceController@index');
	Route::get('/resource/create', 'Resource\ResourceController@create');
	Route::post('/resource/store', 'Resource\ResourceController@store');
	Route::post('/resource/seek', 'Resource\ResourceController@resourceSeek');
	Route::get('/resource/show/{id}', 'Resource\ResourceController@show');
	Route::get('/resource/edit/{id}', 'Resource\ResourceController@edit');
	Route::post('/resource/update/{id}', 'Resource\ResourceController@update');
	Route::get('/resource/delete/{id}', 'Resource\ResourceController@deleteNote');
	Route::get('/resource/delete_do/{id}', 'Resource\ResourceController@delete');
	Route::get('/resource/out/{id}', 'Resource\ResourceController@out');
	Route::post('/resource/out/store', 'Resource\ResourceController@outStore');
	Route::get('/resource/in/{id}', 'Resource\ResourceController@in');
	Route::post('/resource/in/store', 'Resource\ResourceController@inStore');
	// Route::get('/resource/list/{id}', 'Resource\ResourceController@getList');
	Route::get('/resource/image/set/{id}', 'Resource\ResourceController@image');
	Route::post('/resource/image/store/{id?}', 'Resource\ResourceController@imageStore');
	

});


/*
|--------------------------------------------------------------------------
| 测试
|--------------------------------------------------------------------------
*/


Route::get('/test2', 'Customer\CustomerController@index');
Route::get('/test1', 'OaController@qrcode');

Route::get('/test', function () {

echo mt_rand(100000,999999);

	//$sms = new FooWeChat\Notice\Alidayu;
	//$sms = new FooWeChat\Notice\Alidayu;
	//$sms->sendSms();
	// shell_exec('cd /mnt/may/');
	// shell_exec('git pull');

	//Fuck the git hub at  1:36

	//echo sha1('king0105');
	//echo sha1('king0105');
	//echo hash_hmac('king0105');
	//what's the fuck OK

	//echo hash_hmac("sha1", $json, 'king0105');
	//return response('200');
	//fuck ok
	//this is a great step
	//fuck the a
	//test auto pull;
	//exec("git pull https://github.com/restrose/may.git master"); 
	//fuck
	//fuck good

});


















