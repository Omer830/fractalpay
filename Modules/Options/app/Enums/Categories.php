<?php

namespace Modules\Options\Enums;

enum Categories: string {

    case ROLES = 'roles';
    case COUNTRIES = 'countries';
    case STATES = 'states';
    case CITIES = 'cities';
    case DOCUMENT_CATEGORY = 'document_category';
    case DOCUMENTS = 'documents';
    case PAYMENT_METHODS = 'payment_methods';
    case INSURANCE_NAMES = 'insurance_names';
    case TERMS_PERIODS = 'terms_periods';
    case BILLS = 'bills';
    case TRANSACTION_CATEGORIES = 'transaction_categories';
    case TRANSACTIONS = 'transactions';
    case DOCTORS='doctors';
    case ORGANIZATION = 'organization';
    case VISIT_TYPE= 'visit_type';
    case PROVIDER_NUMBER = 'provider_number';
}