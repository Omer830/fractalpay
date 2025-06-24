<?php

namespace Modules\Auth\Enums;

enum CommonKeys : string {

    case Authorization = 'Authorization';
    case AccessExpiry = 'access_expiry';

    case CACHE_USER = 'cache_user';
    case CACHE_USER_HASH = 'cache_user_hash';

    case REFEREE_CODE = 'referee_code';
    case USER_NAME = 'user_name';
    case UNIQUE_CODE = 'unique_code';

}