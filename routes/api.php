<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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

    // 权限树(没有被选中纯净得权限树)
    Route::get('/tree', 'PermissionController@recursiveList');

    // 删除权限
    Route::post('/{permission}', 'PermissionController@destroy');
});


