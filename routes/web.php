<?php

Route::get('/', function () {
    return redirect('/Question/index');
});

Route::get('/home', function () {
    return redirect('/Question/index');
});

Auth::routes();

// 邮箱激活
Route::get('/EmailConfirm/Activate/{confirmation_token}', 'EmailConfirmController@Activate');

// 问题
Route::group(['prefix' => '/Question'], function() {
    Route::get('/create', 'QuestionController@create');
    Route::get('/index', 'QuestionController@index');
    Route::get('/show/{question}', 'QuestionController@show');
    Route::get('/edit/{question}', 'QuestionController@edit');
    Route::get('index',  'QuestionController@index');


    Route::delete('/{question}', 'QuestionController@destroy');
    Route::put('/update/{question}', 'QuestionController@update');
    Route::post('/store', 'QuestionController@store');
});

// 邮箱激活测试
Route::get('/mail', function(){

    return new \App\Mail\UserVerifyMail(\App\User::find(1));
});

// 测试问题的路由,内容自行填充
Route::get('/delete', function (){
    $question_id =20;

    // 删除
    \App\Question::find($question_id)->topic()->sync([120,121]);
});


