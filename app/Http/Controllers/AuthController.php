<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AuthRepository;

class AuthController extends Controller
{
    protected $repository_auth;

    /**
     * AuthController constructor.
     * @param $repository_auth
     */
    public function __construct(AuthRepository $repository_auth)
    {
        $this->repository_auth = $repository_auth;
    }

    /**
     * 认证
     */
    public function redirectToProvider()
    {
        try {
            // oauth认证
            return $this->repository_auth->redirectToProvider();
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
            return redirect('/login');
        }
    }

    /**
     * github 登陆回调
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function gitHubCallback()
    {
        try {
            return $this->repository_auth->gitHubCallback();
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
            return redirect('/login');
        }
    }
}
