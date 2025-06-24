<?php

use Modules\Options\Enums\Attributes;

return [

    'options' => [

        /** category */
        'countries' =>  [

            'australia' =>  [
                Attributes::COUNTRY_CODE->value => 'au',
                Attributes::COUNTRY_DIAL_CODE->value => '+61',
                Attributes::POSTAL_PATTERN->value   =>  '/^\d{4}$/',
                Attributes::CURRENCY->value => \Modules\Wallet\Enums\Currency::AUD->value,
            ],
            'usa' => [
                Attributes::COUNTRY_CODE->value => 'us',
                Attributes::COUNTRY_DIAL_CODE->value => '+1',
                Attributes::POSTAL_PATTERN->value   =>  '/^\d{5}(-\d{4})?$/',
                Attributes::CURRENCY->value => \Modules\Wallet\Enums\Currency::USD->value,
            ],
        ],

        'documents' =>  [

            'birth-certificate' =>  [
                Attributes::NUMBER_OF_FILES->value => 1,
                Attributes::DOCUMENT_POINTS->value => 50,
                Attributes::DOCUMENT_ICONS->value => 'diploma',
            ],
            "passport" => [
                Attributes::NUMBER_OF_FILES->value => 1,
                Attributes::DOCUMENT_POINTS->value => 50,
                Attributes::DOCUMENT_ICONS->value => 'passport',

            ],
            "birth-card" => [
                Attributes::NUMBER_OF_FILES->value => 1,
                Attributes::DOCUMENT_POINTS->value => 40,
                Attributes::DOCUMENT_ICONS->value => 'postal_doc',
            ],
            "citizenship-certificate"   => [
                Attributes::NUMBER_OF_FILES->value => 1,
                Attributes::DOCUMENT_POINTS->value => 50,
                Attributes::DOCUMENT_ICONS->value => 'diploma',
            ],
            "national-id"   => [
                Attributes::NUMBER_OF_FILES->value => 1,
                Attributes::DOCUMENT_POINTS->value => 50,
                Attributes::DOCUMENT_ICONS->value => 'identity-card-icon',
            ],
            "driver-license"    => [
                Attributes::NUMBER_OF_FILES->value => 2,
                Attributes::DOCUMENT_POINTS->value => 40,
                Attributes::DOCUMENT_ICONS->value => 'driver-licence-icon',
            ],
            "id-card"   => [
                Attributes::NUMBER_OF_FILES->value => 2,
                Attributes::DOCUMENT_POINTS->value => 40,
                Attributes::DOCUMENT_ICONS->value => 'id-card',
            ],
            "student-card"  => [
                Attributes::NUMBER_OF_FILES->value => 2,
                Attributes::DOCUMENT_POINTS->value => 30,
                Attributes::DOCUMENT_ICONS->value => 'student-car',
            ],
            "mortgage-document" => [
                Attributes::NUMBER_OF_FILES->value => 2,
                Attributes::DOCUMENT_POINTS->value => 20,
                Attributes::DOCUMENT_ICONS->value => 'mortgage-document',
            ],
            "land-titles-office-record" => [
                Attributes::NUMBER_OF_FILES->value => 2,
                Attributes::DOCUMENT_POINTS->value => 20,
                Attributes::DOCUMENT_ICONS->value => 'office-record',
            ],
            "council-rates-notice"  => [
                Attributes::NUMBER_OF_FILES->value => 2,
                Attributes::DOCUMENT_POINTS->value => 20,
                Attributes::DOCUMENT_ICONS->value => 'council-rates-notice',
            ],
            "foreign-drivers-license" => [
                Attributes::NUMBER_OF_FILES->value => 2,
                Attributes::DOCUMENT_POINTS->value => 30,
                Attributes::DOCUMENT_ICONS->value => 'foreign-drivers-icon',
            ],
            "medicare-card" => [
                Attributes::NUMBER_OF_FILES->value => 2,
                Attributes::DOCUMENT_POINTS->value => 20,
                Attributes::DOCUMENT_ICONS->value => 'medical-card-icon',
            ],

            'profile_image' =>  [
                Attributes::NUMBER_OF_FILES->value => 1
            ],

            'insurance-certificate' =>  [
                Attributes::NUMBER_OF_FILES->value => 1,
            ]

        ]
    ],
    'user' => [
        'profileStatus' =>  [

            'profile' =>  [
                Attributes::PROFILE_NAME->value,
                Attributes::PROFILE_ADDRESS->value ,
                Attributes::PROFILE_POSTAL_CODE->value,
                Attributes::PROFILE_COUNTRY->value ,
                Attributes::PROFILE_STATE->value ,
                Attributes::PROFILE_GENDER->value ,
                Attributes::PROFILE_DOB->value ,
                Attributes::PROFILE_USERNAME->value,
                Attributes::PROFILE_IMG->value,

            ],
        ],
    ],

    'keys'  =>  [

        'options'   => [
            'countries' =>  [
                Attributes::COUNTRY_CODE->value,
                Attributes::COUNTRY_DIAL_CODE->value,
                Attributes::POSTAL_PATTERN->value,
                Attributes::CURRENCY->value,
            ],
            'documents' =>  [
                Attributes::NUMBER_OF_FILES->value,
                Attributes::DOCUMENT_POINTS->value,
                Attributes::DOCUMENT_ICONS->value,

            ]
            ],
            'user_profile' => [
                Attributes::PROFILE_NAME->value,
                Attributes::PROFILE_ADDRESS->value ,
                Attributes::PROFILE_POSTAL_CODE->value,
                Attributes::PROFILE_COUNTRY->value ,
                Attributes::PROFILE_STATE->value ,
                Attributes::PROFILE_GENDER->value ,
                Attributes::PROFILE_DOB->value ,
                Attributes::PROFILE_USERNAME->value,
            ],

    ]

];