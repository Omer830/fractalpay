<?php

namespace Modules\Auth\Services;
use Modules\Auth\Models\User;
use Modules\Auth\Jobs\SendOTP;
use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Enums\CommonKeys;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Events\UserCreated;
use Illuminate\Support\Facades\Config;
use App\Http\Middleware\InertiaCognito;
use Illuminate\Support\Facades\Redirect;
use App\Exceptions\InertiaException;
use Ellaisys\Cognito\Auth\RegistersUsers;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Repositories\HelperRepository;
use Modules\Invitation\Services\InvitedUserService;
use Modules\Auth\Interfaces\UserRepositoryInterface;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;



class UserService
{

    use RegistersUsers;

    protected $userRepository;
    protected $cognitoService;

    public function __construct(UserRepositoryInterface $userRepository, CognitoService $cognitoService,InvitedUserService $InvitedUserService)
    {
        $this->userRepository = $userRepository;
        $this->cognitoService = $cognitoService;
        $this->InvitedUserService = $InvitedUserService;
    }

    /**
     * Register a new user.
     *
     * @param array $data The user data
     *
     * @return User The created user
     *
     * @throws ErrorException
     */
    public function registerUser(array $data)
    {
        try {
            $data['name'] = $data['first_name'].' '.$data['last_name'];
            $data['email'] = strtolower($data['email']);

            $email = explode('@', $data['email']);

            //Todo not for production or live testing
//            $data['email'] =  $email[0] . '+'. rand(1,999) . '@' . $email[1];
//            $data['phone_number'] = $data['phone_number'] . rand(1,999);
                // Setting Session for User
            $password = $data['password'];
            session(['myPassword' => $password]);

            $response = $this->cognitoService->createUser($data);

            $data['sub'] = $this->cognitoService->getSubFromResponse(collect($response));

            $data['otp'] = HelperRepository::generateOTP();


            $user = $this->userRepository->create($data);
            if($data['referee_code'])
            {
                $this->InvitedUserService->acceptNotificationToInviter($user, $data['referee_code']);
            }

            SendOTP::dispatch($user, $data['otp'], $data['email']);
            // Setting Session for User
            $password = $data['password'];
            session(['myPassword' => $password]);
            $code = $data['referee_code'] ?? session()->get(CommonKeys::REFEREE_CODE->value);
            UserCreated::dispatch($user, $code);

            return $user;

        } catch (\Exception $e) {

            throw new ErrorException('UN_SUCCESSFUL', 500, $e);

        }

    }

    public function verifyCustomEmail(array $data, $type=null)
    {
        $user = $this->userRepository->findByEmailOrPhone($data);
        if (!$user) {
            throw new ErrorException('User not found', 404);
        }
        
        if (!$data['otp']) {
            throw new ErrorException('OTP is required', 400);
        }
        
        
        if ($type == 'forgetPassword') {
            return true;
        }
        
        
        $emailType = null;
        if (!empty($data['secondary_email'])) {
            $emailType = 'secondary_email';
        } elseif (!empty($data['alternative_email'])) {
            $emailType = 'alternative_email';
        } else {
            throw new ErrorException('Please specify an email to verify', 400);
        }
        
        $otpField = $emailType . '_otp';
        
        if ((int)$user->$otpField !== (int)$data['otp']) {
            throw new ErrorException('Incorrect OTP', 401);
        }
        
        $this->userRepository->verifyUser($user, $data);

        return Config::get('flashMessages.OTP_VERIFIED');
    }

