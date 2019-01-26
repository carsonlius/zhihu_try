<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /*
    * 一天的最多发送次数
    * */
    private $attempt_max = 3;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        flash('提示:一天只可以发送三条确认邮件')->warning();
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // 判断邮件发送的次数是不是太多了
        if ($this->hasTooManyResetEmailAttempts($request)) {
            event(new Lockout($request));
            return $this->sendLockoutResponse();
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        $this->incrementResetEmailAttempts($request);

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }


    /**
     * Redirect the user after determining they are locked out.
     */
    protected function sendLockoutResponse()
    {
        throw ValidationException::withMessages([
            'email' => [Lang::get('auth.reset_email')],
        ])->status(423);
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function hasTooManyResetEmailAttempts(Request $request)
    {
        return app(RateLimiter::class)->tooManyAttempts(
            $this->throttleKey($request), $this->attempt_max
        );
    }

    /**
     * 获取存活周期
     * @return int
     */
    public function getMinutesDecay()
    {
        $timestamp_end = strtotime(date('Y-m-d', strtotime('+1 day')));
        return floor(($timestamp_end - time()) / 60);
    }

    /**
     * 发送邮件的次数, 时长维持一天
     * @param Request $request
     */
    public function incrementResetEmailAttempts(Request $request)
    {
        $minutes_decay = $this->getMinutesDecay();
        app(RateLimiter::class)->hit(
            $this->throttleKey($request), $minutes_decay
        );
    }

    /**
     * 获取签名
     * @param Request $request
     * @return string
     */
    private function throttleKey(Request $request): string
    {
        return $request->ip() . '_reset_email';
    }
}
