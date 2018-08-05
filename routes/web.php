<?php

Route::get('/', 'QuestionController@index');

Route::get('/home', 'QuestionController@index');

Auth::routes();

// 邮箱激活
Route::get('/EmailConfirm/Activate/{confirmation_token}', 'EmailConfirmController@Activate');

// 问题
Route::group(['prefix' => '/Question'], function () {
    Route::get('/create', 'QuestionController@create'); // 创建问题
    Route::get('/show/{question}', 'QuestionController@show'); // 展示问题
    Route::get('/edit/{question}', 'QuestionController@edit'); // 编辑问题
    Route::get('/index', 'QuestionController@index'); // 问题列表
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


// 邮箱激活测试
Route::get('/mail', function () {

    return new \App\Mail\UserVerifyMail(\App\User::find(1));
});


// 私信
Route::group(['prefix' => 'message', 'middleware' => ['auth']], function (){
    // 当前用户收到的私信列表
    Route::get('/inbox', 'MessageController@index');

    // 来自某个特定用户发送给登陆用户的信息
    Route::get('/{friend_id}', 'MessageController@show');
});

// 测试问题的路由,内容自行填充
Route::get('/tasks', function () {
    return view('test.test');
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


Route::get('test', function (){

    $user_id =1;

    return App\Message::where(compact('user_id'))->where('from_user_id', '!=', $user_id)->unread()->count();
});



