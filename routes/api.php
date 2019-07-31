<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/topics' ,'TopicController@index')->middleware(['throttle:10000,1']);

Route::resource('tasks', 'TaskController');

Route::group(['prefix' => 'question', 'middleware' => 'auth:api'], function (){
    // 开启或者关闭关注了某个问题
    Route::post('/follow', 'QuestionController@toggle');

    // 判断当前登录的用户是不是已经关注了某个问题
    Route::post('follower', 'QuestionController@follower');
});

// 当前用户是否关注了这个问题的作者
Route::get('/user/follower', 'FollowersController@index')->middleware('auth:api');

// toggle当前用户关注或者不在关注某个用户
Route::post('user/follow', 'FollowersController@follow')->middleware('auth:api');

Route::group(['prefix' => 'answer', 'middleware' => 'auth:api'], function (){
    // 用户点赞
    // 当前用户是否是点赞用户
    Route::get('/{answer_id}/votes/users', 'VoteController@users');

    // 点赞或者取消点赞的董卓
    Route::post('/vote', 'VoteController@vote');
});

// 私信的功能
Route::group(['prefix' => 'message', 'middleware' => ['auth:api']], function (){
    // 发送私信
    Route::post('/store', 'MessageController@store');

    // 未读私信的数量
    Route::post('/unreadNum', 'MessageController@unreadNum');

    // 私信详情
    Route::get('/inboxShow', 'MessageController@inboxShow');

    // 标记为已读
    Route::get('/markAsRead', 'MessageController@markAsRead');
});

// 评论部分
Route::group(['prefix' => 'comments', 'middleware' => ['auth:api']], function (){
    // 存储评论
    Route::post('store', 'CommentController@store');

    // 某个问题下面的评论列表
    Route::get('/{question_id}/question', 'CommentController@question');

    // 获取某个答案下的评论列表
    Route::get('/{answer_id}/answer', 'CommentController@answer');
});

// 通知部分
Route::group(['prefix' => 'notification', 'middleware' => 'auth:api'], function (){
    // 未阅读通知数量
    Route::get('unreadNum', 'NotificationsController@unreadNum');
});


// 更新配置
Route::post('setting', 'UserController@update')->middleware('auth:api');


// 用户角色管理
Route::group(['prefix' => 'user', 'middleware' => ['auth:api']], function(){
    // 用户权限列表
    Route::post('/role', 'UserController@list');

    // 用户全量列表
    Route::get('list', 'UserController@index');

    // 用户编辑角色
    Route::patch('/role', 'UserController@updateRole');
});


// 角色
Route::group(['prefix' => 'role', 'middleware' => 'auth:api'], function(){

    // 编辑角色
    Route::post('/edit', 'RoleController@update');

    // 分配角色权限
    Route::post('/permission', 'RoleController@updatePermission');

    // 存储角色
    Route::post('/', 'RoleController@store');

    // 角色列表
    Route::get('/', 'RoleController@list');

    // 特定用户绑定的角色
    Route::get('/show', 'RoleController@which');

    // 删除角色
    Route::post('/{role}', 'RoleController@delete');
});


Route::group(['prefix' => 'permission', 'middleware' => 'auth:api'], function(){

    // 已经有一部分 已经被选中得权限树
    Route::get('/tree_permission', 'PermissionController@tree');

    // 编辑权限
    Route::post('/edit', 'PermissionController@update');

    // 存储权限
    Route::post('/', 'PermissionController@store');

    // 权限列表
    Route::get('/', 'PermissionController@list');

    // 权限树(全量没有被选中纯净得权限树)
    Route::get('/tree', 'PermissionController@recursiveList');

    // 获取指定的权限(在权限树中的位置)
    Route::get('/show/tree', 'PermissionController@show');

    // 删除权限
    Route::post('/{permission}', 'PermissionController@destroy')->middleware('permission:permission.del');
});

Route::post('test', function(){
    if (!\request()->post('apieky')) {
        return [
            'status' => 1478,
            'error' => [
                'msg' => '鉴权未通过'
            ]
        ];

    }
    return [
        'status' => 0,
        'data' => [
            'hello' => 'world'
        ]
    ];
});

Route::get('test', function(){

    if (!\request()->get('apikey')) {
        return [
            'status' => 1478,
            'error' => [
                'msg' => '鉴权未通过'
            ]
        ];

    }
    return [
        'status' => 0,
        'data' => [
            'counter' => 14
        ]
    ];
})->middleware('auth:passport');

Route::get('music', function(){
    return env('APP_URL') . 'storage/program/audio/en.mp3';
});


// 小程序 网站
Route::group(['prefix' => 'mini', 'middleware' => ['auth:api']], function(){
    // 生成期刊
    Route::post('periodicals', 'PeriodicalController@createDo');

    // 期刊列表
    Route::get('periodicals', 'PeriodicalController@lists');

    // 更新期刊
    Route::patch('periodicals', 'PeriodicalController@update');


});

// 小程序前置的接口（敏感信息  && 生成personal token）
Route::group(['prefix' => 'mini'], function(){
    // 生成personal token
    Route::post('token', 'MiniProgramController@genPersonalToken');

    // 敏感信息解密
    Route::post('explainer', 'MiniProgramController@decode');

    // code to session 获取会话密钥
    Route::get('session', 'MiniProgramController@codeToSession');

    // 登陆
    Route::post('login', 'MiniProgramController@login');
});


// 文件管理
Route::group(['prefix' => 'file', 'middleware' => ['auth:api']], function(){

    // 上传单格文件
    Route::post('', 'FileController@uploadSingle');

    // 删除单个文件
    Route::delete('', 'FileController@deleteSingle');

});
