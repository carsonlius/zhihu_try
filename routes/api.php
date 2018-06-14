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

// 判断当前登录的用户是不是已经登陆的状态
Route::post('/question/follower', function(Request $request){
    // 判断当前用户是否关注了某个问题
    $question_id = $request->post('question_id');
    $user_id = $request->get('id');

    $where = compact('question_id', 'user_id');
    $is_followed = \App\FollowerQuestion::where($where)
        ->count();

    return response()->json(['followed' => !!$is_followed, 'status' => 0, 'where' => $where]);
})->middleware('api');

// 开启或者关闭关注
Route::post('/question/follow', function (Request $request) {

    try {
        // 是否关注了某个问题
        $question_id = $request->post('question_id');
        $user_id = $request->get('id');

        $where = compact('question_id', 'user_id');
        $obj_followed = \App\FollowerQuestion::where($where)
            ->first();

        // 已经关注了 则删掉
        if ($obj_followed) {
            $obj_followed->delete();
            return response()->json(['followed' => false, 'status' => 0]);
        }

        // 没有关注 则添加
        \App\FollowerQuestion::create(compact('question_id', 'user_id'));
        return response()->json(['followed' => true, 'status' =>  0]);
    } catch (\Exception $e) {
        return response(['status' => 9999, 'msg' => $e->getMessage()]);
    }
});
