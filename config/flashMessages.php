<?php



return [

    /** Default Message */

        'DEFAULT_MESSAGE'   =>  ['code' => 200, 'name' => 'DEFAULT_MESSAGE', 'message' => 'If you get an error, please contact us.', 'status' => 200, 'success' => true],
    /** Info Messages */
        'SUCCESS' => ['code' => 201, 'name' => 'SUCCESS', 'message' => 'Request completed successfully', 'statusCode' => 200, 'success' => true],
        'ALREADY_HAS_ACCESS' => ['code' => 202, 'name' => 'ALREADY_HAS_ACCESS', 'message' => 'Already has access', 'statusCode' => 200, 'success' => true],
        'UPDATED' => ['code' => 203, 'name' => 'UPDATED', 'message' => 'Record updated successfully', 'statusCode' => 200, 'success' => true],
        'NOTHING_CHANGED' => ['code' => 204, 'name' => 'NOTHING_CHANGED', 'message' => 'Nothing changed, check your request', 'statusCode' => 200, 'success' => true],
        'NO_RECORD_FOUND' => ['code' => 207, 'name' => 'NO_RECORD_FOUND', 'message' => 'No record found', 'statusCode' => 200, 'success' => true],
        'CHECK_REQUEST' => ['code' => 208, 'name' => 'CHECK_REQUEST', 'message' => 'Check your request and try again', 'statusCode' => 200, 'success' => true],
        'EMAIL_SENT' => ['code' => 209, 'name' => 'EMAIL_SENT', 'message' => 'Email sent if address matches our records', 'statusCode' => 200, 'success' => true],
        'NO_FILE_IN_UPLOAD' => ['code' => 218, 'name' => 'NO_FILE_IN_UPLOAD', 'message' => 'No file found in uploads, please upload and retry', 'statusCode' => 200, 'success' => true],
        'OTP_SENT'  =>  ['code' => 219, 'name' => 'OTP_SENT', 'message' => 'We sent you a one time passcode', 'statusCode' => 200, 'success' => true],
        'OTP_VERIFIED'  =>  ['code' => 220, 'name' => 'OTP_VERIFIED', 'message' => 'OTP verified successfully', 'statusCode' => 200, 'success' => true],
        'PASSWORD_RESET'  =>  ['code' => 221, 'name' => 'PASSWORD_RESET', 'message' => 'Password reset successfully', 'statusCode' => 200, 'success' => true],
        'INVITATION_SENT'   =>  ['code' => 225, 'name' => 'INVITATION_SENT', 'message' => 'Invitation sent successfully', 'statusCode' => 200, 'success' => true],
        'INVITATION_OPENED'   =>  ['code' => 226, 'name' => 'INVITATION_OPENED', 'message' => 'Invitation opened', 'statusCode' => 200, 'success' => true],
        'INVITATION_ACCEPTED'   =>  ['code' => 226, 'name' => 'INVITATION_ACCEPTED', 'message' => 'Invitation accepted', 'statusCode' => 200, 'success' => true],





    /** Error Messages */
        'NO_ACCESS' => ['code' => 401, 'name' => 'NO_ACCESS', 'message' => 'Access restricted', 'statusCode' => 405],
        'NOT_ALLOWED' => ['code' => 402, 'name' => 'NOT_ALLOWED', 'message' => 'Permission denied for this task', 'statusCode' => 405],
        'ONE_OR_MANY_NOT_ALLOWED' => ['code' => 403, 'name' => 'ONE_OR_MANY_NOT_ALLOWED', 'message' => 'Access denied for one or more records', 'statusCode' => 405],
        'WRONG_INVITE' => ['code' => 404, 'name' => 'WRONG_INVITE', 'message' => 'Error while sending invite', 'statusCode' => 400],
        'NO_APP_ACCESS' => ['code' => 407, 'name' => 'NO_APP_ACCESS', 'message' => 'No access to this app, check app identifier', 'statusCode' => 400],
        'NAME_ALREADY_EXISTS' => ['code' => 409, 'name' => 'NAME_ALREADY_EXISTS', 'message' => 'Name already exists, choose another', 'statusCode' => 409],
        'NOT_FOUND' => ['code' => 411, 'name' => 'NOT_FOUND', 'message' => 'Resource not found', 'statusCode' => 404],
        'CANT_DO_THAT' => ['code' => 412, 'name' => 'CANT_DO_THAT', 'message' => 'Invalid request, check parameters', 'statusCode' => 403],
        'RELATION_NOT_FOUND' => ['code' => 413, 'name' => 'RELATION_NOT_FOUND', 'message' => 'Relation not found, check and retry', 'statusCode' => 404],
        'MISSING_PARAMETER' => ['code' => 414, 'name' => 'MISSING_PARAMETER', 'message' => 'Required parameters missing, check and retry', 'statusCode' => 422],
        'UN_SUCCESSFUL' => ['code' => 415, 'name' => 'UN_SUCCESSFUL', 'message' => 'Operation failed, try again or contact admin', 'statusCode' => 422],
        'INVALID_LOGIN' => ['code' => 416, 'name' => 'INVALID_LOGIN', 'message' => 'Invalid email or password', 'errors' => ['email' => 'Invalid login details'], 'statusCode' => 422],
        'ALREADY_EXISTS' => ['code' => 417, 'name' => 'ALREADY_EXISTS', 'message' => 'Address already exists, check request', 'statusCode' => 409],
        'NOT_ENOUGH_INFO' => ['code' => 418, 'name' => 'NOT_ENOUGH_INFO', 'message' => 'Insufficient information, check address', 'statusCode' => 404],
        'NO_LOCAL_USER' => ['code' => 419, 'name' => 'NO_LOCAL_USER', 'message' => 'No user found, contact admin', 'statusCode' => 404],
        'USER_DELETED' => ['code' => 421, 'name' => 'USER_DELETED', 'message' => 'User deleted, contact admin', 'statusCode' => 402],
        'FILE_NOT_FOUND' => ['code' => 422, 'name' => 'FILE_NOT_FOUND', 'message' => 'Invalid file, check and retry', 'statusCode' => 404],
        'INVALID_REQUEST' => ['code' => 427, 'name' => 'INVALID_REQUEST', 'message' => 'Invalid request, requires application/json header', 'statusCode' => 400],
        'ERROR'     =>  ['code' => 428, 'name' => 'ERROR', 'message' => 'Something Went wrong, please try again or contact customer support team', 'statusCode' => 400],
        'INVALID_OTP'     =>  ['code' => 429, 'name' => 'ERROR', 'message' => 'Incorrect OTP provided, please check and try again', 'statusCode' => 401],
        'INVALID_TOKEN'     =>  ['code' => 430, 'name'  =>  'INVALID_TOKEN', 'message' => 'Token is invalid. Please update the page. If the issue continues, consider logging out and signing back in.', 'statusCode' => 401],
        'ONLY_STRING_ALLOWED'   =>  ['code' => 431, 'name'  =>  'ONLY_STRING_ALLOWED', 'message' => 'Please check your payload, only string allowed for parent', 'statusCode' => 405],
        'INVITATION_NOT_FOUND' => ['code' => 432, 'name' => 'NOT_FOUND', 'message' => 'No invitation found', 'error' => 'No invitation found, matching your request', 'statusCode' => 404],
        'INSUFFICIENT_FUND' => ['code' => 435, 'name' => 'INSUFFICIENT_FUND', 'message' => 'Insufficient fund in your account', 'error' => 'No invitation found, matching your request', 'statusCode' => 400],
        'STATUS_CHANGE_NOT_ALLOWED' => ['code' => 436, 'name' => 'STATUS_CHANGE_NOT_ALLOWED', 'message' => 'Change of transaction status is not allowed', 'error' => 'Change of transaction status is not allowed', 'statusCode' => 405],
        'INVALID_PAYMENT_METHOD_TYPE' => ['code' => 412, 'name' => 'INVALID_PAYMENT_METHOD_TYPE', 'message' => 'Invalid payment method type selected', 'error' => 'Invalid payment method type selected', 'statusCode' => 404],
        'INVALID_RECEIVER' => ['code' => 413, 'name' => 'INVALID_RECEIVER', 'message' => 'Invalid receiver or no receiver found', 'error' => 'Invalid receiver or no receiver found', 'statusCode' => 404],
];
