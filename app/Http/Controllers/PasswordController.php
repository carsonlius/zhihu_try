<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PasswordRepository;
use App\Http\Requests\PasswordChangeRequest;

class PasswordController extends Controller
{
    protected $repository_password;

    /**
     * PasswordController constructor.
     * @param $repository_passworden
     */
    public function __construct(PasswordRepository $repository_password)
    {
        $this->repository_password = $repository_password;
    }

    /**
     * 更改密码得页面
     */
    public function password()
    {
        return view('users.password');
    }

    /**
     * 更新密码
     * @param PasswordChangeRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PasswordChangeRequest $request)
    {
        try {
            $this->repository_password->update();
            return redirect('/');
        } catch (\Exception $e) {
            return redirect()->back()->withInput();
        }
    }
}