        public function verifyOTP(array $data, $type=null)
    {
        try {     
            $user = $this->userRepository->findByEmailOrPhone($data);
           
            if (!$user || !$data['otp']) {        
                throw new ErrorException('NO_LOCAL_USER');
            }       
            
            if($type == 'forgetPassword') {                                                             
                return true;
            }        

            // Determine which OTP field to check
            $otpField = 'otp'; // default
            if (!empty($data['secondary_email'])) {
                $otpField = 'secondary_email_otp';
            } elseif (!empty($data['alternative_email'])) {
                $otpField = 'alternative_email_otp';
            }
              
            // Verify the OTP
            if ((int)$user->$otpField !== (int)$data['otp']) {         
                throw new ErrorException('INVALID_OTP');
            }          

            // Verify user on Cognito if it's primary email
            if(isset($data['email'])) {
                $this->cognitoService->verifyCognitoUser($data);
            }

            // Update verification status
            $this->userRepository->verifyUser($user, $data);

            // If it's primary email login, authenticate and return tokens
            if(isset($data['email'])) {
                $password = session('myPassword');
                session()->forget('myPassword');
                
                $myData = [
                    'email' => $data['email'],
                    'password' => $password,
                ];
                
                $result = $this->cognitoService->authenticateCognitoUser($myData);
                $accessToken = $result['AuthenticationResult']['AccessToken'] ?? null;

                if(isset($accessToken)) {
                    InertiaCognito::updateSession($accessToken);
                }

                if($refreshToken = $user->getRefreshToken()) {
                    $response = $this->cognitoService->getNewAccessToken($user->sub, $refreshToken->RefreshToken);
                    return (Object)[
                        'token' => $user->updateRefreshToken($refreshToken, $response['AuthenticationResult'] ?? []),
                        'user' => $user
                    ];
                }
            }

            return Config::get('flashMessages.OTP_VERIFIED');

        } catch (\Exception $e) {
            throw new ErrorException(message: 'UN_SUCCESSFUL', previous: $e);
        }
    }

    public function loginUser(array $data)
    {
       
        try {
            
            $user = $this->userRepository->findByEmailLogin($data['email']);
            
            if($user)
            {
                $data['email'] = $user->email;
            }else{

                $result = $this->cognitoService->authenticateCognitoUser($data);
          
                $accessToken = $result['AuthenticationResult']['AccessToken'] ?? null;

                if (isset($accessToken)) {
                    InertiaCognito::updateSession($accessToken);
                } else {
                    throw new InertiaException('Login/Login', new \Exception('Access token not returned from Cognito.'));
                }
                
                session([
                    'myPassword' => $data['password'],
                    'wrongOtp' => true,
                ]);
                $user = $this->userRepository->findByEmailOrPhoneNumber($data['email']);
                $this->sendOTP($data);

                return [
                    'otp_verified' => false,
                    'user' => $user,
                ];
            }

            $result = $this->cognitoService->authenticateCognitoUser($data);
          
            $accessToken = $result['AuthenticationResult']['AccessToken'] ?? null;

            if(ISSET($accessToken)) {

                InertiaCognito::updateSession($accessToken);

            }

            $user = $this->userRepository->findByEmail($data['email']);

            $user?->createRefreshToken($result['AuthenticationResult'] ?? []);
          
            return (Object)[
                'token' => $accessToken,
                'refreshToken'  => $result['AuthenticationResult']['RefreshToken'] ?? null,
                'user' =>   $user
            ];

        } catch (ValidationException $e) {

            throw new ErrorException('INVALID_LOGIN', 500, $e);

        } catch (\Exception $e) {

            throw new ErrorException('INVALID_LOGIN', 500, $e);

        }

    }

    public function logoutUser($session)
    {

            $session->regenerate();
            $session->invalidate();

            return true;
    }

    public function sendOTP(array $data)
    {
        $user = $this->userRepository->findByEmailOrPhone($data['email']);

        $otp = HelperRepository::generateOTP();

        $this->userRepository->update($user->id, ['otp' => $otp]);
        Log::debug('dispatching the job');

        SendOTP::dispatch($user, $otp, $data['email'] ?? null, $data['phone_number'] ?? null);

        return Config::get('flashMessages.OTP_SENT');

    }

    public function refreshAccess()
    {
//        try {


            if(ISSET(request()->refreshToken) && ISSET(request()->email)) {

                $user = $this->userRepository->findByEmailOrPhone(request()->email);

                $refreshToken = $user->getRefreshToken(request()->refreshToken);

            }
            else {

                $user = Auth::user();

                $refreshToken = $user->getRefreshToken();

            }

            if(!$refreshToken) {
                throw new ErrorException('INVALID_TOKEN');
            }

            $response = $this->cognitoService->getNewAccessToken($user->sub, $refreshToken?->RefreshToken);

            return (Object)[
                'token' => $user->updateRefreshToken($refreshToken, $response['AuthenticationResult'] ?? []),
                'user' =>   $user
            ];

//        }
//        catch (\Exception $e) {
//            throw new ErrorException('UN_SUCCESSFUL', 500, $e);
//        }
    }

    public function resetUserPassword(array $data)
    {

        if(!ISSET($data['otp'])) {
            throw new ErrorException('INVALID_OTP');
        }

        $user = $this->userRepository->findByEmailOrPhone($data);

        if($user->otp === $data['otp']) {
            $this->userRepository->verifyUser($user, $data);
        }

        $this->cognitoService->setUserPassword($data);

        return Config::get('flashMessages.PASSWORD_RESET');

    }

