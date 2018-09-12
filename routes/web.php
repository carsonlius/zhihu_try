<?php

Route::get('/', 'QuestionController@index')->name('home');

Route::get('/home', 'QuestionController@index');

Auth::routes();

// 邮箱激活
Route::get('/EmailConfirm/Activate/{confirmation_token}', 'EmailConfirmController@Activate');

// 问题
Route::group(['prefix' => '/Question', 'middleware' => ['auth']], function () {
    // 创建问题
    Route::get('/create', 'QuestionController@create')->name('question.create')->middleware('permission:question.create');
    Route::get('/show/{question}', 'QuestionController@show'); // 展示问题
    Route::get('/edit/{question}', 'QuestionController@edit'); // 编辑问题
    Route::get('/index', 'QuestionController@index')->middleware('permission:question.index')->name('question.index');
    Route::delete('/{question}', 'QuestionController@destroy'); // 删除问题
    Route::put('/update/{question}', 'QuestionController@update'); // 更新问题
    Route::post('/store', 'QuestionController@store'); // 存储问题
});

// 答案

Route::group(['prefix' => 'Answer', 'middleware' => ['auth']], function () {
    Route::post('', 'AnswerController@store'); // 创建答案
});

// 关注者
Route::group(['prefix' => 'Follower', 'middleware' => ['auth']], function (){
    Route::get('/{question}', 'FollowerQuestionController@store'); // 关注问
});


// 私信
Route::group(['prefix' => 'message', 'middleware' => ['auth']], function (){
    // 当前用户收到的私信列表
    Route::get('/inbox', 'MessageController@index');

    // 来自某个特定用户发送给登陆用户的信息
    Route::get('/{friend_id}', 'MessageController@show');
});


// 消息通知
Route::group(['prefix' => 'notifications', 'middleware' => 'auth'], function(){
    // 消息通知列表
    Route::get('/', 'NotificationsController@index');

    // 消息详情列表
    Route::get('/{notification}', 'NotificationsController@show');
});


// 用户头像
Route::get('/avatar', 'UserController@avatar')->middleware('auth');
Route::post('/avatar', 'UserController@avatarUpload')->middleware('auth');

Route::group(['prefix' => 'user', 'middleware' => ['auth']], function (){
    // 用户分配角色
    Route::get('/role', 'UserController@roleAssign')->name('user.role')->middleware('permission:user.role');
});

// 用户密码
Route::group(['prefix' => 'password', 'middleware' => ['auth']], function(){
    // 密码得更新
    Route::get('', 'PasswordController@password');

    // 密码更新
    Route::post('update', 'PasswordController@update');
});

// 用户设置列表 && 更新
Route::get('setting', 'UserController@settingList')->middleware('auth');

Route::get('permission', function (){
    return view('home');
});

// 用户角色
Route::group(['prefix' => 'Role', 'middleware' => 'auth'], function(){
    // 角色列表
    Route::get('/', 'RoleController@index')->middleware('permission:role')->name('role');

    // 新建角色
    Route::get('/create', 'RoleController@create')->name('role.create')->middleware('permission:role.create');

    // 用户分配角色
    Route::get('/user', 'UserController@role')->name('role.user')->middleware('permission:role.user');

    // 编辑角色
    Route::get('/{role}/edit', 'RoleController@edit')->name('role.edit')->middleware('permission:role.edit');

    // 角色权限分配
    Route::get('/permission', 'RoleController@permission')->name('role.permission')->middleware('permission:role.permission');
});

// 权限
Route::group(['prefix' => 'permission', 'middleware' => 'auth'], function(){
    // 权限列表
    Route::get('/', 'PermissionController@index')->name('permission')->middleware('permission:permission.list');

    // 编辑权限
    Route::get('/{permission}/edit', 'PermissionController@edit')->name('permission.edit')->middleware('permission:permission.edit');

    // 新建权限
    Route::get('/create', 'PermissionController@create')->name('permission.create')->middleware('permission:permission.create');
});


// 为route菜单生成的路由
// 问题列表
Route::get('/resres', function(){

})->middleware('permission:question.sui')->name('question.sui');
Route::get('/whatever', function (){
})->middleware('permission:setting.whatever')->name('setting.whatever');


// 测试使用的路由
Route::get('/test', 'UserController@list');

Route::get('test_permission', 'PermissionController@tree');



