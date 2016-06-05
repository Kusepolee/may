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

//SERVER
Route::any('/server/hook', 'ServerController@GithubWebhook');

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
	Route::get('/member/image/set', 'MemberController@image');
	Route::post('/member/image/store/{id?}', 'MemberController@imageStore');

	//OA
	Route::get('/oa/qrcode/{id?}', 'OaController@qrcode');
	Route::get('/oa/vcard/{id?}', 'OaController@vcard');

	//EXCEL
	Route::post('excel/member', 'ExcelController@getMembers');

	
	

});


/*
|--------------------------------------------------------------------------
| 测试
|--------------------------------------------------------------------------
*/


Route::get('/test2', 'Customer\CustomerController@index');
Route::get('/test1', 'OaController@qrcode');

Route::get('/test', function () {

	$json = 
'{
  "ref": "refs/heads/master",
  "before": "294d0b00b504788961644e25eff898069e34c493",
  "after": "556c3998d1c9d6e0dac0452088315127b46524ff",
  "created": false,
  "deleted": false,
  "forced": false,
  "base_ref": null,
  "compare": "https://github.com/restrose/may/compare/294d0b00b504...556c3998d1c9",
  "commits": [
    {
      "id": "556c3998d1c9d6e0dac0452088315127b46524ff",
      "tree_id": "082f9016eceb602bfc767a367fd9def7689a6ac8",
      "distinct": true,
      "message": "test\n\ntest",
      "timestamp": "2016-06-05T10:27:45+08:00",
      "url": "https://github.com/restrose/may/commit/556c3998d1c9d6e0dac0452088315127b46524ff",
      "author": {
        "name": "Kris",
        "email": "me@restrose.net"
      },
      "committer": {
        "name": "Kris",
        "email": "me@restrose.net"
      },
      "added": [

      ],
      "removed": [

      ],
      "modified": [
        "app/Http/Controllers/ServerController.php",
        "app/Http/routes.php"
      ]
    }
  ],
  "head_commit": {
    "id": "556c3998d1c9d6e0dac0452088315127b46524ff",
    "tree_id": "082f9016eceb602bfc767a367fd9def7689a6ac8",
    "distinct": true,
    "message": "test\n\ntest",
    "timestamp": "2016-06-05T10:27:45+08:00",
    "url": "https://github.com/restrose/may/commit/556c3998d1c9d6e0dac0452088315127b46524ff",
    "author": {
      "name": "Kris",
      "email": "me@restrose.net"
    },
    "committer": {
      "name": "Kris",
      "email": "me@restrose.net"
    },
    "added": [

    ],
    "removed": [

    ],
    "modified": [
      "app/Http/Controllers/ServerController.php",
      "app/Http/routes.php"
    ]
  },
  "repository": {
    "id": 59004589,
    "name": "may",
    "full_name": "restrose/may",
    "owner": {
      "name": "restrose",
      "email": "hi@restrose.net"
    },
    "private": false,
    "html_url": "https://github.com/restrose/may",
    "description": "",
    "fork": false,
    "url": "https://github.com/restrose/may",
    "forks_url": "https://api.github.com/repos/restrose/may/forks",
    "keys_url": "https://api.github.com/repos/restrose/may/keys{/key_id}",
    "collaborators_url": "https://api.github.com/repos/restrose/may/collaborators{/collaborator}",
    "teams_url": "https://api.github.com/repos/restrose/may/teams",
    "hooks_url": "https://api.github.com/repos/restrose/may/hooks",
    "issue_events_url": "https://api.github.com/repos/restrose/may/issues/events{/number}",
    "events_url": "https://api.github.com/repos/restrose/may/events",
    "assignees_url": "https://api.github.com/repos/restrose/may/assignees{/user}",
    "branches_url": "https://api.github.com/repos/restrose/may/branches{/branch}",
    "tags_url": "https://api.github.com/repos/restrose/may/tags",
    "blobs_url": "https://api.github.com/repos/restrose/may/git/blobs{/sha}",
    "git_tags_url": "https://api.github.com/repos/restrose/may/git/tags{/sha}",
    "git_refs_url": "https://api.github.com/repos/restrose/may/git/refs{/sha}",
    "trees_url": "https://api.github.com/repos/restrose/may/git/trees{/sha}",
    "statuses_url": "https://api.github.com/repos/restrose/may/statuses/{sha}",
    "languages_url": "https://api.github.com/repos/restrose/may/languages",
    "stargazers_url": "https://api.github.com/repos/restrose/may/stargazers",
    "contributors_url": "https://api.github.com/repos/restrose/may/contributors",
    "subscribers_url": "https://api.github.com/repos/restrose/may/subscribers",
    "subscription_url": "https://api.github.com/repos/restrose/may/subscription",
    "commits_url": "https://api.github.com/repos/restrose/may/commits{/sha}",
    "git_commits_url": "https://api.github.com/repos/restrose/may/git/commits{/sha}",
    "comments_url": "https://api.github.com/repos/restrose/may/comments{/number}",
    "issue_comment_url": "https://api.github.com/repos/restrose/may/issues/comments{/number}",
    "contents_url": "https://api.github.com/repos/restrose/may/contents/{+path}",
    "compare_url": "https://api.github.com/repos/restrose/may/compare/{base}...{head}",
    "merges_url": "https://api.github.com/repos/restrose/may/merges",
    "archive_url": "https://api.github.com/repos/restrose/may/{archive_format}{/ref}",
    "downloads_url": "https://api.github.com/repos/restrose/may/downloads",
    "issues_url": "https://api.github.com/repos/restrose/may/issues{/number}",
    "pulls_url": "https://api.github.com/repos/restrose/may/pulls{/number}",
    "milestones_url": "https://api.github.com/repos/restrose/may/milestones{/number}",
    "notifications_url": "https://api.github.com/repos/restrose/may/notifications{?since,all,participating}",
    "labels_url": "https://api.github.com/repos/restrose/may/labels{/name}",
    "releases_url": "https://api.github.com/repos/restrose/may/releases{/id}",
    "deployments_url": "https://api.github.com/repos/restrose/may/deployments",
    "created_at": 1463473821,
    "updated_at": "2016-05-17T08:34:31Z",
    "pushed_at": 1465093674,
    "git_url": "git://github.com/restrose/may.git",
    "ssh_url": "git@github.com:restrose/may.git",
    "clone_url": "https://github.com/restrose/may.git",
    "svn_url": "https://github.com/restrose/may",
    "homepage": null,
    "size": 4107,
    "stargazers_count": 0,
    "watchers_count": 0,
    "language": "PHP",
    "has_issues": true,
    "has_downloads": true,
    "has_wiki": true,
    "has_pages": false,
    "forks_count": 1,
    "mirror_url": null,
    "open_issues_count": 0,
    "forks": 1,
    "open_issues": 0,
    "watchers": 0,
    "default_branch": "master",
    "stargazers": 0,
    "master_branch": "master",
    "organization": "restrose"
  },
  "pusher": {
    "name": "Kris-Ni",
    "email": "kris@restrose.net"
  },
  "organization": {
    "login": "restrose",
    "id": 18107250,
    "url": "https://api.github.com/orgs/restrose",
    "repos_url": "https://api.github.com/orgs/restrose/repos",
    "events_url": "https://api.github.com/orgs/restrose/events",
    "hooks_url": "https://api.github.com/orgs/restrose/hooks",
    "issues_url": "https://api.github.com/orgs/restrose/issues",
    "members_url": "https://api.github.com/orgs/restrose/members{/member}",
    "public_members_url": "https://api.github.com/orgs/restrose/public_members{/member}",
    "avatar_url": "https://avatars.githubusercontent.com/u/18107250?v=3",
    "description": "Laravel, Wechat"
  },
  "sender": {
    "login": "Kris-Ni",
    "id": 18107213,
    "avatar_url": "https://avatars.githubusercontent.com/u/18107213?v=3",
    "gravatar_id": "",
    "url": "https://api.github.com/users/Kris-Ni",
    "html_url": "https://github.com/Kris-Ni",
    "followers_url": "https://api.github.com/users/Kris-Ni/followers",
    "following_url": "https://api.github.com/users/Kris-Ni/following{/other_user}",
    "gists_url": "https://api.github.com/users/Kris-Ni/gists{/gist_id}",
    "starred_url": "https://api.github.com/users/Kris-Ni/starred{/owner}{/repo}",
    "subscriptions_url": "https://api.github.com/users/Kris-Ni/subscriptions",
    "organizations_url": "https://api.github.com/users/Kris-Ni/orgs",
    "repos_url": "https://api.github.com/users/Kris-Ni/repos",
    "events_url": "https://api.github.com/users/Kris-Ni/events{/privacy}",
    "received_events_url": "https://api.github.com/users/Kris-Ni/received_events",
    "type": "User",
    "site_admin": false
  }
}';
	//$sms = new FooWeChat\Notice\Alidayu;
	//$sms = new FooWeChat\Notice\Alidayu;
	//$sms->sendSms();
	//shell_exec('cd /mnt/may/');
	//shell_exec('git pull');
	//echo sha1('king0105');
	//echo sha1('king0105');
	//echo hash_hmac('king0105');

	echo hash_hmac("sha1", $json, 'king0105');
	//this is a great step
	//fuck the a
	//test auto pull;
	//exec("git pull https://github.com/restrose/may.git master"); 
	//fuck

});


















