<?php
namespace Modules\Options\Enums;

enum Attributes : string {

    case COUNTRY_CODE = 'country_code';
    case COUNTRY_DIAL_CODE = 'country_dial_code';
    case POSTAL_PATTERN = 'postal_pattern';
    case NUMBER_OF_FILES = 'number_of_files';
    case DOCUMENT_POINTS = 'document_points';
    case DOCUMENT_ICONS = 'document_icons';
    case PROFILE_NAME = 'profile_name';
    case PROFILE_ADDRESS = 'profile_address';
    case PROFILE_POSTAL_CODE = 'profile_postal_code';
    case PROFILE_COUNTRY = 'profile_country';
    case PROFILE_STATE = 'profile_state';
    case PROFILE_GENDER = 'profile_gender';
    case PROFILE_DOB = 'profile_dob';
    case PROFILE_USERNAME = 'profile_username';
    case PROFILE_IMG = 'profile_img';
    case CURRENCY = 'currency';

}