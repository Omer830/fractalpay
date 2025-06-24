<?php

namespace Modules\Auth\Services;

use App\Exceptions\ErrorException;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class CognitoService
{
    protected $cognitoClient;
    const ATTRIBUTE_EMAIL             =  'email';
    const ATTRIBUTE_NAME              =  'name';
    const ATTRIBUTE_PHONE_NUMBER      =  'phone_number';
    const ATTRIBUTE_SUB               =  'sub';
    const ATTRIBUTE_PASSWORD          =  'password';
    const AccessToken                 =  'AccessToken';
    const OLD_PASSWORD                =  'old_password';
    const NEW_PASSWORD                =  'new_password';
    const ATTRIBUTE_SECONDARY_EMAIL   = 'custom:secondary_email';
    const ATTRIBUTE_ALTERNATIVE_EMAIL = 'custom:alternative_email';
    private mixed $poolId;
    private mixed $clientId;
    private mixed $clientSecret;

    public function __construct()
    {
        $this->cognitoClient = app('aws.cognito.client');
        $this->poolId= config('services.cognito.user_pool_id');
        $this->clientId = config('services.cognito.client_id');
        $this->clientSecret = config('services.cognito.client_secret');
    }

     /**
     * Add custom attributes to the user pool schema
     */
    public function addCustomAttributes(array $attributes)
    {
        try {
            return $this->cognitoClient->addCustomAttributes([
                'UserPoolId' => $this->poolId,
                'CustomAttributes' => $attributes
            ]);
        } catch (CognitoIdentityProviderException $e) {
            throw new \Exception("Failed to add custom attributes: ".$e->getAwsErrorMessage());
        }
    }

    public function updateUserAttributes(string $email, array $userAttributes): array
    {
        try {
            $response = $this->cognitoClient->adminUpdateUserAttributes([
                'UserPoolId' => $this->poolId,
                'Username' => $email,
                'UserAttributes' => $userAttributes
            ]);

            return [
                'success' => true,
                'updated_attributes' => array_column($userAttributes, 'Name'),
                'timestamp' => now()->toDateTimeString()
            ];
            
        } catch (CognitoIdentityProviderException $e) {
            
            throw new \Exception("AWS Cognito error: ".$e->getAwsErrorMessage());
        }
    }

  

    public function createUser(array $data)
    {
        try {

            $response = $this->cognitoClient->adminCreateUser([
                'UserPoolId' => $this->poolId,
                'Username' => $data['email'],
                'UserAttributes' => [
                    [
                        'Name' => self::ATTRIBUTE_EMAIL,
                        'Value' => $data[self::ATTRIBUTE_EMAIL],
                    ],
                    [
                        'Name' => self::ATTRIBUTE_NAME,
                        'Value' => $data[self::ATTRIBUTE_NAME],
                    ],

                ],
                'TemporaryPassword' => $data['password'],
                'MessageAction' => config('services.cognito.message_action', 'SUPPRESS'),
            ]);

            $this->setUserPassword($data);

            return $response;

        } catch (CognitoIdentityProviderException $e) {
            $awsErrorCode = $e->getAwsErrorCode();

            if ($awsErrorCode === 'UsernameExistsException') {

                throw new \Exception('The email is already registered. Please use a different email.', 409);
            } elseif ($awsErrorCode === 'InvalidPasswordException') {
                throw new \Exception('The password does not meet the required criteria. Please choose a stronger password.', 400);
            } else {
                throw new \Exception($e->getAwsErrorMessage(), $e->getStatusCode());
            }

        } catch (\Exception $e) {

            throw new \Exception($e->getMessage(), 500);
        }
    }


    public function setUserPassword($data)
    {
        $this->cognitoClient->adminSetUserPassword([
            'UserPoolId' => $this->poolId,
            'Username' => $data[self::ATTRIBUTE_EMAIL],
            'Password' => $data[self::ATTRIBUTE_PASSWORD],
            'Permanent' => true,
        ]);
    }

    public function changeUserPassword($data)
    {
        try {
            $this->cognitoClient->changePassword([
                'AccessToken' => $data[self::AccessToken],
                'PreviousPassword' => $data[self::OLD_PASSWORD],
                'ProposedPassword' => $data[self::NEW_PASSWORD],
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to change password: ' . $e->getMessage());
        }
    }

    public function signOutUserGlobally(string $accessToken): void
    {
        $this->cognitoClient->globalSignOut([
            'AccessToken' => $accessToken,
        ]);
    }

    public function getSubFromResponse(Collection $response)
    {

        // Check if the response is valid and contains the User attributes
        if ($response->has('User') && is_array($response->get('User')['Attributes'])) {
            $attributes = collect($response->get('User')['Attributes']);
            return $attributes->firstWhere('Name', self::ATTRIBUTE_SUB)['Value'] ?? null;
        }

        if ($response->has('UserAttributes')) {
            $attributes = collect($response->get('UserAttributes'));
            return $attributes->firstWhere('Name', self::ATTRIBUTE_SUB)['Value'] ?? null;
        }

        return null;
    }

    public function authenticateCognitoUser(array $data)
    {

        try {

            $email = $data['email'];
            $password = $data['password'];
            $secretHash = $this->calculateSecretHash($email);

            $response = $this->cognitoClient->adminInitiateAuth([
                'AuthFlow' => 'ADMIN_USER_PASSWORD_AUTH',
                'ClientId' => $this->clientId,
                'UserPoolId' => $this->poolId,
                'AuthParameters' => [
                    'USERNAME' => $email,
                    'PASSWORD' => $password,
                    'SECRET_HASH' => $secretHash,
                ],
            ]);
           
            return $response->toArray();

        } catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {

            throw new \ErrorException('UN_SUCCESSFUL', previous: $e);

        }

        catch (\Exception $e) {

            throw new \ErrorException('UN_SUCCESSFUL', previous: $e);

        }
    }

    public function verifyCognitoUser(array $data)
    {

        try {

            // Setting the user status back to unconfirmed to avoid auto-confirmation
            $this->cognitoClient->adminUpdateUserAttributes([
                'UserPoolId' => $this->poolId,
                'Username' => $data[self::ATTRIBUTE_EMAIL],
                'UserAttributes' => [
                    [
                        'Name' => 'email_verified',
                        'Value' => 'true'
                    ]
                ],
            ]);

            return true;

        }
        catch (\Exception $e) {

            throw new \ErrorException($e);

        }

    }

    public function getUser($token)
    {

        try {
            return $this->getSubFromResponse(collect($this->cognitoClient->getUser([
                'AccessToken' => $token,
            ])));
        }
        catch (\Exception $e) {
            throw new ErrorException('UN_SUCCESSFUL', previous: $e);
        }

    }

    public function getNewAccessToken($email, $refreshToken)
    {


//        try {
            $secretHash = $this->calculateSecretHash($email);

            $response = $this->cognitoClient->adminInitiateAuth([
                'AuthFlow' => 'REFRESH_TOKEN_AUTH',
                'AuthParameters' => [
                    'USERNAME' => $email,
                    'REFRESH_TOKEN' => $refreshToken,
                    'SECRET_HASH' =>  $secretHash
                ],
                'ClientId' => $this->clientId,
                'UserPoolId' => $this->poolId,
            ]);

            return $response->toArray();

//        } catch (AwsException $e) {
//
//            throw new ErrorException(message: 'UN_SUCCESSFUL', previous: $e);
//
//        }
//        catch (\Exception $e) {
//
//            throw new \ErrorException(message: 'UN_SUCCESSFUL', previous: $e);
//
//        }

    }

    protected function calculateSecretHash($username)
    {
        $message = $username . $this->clientId;
        $hash = hash_hmac('sha256', $message, $this->clientSecret, true);
        return base64_encode($hash);
    }


}
