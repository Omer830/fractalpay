<?php

namespace Modules\Auth\Http\Controllers\Web;

use ErrorException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\UserKyc\DTO\UserDTO;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InertiaException;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Services\UserService;
use App\Http\Middleware\InertiaCognito;
use Illuminate\Support\Facades\Redirect;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\ResetPassword;
use Modules\Auth\Http\Requests\SendOTPRequest;
use Modules\Auth\Http\Resources\LoginResource;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Options\Services\OptionListService;
use Modules\Auth\Http\Requests\VerifyOTPRequest;
use Modules\UserKyc\Services\UserProfileService;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Contracts\AuthControllerInterface;
use Modules\PaymentMethod\Services\UserPaymentMethodService;


class WebAuthController extends AuthController implements AuthControllerInterface
{
    protected $userService;

    public function __construct(UserService $userService, OptionListService $optionListService,  private UserProfileService $UserProfileService, private UserPaymentMethodService $UserPaymentMethodService)
    {
        $this->userService = $userService;
        $this->optionListService = $optionListService;
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function showSignupForm()
    {
        $countries = $this->optionListService->getOptionsList(['categories' => 'countries']);
        return Inertia::render('Signup/Signup', ['countries' => $countries]);

    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->userService->registerUser($request->validated());
            return Redirect::route('signup.otp')
                ->with('user', $user)
                ->with('success', 'Contact created.');

        } catch (\App\Exceptions\ErrorException $e) {
            throw new InertiaException('Signup/SignupForm', $e);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed. Please try again later.']);
        }
    }



    public function sendOTP(SendOTPRequest $request)
    {       
        try {
            Log::debug('in the otp flow');
            $result = $this->userService->sendOTP($request->validated());

            $message = $result ? 'OTP successfully sent.' : 'Failed to send OTP.';
            request()->session()->flash('message', $message);
            request()->session()->flash('success', $result);

            return redirect()->back();
        } catch (\Exception $e) {
           
            \Log::error("Error sending OTP: " . $e->getMessage());

            request()->session()->flash('error', 'An error occurred while sending the OTP.');

            return redirect()->back();
        }
    }


    public function stepsPage()
    {
        $this->UserProfileService->setUserStatus();

        $this->UserProfileService->setUserDocumentsStatus();

        $data = [];

        $this->UserPaymentMethodService->setPaymentMethodStatus($data);

        $profileStatus          =   session()->get('profile_status');

        $proofOfIdentityStatus  =   session()->get('proofOfIdentityStatus');

        $paymentStatus          =   session()->get('PaymentStatus');

        return Inertia::render('Steps/steps', [
            'profile_status'        => $profileStatus,
            'proofOfIdentityStatus' => $proofOfIdentityStatus,
            'paymentStatus'         => $paymentStatus
        ]);
    }


    public function verifyOTP(VerifyOTPRequest $request)
    {
       
        $data = $this->userService->verifyOTP($request->validated());
        if (session('wrongOtp') === true) {
            session()->forget('wrongOtp'); 
            session()->forget('myPassword'); 
            return redirect()->route('welcome.page')->with('message', 'OTP verify successfully!');
        }
        if(is_array($data))
        {
            return redirect()->route('welcome.page')->with('message', 'OTP verify successfully!');
        }

        $resource = new LoginResource($data);

        return redirect()->route('choose-password')
            ->with([
                'message' => 'OTP verified successfully. You may now choose a new password.',
                'Authorization' => $data->token->AccessToken, // Example of passing token in the session
                'AccessExpiry' => now()->addMinutes(55)->unix() // Including this for consistency with your previous setup
            ]);
    }
    public function choosePasswordPage()
    {
        return Inertia::render('ForgotPassword/ChoosePassword');
    }
    public function verifyOTPFP(VerifyOTPRequest $request)
    {
        $type='forgetPassword';
        $data = $this->userService->verifyOTP($request->validated(),$type);

        if($data === true)
        {
            return redirect()->route('choose-password')->with('message', 'OTP verify successfully!');
        }

        $resource = new LoginResource($data);

        return redirect()->route('choose-password')
            ->with([
                'message' => 'OTP verified successfully. You may now choose a new password.',
                'Authorization' => $data->token->AccessToken, // Example of passing token in the session
                'AccessExpiry' => now()->addMinutes(55)->unix() // Including this for consistency with your previous setup
            ]);
    }

    public function showLoginForm()
    {
        return Inertia::render('Login/Login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->userService->loginUser($request->validated());
            
            if(is_array($data)){
                 if (isset($data['otp_verified']) && $data['otp_verified'] === false) {
                return redirect()->route('signup.otp')->with([
                    'user' => $data['user'],
                    'success' => 'Contact created.',
                ]);
            }
            }
           

            if(ISSET($data->token)) {

                $request->session()->regenerate();

                InertiaCognito::updateSession($data->token);

                return redirect()->route('dashboard')->with('success', 'Login successful.');

            }

        }
        catch (\App\Exceptions\ErrorException $e) {

            throw new InertiaException('Login/Login', $e);

        }

    }
    public function logout(Request $request)
    {
        try {

            $data=$this->userService->logoutUser($request->session());
            
            if($data)
            {
                return back()->with('success', 'Successfully logged out');
            }

        } catch (\App\Exceptions\ErrorException $e) {
            throw new InertiaException('Logout/Logout', $e);
        }
    }

    public function refreshAccess()
    {

        try {

            $data = $this->userService->refreshAccess();

            $resource = new LoginResource($data);

            return response($resource)
                ->header('RefreshToken', $data->token->RefreshToken)
                ->header('RefreshTokenExpiry', NOW()->addMonths(11)->unix())
                ->header('AccessExpiry', NOW()->addMinutes(60)->unix())
                ->header('Authorization', $data->token->AccessToken);

        } catch (\Error $e) {

            throw new ErrorException(message: 'ERROR', previous: $e);

        }

    }

    public function ForgotPage()
    {
        return Inertia::render('ForgotPassword/ForgotPassword');
    }

    public function forgot(SendOTPRequest $request)
    {
        try {
            $response = $this->userService->sendOTP($request->validated());
            return redirect()->route('forgot-password.otp')->with('message', 'OTP sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to send OTP.']);
        }
    }

    public function resetPassword(ResetPassword $request)
    {
        try {
            $response = $this->userService->resetUserPassword($request->validated());


            return Inertia::render('ForgotPassword/ChoosePassword', [
                'response' => $response
            ])->with('success', 'Password reset successfully!');
        } catch (\Exception $e) {

            return Redirect::back()->with('error', 'Failed to reset password. Please try again.');
        }
    }

    public function welcomeInvite()
    {

        return Inertia::render('Welcome/welcomeInvite');
    }
}
