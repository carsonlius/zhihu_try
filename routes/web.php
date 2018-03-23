<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Auth::routes();

Route::get('/EmailConfirm/Activate/{confirmation_token}', 'EmailConfirmController@Activate');



// 邮箱激活
Route::get('/mail', function(){

    return new \App\Mail\UserVerifyMail(\App\User::find(1));
});

