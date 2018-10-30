<?php

// 第三方登陆
Route::group(['prefix' => 'oauth'], function () {
    Route::get('/', 'AuthController@redirectToProvider');
    Route::get('/github', 'AuthController@gitHubCallback');
});

Route::get('/', 'QuestionController@index')->name('home');

Route::get('/home', 'QuestionController@index');

Auth::routes();

// 邮箱激活
Route::get('/EmailConfirm/Activate/{confirmation_token}', 'EmailConfirmController@Activate');

// 问题
Route::group(['prefix' => 'Question', 'middleware' => ['auth']], function () {
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
Route::group(['prefix' => 'Follower', 'middleware' => ['auth']], function () {
    Route::get('/{question}', 'FollowerQuestionController@store'); // 关注问
});


// 私信
Route::group(['prefix' => 'message', 'middleware' => ['auth']], function () {
    // 当前用户收到的私信列表
    Route::get('/inbox', 'MessageController@index');

    // 来自某个特定用户发送给登陆用户的信息
    Route::get('/{friend_id}', 'MessageController@show');
});


// 消息通知
Route::group(['prefix' => 'notifications', 'middleware' => 'auth'], function () {
    // 消息通知列表
    Route::get('/', 'NotificationsController@index');

    // 消息详情列表
    Route::get('/{notification}', 'NotificationsController@show');
});


// 用户头像
Route::get('/avatar', 'UserController@avatar')->middleware('auth');
Route::post('/avatar', 'UserController@avatarUpload')->middleware('auth');

Route::group(['prefix' => 'user', 'middleware' => ['auth']], function () {
    // 用户分配角色
    Route::get('/role', 'UserController@roleAssign')->name('user.role')->middleware('permission:user.role');
});

// 用户密码
Route::group(['prefix' => 'password', 'middleware' => ['auth']], function () {
    // 密码得更新
    Route::get('', 'PasswordController@password');

    // 密码更新
    Route::post('update', 'PasswordController@update');
});

// 用户设置列表 && 更新
Route::get('setting', 'UserController@settingList')->middleware('auth');

Route::get('permission', function () {
    return view('home');
});

// 用户角色
Route::group(['prefix' => 'Role', 'middleware' => 'auth'], function () {
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
Route::group(['prefix' => 'permission', 'middleware' => 'auth'], function () {
    // 权限列表
    Route::get('/', 'PermissionController@index')->name('permission')->middleware('permission:permission.list');

    // 编辑权限
    Route::get('/{permission}/edit', 'PermissionController@edit')->name('permission.edit')->middleware('permission:permission.edit');

    // 新建权限
    Route::get('/create', 'PermissionController@create')->name('permission.create')->middleware('permission:permission.create');
});


// 为一级菜单生路由
Route::get('/resres', 'QuestionController@index')->middleware('permission:question.sui')->name('question.sui');
Route::get('/whatever', 'QuestionController@index')->middleware('permission:setting.whatever')->name('setting.whatever');
Route::get('/oauth2/list', 'QuestionController@index')->name('oauth2.list');

// oauth2认证体系
Route::group(['prefix' => 'oauth2', 'middleware' => 'auth'], function () {
    // grant code  获取code
    Route::get('/authorization/code', function () {
        $query = http_build_query([
            'client_id' => 6,
            'redirect_uri' => 'https://zhihu.carsonlius.vip/oauth2/code/callback',
            'response_type' => 'code',
            'scope' => 'lesson1',
        ]);
        return redirect('https://learn.carsonlius.vip/oauth/authorize?' . $query);
    })->name('oauth2.code');

    // grant code callback
    Route::get('/code/callback', function (Illuminate\Http\Request $request) {
        $http = new GuzzleHttp\Client;
        $response = $http->post('https://learn.carsonlius.vip/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => 6,
                'client_secret' => 'xl3TdeIGnmp6IifoQPpemNvDFeZNHulr39f1AtLL',
                'redirect_uri' => 'https://zhihu.carsonlius.vip/oauth2/code/callback',
                'code' => $request->code,
            ],
        ]);
        $authorizationC_response = json_decode((string)$response->getBody(), true);
        $refresh_token = $authorizationC_response['refresh_token'];
        session(compact('refresh_token'));
        return $authorizationC_response;
    });

    // grant code refresh
    Route::get('/oauth2/refresh_token', function () {

        $refresh_token = session('refresh_token');
        $http = new GuzzleHttp\Client;
        $response = $http->post('https://learn.carsonlius.vip/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
                'client_id' => 6,
                'client_secret' => 'xl3TdeIGnmp6IifoQPpemNvDFeZNHulr39f1AtLL',
                'scope' => '',
            ],
        ]);
        return json_decode((string)$response->getBody(), true);
    })->name('auth2.code.refresh');

    //  password grant token
    Route::get('/authorization/token', function () {
        $http = new GuzzleHttp\Client;
        $response = $http->post('https://learn.carsonlius.vip/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '9',
                'client_secret' => 'OvJOczU4LQGBs2YNX0zRhtMqnU0Vobj59ztqNGNX',
                'username' => 'llewellyn33@example.org',
                'password' => 'secret',
                'scope' => '',
            ],
        ]);

        return json_decode((string)$response->getBody(), true);
    })->name('authorization.password');

    // implicit grant token
    Route::get('/authorization/implicit', function () {
        $query = http_build_query([
            'client_id' => 7,
            'redirect_uri' => 'https://zhihu.carsonlius.vip/oauth2/implicit/callback',
            'response_type' => 'token',
            'scope' => '*',
        ]);

        return redirect('https://learn.carsonlius.vip/oauth/authorize?' . $query);
    })->name('authorization.implicit');

    //  implicit grant type的回调部分
    Route::get('/implicit/callback', function (Illuminate\Http\Request $request) {
        dump('implicit认证完成,以后就是SPA的操作了');
    });


    // client credentials grant tokens
    Route::get('/credentials', function(){
        $guzzle = new GuzzleHttp\Client;

        $response = $guzzle->post('https://learn.carsonlius.vip/oauth/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => 7,
                'client_secret' => 'rlF4dXzY6PqKxKudWhU5itoUykuNUVraq5KsspKF',
                'scope' => '',
            ],
        ]);

        return json_decode((string) $response->getBody(), true)['access_token'];
    })->name('authorization.credentials');

    // personal access token
    Route::get('/personal_token', function (){
        return redirect('https://learn.carsonlius.vip/tokens/tokens');
    })->name('authorization.personal');

    Route::get('js/api', function(){
        return redirect('https://learn.carsonlius.vip/passport_web/show');
    })->name('js.api');
});