    public function changeUserPassword(array $data)
    {
        $accessToken = request()->bearerToken(); // Retrieve Bearer token from the request header

        if (!$accessToken) {
            throw new ErrorException('Access token is missing for the authenticated user');
        }

        $data['AccessToken'] = $accessToken;

        $this->cognitoService->changeUserPassword($data);

        $this->cognitoService->signOutUserGlobally($accessToken);

        $reauthenticateData = [
            'email'    => auth()->user()->email,
            'password' => $data['new_password'],
        ];

        $result         = $this->cognitoService->authenticateCognitoUser($reauthenticateData);

        $accessTokenNew = $result['AuthenticationResult']['AccessToken'] ?? null;

        if (isset($accessTokenNew)) {
            InertiaCognito::updateSession($accessTokenNew);
        }

        return (Object)[
            'token'            => $accessTokenNew,
            'refreshToken'     => $result['AuthenticationResult']['RefreshToken'] ?? null,
        ];
    }


    /**
     * Initialize custom attributes in user pool
     */
    public function initializeCustomAttributes(array $attributes): void
    {
        $formattedAttributes = array_map(function ($attr) {
            $attribute = [
                'Name' => $attr['name'], 
                'AttributeDataType' => $attr['type'],
                'Mutable' => $attr['mutable'] ?? true,
                'Required' => $attr['required'] ?? false,
            ];

            if ($attr['type'] === 'String') {
                $attribute['StringAttributeConstraints'] = [
                    'MaxLength' => (string)($attr['max_length'] ?? 2048),
                    'MinLength' => (string)($attr['min_length'] ?? 1)
                ];
            } elseif ($attr['type'] === 'Number') {
                $attribute['NumberAttributeConstraints'] = [
                    'MaxValue' => (string)($attr['max_value'] ?? '1000000000'),
                    'MinValue' => (string)($attr['min_value'] ?? '0')
                ];
            }

            return $attribute;
        }, $attributes);
        
        $this->cognitoService->addCustomAttributes($formattedAttributes);
    }

    public function updateUserCustomAttributes(string $email, array $attributes): array
    {
        DB::beginTransaction();
        try {

            $userAttributes = [];
            foreach ($attributes as $key => $value) {
                if (!is_string($key)) {
                    throw new \InvalidArgumentException('Attribute keys must be strings');
                }

                if (!in_array($key, ['secondary_email', 'alternative_email'])) {
                    throw new \InvalidArgumentException("Invalid attribute: {$key}");
                }

                $userAttributes[] = [
                    'Name' => "custom:{$key}",
                    'Value' => (string)$value
                ];
            }

            if (empty($userAttributes)) {
                throw new \InvalidArgumentException('No valid attributes provided');
            }

            $cognitoResult = $this->cognitoService->updateUserAttributes($email, $userAttributes);

            $user = $this->userRepository->findByEmail($email);
            if (!$user) {
                throw new \Exception("User not found");
            }

        
            $updateData = [
                'alternative_email' => $attributes['alternative_email'] ?? $user->alternative_email,
                'secondary_email' => $attributes['secondary_email'] ?? $user->secondary_email,
            ];

        
            if (isset($attributes['alternative_email']) && $attributes['alternative_email'] !== $user->alternative_email) {
                $updateData['alternative_email_otp'] = HelperRepository::generateOTP();
                $updateData['alternative_email_verified_at'] = null;
                SendOTP::dispatch($user, $updateData['alternative_email_otp'], $attributes['alternative_email'], 'alternative_email');
            }
            
            if (isset($attributes['secondary_email']) && $attributes['secondary_email'] !== $user->secondary_email) {
                $updateData['secondary_email_otp'] = HelperRepository::generateOTP();
                $updateData['secondary_email_verified_at'] = null;
                SendOTP::dispatch($user, $updateData['secondary_email_otp'], $attributes['secondary_email'], 'secondary_email');
            }

            $this->userRepository->update($user->id, $updateData);

            DB::commit();

            return array_merge($cognitoResult, [
                'local_updated' => true,
                'otp_sent' => isset($updateData['alternative_email_otp']) || isset($updateData['secondary_email_otp'])
            ]);

        } catch (\Exception $e) {

            DB::rollBack();
    throw $e;
        }
    }


    public function getAllVisits($id)
    {
        return $this->userRepository->getAllVisits($id);
    }

}
