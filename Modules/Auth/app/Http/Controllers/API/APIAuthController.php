<?php

namespace Modules\Auth\Http\Controllers\API;

use Illuminate\Http\Request;


use Modules\UserKyc\DTO\UserDTO;
use App\Exceptions\ErrorException;
use Modules\Auth\Enums\CommonKeys;
use Modules\Auth\Services\UserService;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\ResetPassword;
use Modules\Auth\Http\Requests\ChangePassword;
use Modules\Auth\Http\Requests\SendOTPRequest;
use Modules\Auth\Http\Resources\LoginResource;
use Modules\Auth\Http\Requests\RegisterRequest;
    use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Requests\VerifyOTPRequest;
use Modules\UserKyc\Services\UserProfileService;
use Modules\Auth\Http\Resources\RegisterResource;
use Modules\Auth\Contracts\AuthControllerInterface;
use Modules\Auth\Http\Requests\VerifyCustomEmailRequest;
use Modules\Auth\Http\Requests\AwsCustomAttributeRequest;
use Modules\Auth\Http\Requests\ChangeAlternativeSecondaryEmail;

class APIAuthController extends AuthController implements AuthControllerInterface
{

    private UserService $userService;

    public function __construct(UserService $userService,private UserProfileService $UserProfileService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function register(RegisterRequest $request)
    {
        
        return new RegisterResource($this->userService->registerUser($request->validated()));
    }

    public function sendOTP(SendOTPRequest $request)
    {
        return $this->userService->sendOTP($request->validated());
    }

    public function verifyOTP(VerifyOTPRequest $request)
    {
        $data = $this->userService->verifyOTP($request->validated());

        if(is_array($data))
        {
            return $data;
        }

        $resource = new LoginResource($data);

        return response($resource)
            ->header('AccessExpiry', NOW()->addMinutes(55)->unix())
            ->header('Authorization', $data->token->AccessToken);
    }
    public function verifyCustomEmail(VerifyCustomEmailRequest $request)
    {
        try {
            $data = $this->userService->verifyCustomEmail($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
          
            \Log::error('OTP Verification Error: '.$e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), 
                'error_code' => $e->getCode()
            ], $e->getCode() >= 400 && $e->getCode() < 500 ? $e->getCode() : 400);
        }
    }

    public function login(LoginRequest $request)
    {

        try {
            
            $data = $this->userService->loginUser($request->validated());

            $resource = new LoginResource($data);

            return response($resource)
                ->header('RefreshToken', $data->refreshToken)
                ->header('RefreshTokenExpiry', NOW()->addDays(29)->unix())
                ->header('AccessExpiry', NOW()->addMinutes(30)->unix())
                ->header('Authorization', $data->token);

        }
        catch (\Error $e) {

            throw new ErrorException(message: 'ERROR', previous: $e);

        }


    }
    public function logout(Request $request)
    {
        try {
        
            $data=$this->userService->logoutUser($request->session());
            
            return $data;
        } catch (\Error $e) {
            throw new ErrorException(message: 'ERROR', previous: $e);
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

    public function forgot(SendOTPRequest $request)
    {
        return $this->userService->sendOTP($request->validated());
    }

    public function resetPassword(ResetPassword $request)
    {
        return $this->userService->resetUserPassword($request->validated());
    }

    public function changePassword(ChangePassword $request)
    {
        return $this->userService->changeUserPassword($request->validated());
    }

    public function initializeCustomAttributes(AwsCustomAttributeRequest $request)
    {
        try {
            $this->userService->initializeCustomAttributes($request->validated()['attributes']);
            return response()->json(['message' => 'Custom attributes initialized successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateAlternativeSecondaryEmail(ChangeAlternativeSecondaryEmail $request)
    {
        try {
            $result = $this->userService->updateUserCustomAttributes(
                $request->user()->email,
                $request->validated()
            );

            return response()->json([
                'message' => 'Attributes updated successfully',
                'updated_attributes' => $result['updated_attributes'],
                'timestamp' => $result['timestamp']
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => json_decode($e->getMessage(), true) ?? $e->getMessage()
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'aws_error' => str_contains($e->getMessage(), 'AWS Cognito error') 
                            ? $e->getMessage() : null
            ], 500);
        }
    }


}
