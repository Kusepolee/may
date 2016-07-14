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
	Route::get('/cookie/clear', 'OaController@cookieClear');

	//用户
	Route::get('/member', 'MemberController@index');
	Route::post('/member/seek', 'MemberController@MemberSeek');
	Route::get('/member/create', 'MemberController@create');
	Route::post('/member/store', 'MemberController@store');
	Route::get('/member/show/{id?}', 'MemberController@show');
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
	Route::post('excel/finance', 'ExcelController@getFinance');

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
	
	//财务
	Route::get('/finance', 'Finance\FinanceController@index');
	Route::get('/finance/outs', 'Finance\FinanceController@out');
	Route::post('/finance/outs/store', 'Finance\FinanceController@outStore');
	Route::get('/finance/trans/{id}', 'Finance\FinanceController@tran');
	Route::post('/finance/trans/store', 'Finance\FinanceController@tranStore');
	Route::post('/finance/seek', 'Finance\FinanceController@financeSeek');

	//面板
	Route::get('/panel', 'Panel\PanelController@index');
	Route::get('/panel/complaints', 'Panel\PanelController@complaints');
	Route::post('/panel/complaints/store', 'Panel\PanelController@complaintsStore');
	Route::get('/panel/complaints/image/set/{id}', 'Panel\PanelController@image');
	Route::post('/panel/complaints/image/store', 'Panel\PanelController@imageStore');
	Route::get('/panel/complaints/record', 'Panel\PanelController@complaintsRecord');
	Route::get('/panel/complaints/show/{id}', 'Panel\PanelController@complaintsShow');

});


/*
|--------------------------------------------------------------------------
| 测试
|--------------------------------------------------------------------------
*/


Route::post('/upload_test', 'OaController@test');


Route::get('/test', function () {
	$user = 'kris';

	Mail::send('welcome', ['user' => $user], function ($m) use ($user) {
            $m->from('note@mail.henjou.com', '恒久滚塑');

            $m->to('7569300@qq.com', 'kris Ni')->subject('自动通知');
    });
});

Route::get('/test1', function () {
	return redirect('/panel');
});


















