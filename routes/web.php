<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
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

    Route::patch('/update/{question}', 'QuestionController@update');
    Route::post('/store', 'QuestionController@store');
});





// 邮箱激活测试
Route::get('/mail', function(){

    return new \App\Mail\UserVerifyMail(\App\User::find(1));
});

