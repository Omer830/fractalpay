<?php

namespace Modules\Auth\Contracts;

use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Requests\ResetPassword;
use Modules\Auth\Http\Requests\SendOTPRequest;
use Modules\Auth\Http\Requests\VerifyOTPRequest;

interface AuthControllerInterface
{

    //This will be responsible displaying the signup page or redirecting inside app
    public function index();

    public function register(RegisterRequest $request);

    public function login(LoginRequest $request);

    public function sendOTP(SendOTPRequest $request);

    public function verifyOTP(VerifyOTPRequest $request);

    public function refreshAccess();

    public function forgot(SendOTPRequest $request);

    public function resetPassword(ResetPassword $request);

}
