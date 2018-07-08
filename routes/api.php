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

Route::get('/topics' ,function(Request $request){

    $search = $request->input('search');
    $topic = \App\Topic::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->get();
//    $response = ['results' => $topic];
    return json_encode($topic);
})->middleware(['throttle:10000,1']);

Route::resource('tasks', 'TaskController');

// 判断当前登录的用户是不是已经
Route::post('/question/follower', function(Request $request){
    // 判断当前用户是否关注了某个问题
    $question_id = $request->post('question_id');
    $user = \Auth::guard('api')->user();

    $is_followed = $user->followThisQuestion($question_id);

    return response()->json(['followed' => !!$is_followed, 'status' => 0]);
})->middleware('auth:api');

// 开启或者关闭关注了某个问题
Route::post('/question/follow', function (Request $request) {
    try {
        // 是否关注了某个问题
        $question_id = $request->post('question_id');
        $user_id = \Auth::guard('api')->id();

        $where = compact('question_id', 'user_id');
        $obj_followed = \App\FollowerQuestion::where($where)
            ->first();

        // 已经关注了 则删掉  && 关注者减一
        if ($obj_followed) {
            $obj_followed->delete();

            \App\Question::find($question_id)->decrement('flowers_count', 1);
            return response()->json(['followed' => false, 'status' => 0]);
        }

        // 没有关注 则添加 && 关着者+1
        \App\FollowerQuestion::create(compact('question_id', 'user_id'));
        \App\Question::find($question_id)->increment('flowers_count', 1);
        return response()->json(['followed' => true, 'status' =>  0]);
    } catch (\Exception $e) {
        return response(['status' => 9999, 'msg' => $e->getMessage()]);
    }
})->middleware('auth:api');


// 当前用户是否关注了这个问题的作者
Route::get('/user/follower', 'FollowersController@index')->middleware('auth:api');

// toggle当前用户关注或者不在关注某个用户
Route::post('user/follow', 'FollowersController@follow')->middleware('auth:api');



Route::group(['prefix' => 'answer', 'middleware' => 'auth:api'], function (){
    // 用户点赞
    // 当前用户是否是点赞用户
    Route::get('/{answer_id}/votes/users', 'VoteController@users')->middleware('auth:api');

    // 点赞或者取消点赞的董卓
    Route::post('/vote', 'VoteController@vote')->middleware('auth:api');
});



// 私信的功能
Route::group(['prefix' => 'message', 'middleware' => ['auth:api']], function (){
    // 发送私信
    Route::post('/store', 'MessageController@store');
});