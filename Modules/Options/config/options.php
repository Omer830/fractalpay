<?php

use Modules\Options\Enums\Categories;

return [

    Categories::ROLES->value => [
        ['name' => 'Admin', 'slug' => 'admin'],
        ['name' => 'View Only', 'slug' => 'view-only']
    ],

    Categories::COUNTRIES->value => [
        ['name' => "Australia", 'slug' => "australia"],
        ['name' => "USA", 'slug' => "usa"]
    ],

    Categories::STATES->value => [
        ['name' => "New South Wales", 'slug' => "new-south-wales", 'parent_slug' => "australia", 'parent_category'  => Categories::COUNTRIES->value],
        ['name' => "Victoria", 'slug' => "victoria", 'parent_slug' => "australia", 'parent_category'  => Categories::COUNTRIES->value],
        ['name' => "Queensland", 'slug' => "queensland", 'parent_slug' => "australia", 'parent_category'  => Categories::COUNTRIES->value],
        ['name' => "Western Australia", 'slug' => "western-australia", 'parent_slug' => "australia", 'parent_category'  => Categories::COUNTRIES->value],
        ['name' => "South Australia", 'slug' => "south-australia", 'parent_slug' => "australia", 'parent_category'  => Categories::COUNTRIES->value],
        ['name' => "Tasmania", 'slug' => "tasmania", 'parent_slug' => "australia", 'parent_category'  => Categories::COUNTRIES->value],
        ['name' => "Northern Territory", 'slug' => "northern-territory", 'parent_slug' => "australia", 'parent_category'  => Categories::COUNTRIES->value],
        ['name' => "Australian Capital Territory", 'slug' => "australian-capital-territory", 'parent_slug' => "australia", 'parent_category'  => Categories::COUNTRIES->value],
    ],

    // Cities
    Categories::CITIES->value => [
        ['name' => "Sydney", 'slug' => "sydney", 'parent_slug' => "new-south-wales", 'parent_category'  => Categories::STATES->value],
        ['name' => "Newcastle", 'slug' => "newcastle", 'parent_slug' => "new-south-wales", 'parent_category'  => Categories::STATES->value],
        ['name' => "Wollongong", 'slug' => "wollongong", 'parent_slug' => "new-south-wales", 'parent_category'  => Categories::STATES->value],
        ['name' => "Melbourne", 'slug' => "melbourne", 'parent_slug' => "victoria", 'parent_category'  => Categories::STATES->value],
        ['name' => "Geelong", 'slug' => "geelong", 'parent_slug' => "victoria", 'parent_category'  => Categories::STATES->value],
        ['name' => "Ballarat", 'slug' => "ballarat", 'parent_slug' => "victoria", 'parent_category'  => Categories::STATES->value],
        ['name' => "Bendigo", 'slug' => "bendigo", 'parent_slug' => "victoria", 'parent_category'  => Categories::STATES->value],
        ['name' => "Shepparton", 'slug' => "shepparton", 'parent_slug' => "victoria", 'parent_category'  => Categories::STATES->value],
        ['name' => "Brisbane", 'slug' => "brisbane", 'parent_slug' => "queensland", 'parent_category'  => Categories::STATES->value],
        ['name' => "Gold Coast", 'slug' => "gold-coast", 'parent_slug' => "queensland", 'parent_category'  => Categories::STATES->value],
        ['name' => "Townsville", 'slug' => "townsville", 'parent_slug' => "queensland", 'parent_category'  => Categories::STATES->value],
        ['name' => "Cairns", 'slug' => "cairns", 'parent_slug' => "queensland", 'parent_category'  => Categories::STATES->value],
        ['name' => "Toowoomba", 'slug' => "toowoomba", 'parent_slug' => "queensland", 'parent_category'  => Categories::STATES->value],
        ['name' => "Mackay", 'slug' => "mackay", 'parent_slug' => "queensland", 'parent_category'  => Categories::STATES->value],
        ['name' => "Perth", 'slug' => "perth", 'parent_slug' => "western-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Fremantle", 'slug' => "fremantle", 'parent_slug' => "western-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Mandurah", 'slug' => "mandurah", 'parent_slug' => "western-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Kalgoorlie", 'slug' => "kalgoorlie", 'parent_slug' => "western-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Albany", 'slug' => "albany", 'parent_slug' => "western-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Bunbury", 'slug' => "bunbury", 'parent_slug' => "western-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Geraldton", 'slug' => "geraldton", 'parent_slug' => "western-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Adelaide", 'slug' => "adelaide", 'parent_slug' => "south-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Mount Gambier", 'slug' => "mount-gambier", 'parent_slug' => "south-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Whyalla", 'slug' => "whyalla", 'parent_slug' => "south-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Port Augusta", 'slug' => "port-augusta", 'parent_slug' => "south-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Port Lincoln", 'slug' => "port-lincoln", 'parent_slug' => "south-australia", 'parent_category'  => Categories::STATES->value],
        ['name' => "Hobart", 'slug' => "hobart", 'parent_slug' => "tasmania", 'parent_category'  => Categories::STATES->value],
        ['name' => "Launceston", 'slug' => "launceston", 'parent_slug' => "tasmania", 'parent_category'  => Categories::STATES->value],
        ['name' => "Devonport", 'slug' => "devonport", 'parent_slug' => "tasmania", 'parent_category'  => Categories::STATES->value],
        ['name' => "Burnie", 'slug' => "burnie", 'parent_slug' => "tasmania", 'parent_category'  => Categories::STATES->value],
        ['name' => "Darwin", 'slug' => "darwin", 'parent_slug' => "northern-territory", 'parent_category'  => Categories::STATES->value],
        ['name' => "Alice Springs", 'slug' => "alice-springs", 'parent_slug' => "northern-territory", 'parent_category'  => Categories::STATES->value],
        ['name' => "Palmerston", 'slug' => "palmerston", 'parent_slug' => "northern-territory", 'parent_category'  => Categories::STATES->value],
        ['name' => "Katherine", 'slug' => "katherine", 'parent_slug' => "northern-territory", 'parent_category'  => Categories::STATES->value],
        ['name' => "Canberra", 'slug' => "canberra", 'parent_slug' => "australian-capital-territory", 'parent_category'  => Categories::STATES->value],
    ],

    //Documents category
    Categories::DOCUMENT_CATEGORY->value => [
        ['name' => 'Primary', 'slug' => 'primary-au', 'parent_slug' => 'australia', 'parent_category' => Categories::COUNTRIES->value],
        ['name' => 'Secondary', 'slug' => 'secondary-au', 'parent_slug' => 'australia', 'parent_category' => Categories::COUNTRIES->value],
        ['name' => 'Other', 'slug' => 'other-au', 'parent_slug' => 'australia', 'parent_category' => Categories::COUNTRIES->value],
        ['name' => 'Insurance', 'slug' => 'insurance-au', 'parent_slug' => 'australia', 'parent_category' => Categories::COUNTRIES->value],
    ],

    // Documents
    Categories::DOCUMENTS->value => [
            ['name' => "Birth Certificate", 'slug' => "birth-certificate", 'points' => 50, 'sides_required' => 1, 'parent_slug' => 'primary-au'],
            ['name' => "Passport", 'slug' => "passport", 'points' => 50, 'sides_required' => 1, 'parent_slug' => 'primary-au'],
            ['name' => "Birth Card", 'slug' => "birth-card", 'points' => 40, 'sides_required' => 1, 'parent_slug' => 'primary-au'],
            ['name' => "Citizenship Certificate", 'slug' => "citizenship-certificate", 'points' => 50, 'sides_required' => 1, 'parent_slug' => 'primary-au'],
            ['name' => "National ID", 'slug' => "national-id", 'points' => 50, 'sides_required' => 1, 'parent_slug' => 'primary-au'],
            ['name' => "Driver License", 'slug' => "driver-license", 'points' => 40, 'sides_required' => 2, 'parent_slug' => 'secondary-au'],
            ['name' => "ID Card", 'slug' => "id-card", 'points' => 40, 'sides_required' => 2, 'parent_slug' => 'secondary-au'],
            ['name' => "Student Card", 'slug' => "student-card", 'points' => 30, 'sides_required' => 1, 'parent_slug' => 'secondary-au'],
            ['name' => "Mortgage Document", 'slug' => "mortgage-document", 'points' => 20, 'sides_required' => 2, 'parent_slug' => 'secondary-au'],
            ['name' => "Land Titles Office Record", 'slug' => "land-titles-office-record", 'points' => 20, 'sides_required' => 2, 'parent_slug' => 'other-au'],
            ['name' => "Council Rates Notice", 'slug' => "council-rates-notice", 'points' => 20, 'sides_required' => 1, 'parent_slug' => 'other-au'],
            ['name' => "Foreign Driver's License", 'slug' => "foreign-drivers-license", 'points' => 30, 'sides_required' => 2, 'parent_slug' => 'other-au'],
            ['name' => "Medicare Card", 'slug' => "medicare-card", 'points' => 20, 'sides_required' => 1, 'parent_slug' => 'other-au'],
            ['name' => "Insurance Certificate", 'slug' => "insurance-certificate", 'sides_required' => 1, 'parent_slug' => 'insurance-au'],
            ['name' => "Profile Image", 'slug'  => "profile-image"]
    ],

    // Payment Methods
    Categories::PAYMENT_METHODS->value => [
        ['name' => 'Debit Card', 'slug' => 'debit-card'],
        ['name' => 'Bank Account', 'slug' => 'bank-account'],
    ],

    Categories::INSURANCE_NAMES->value => [
        ['name' => 'Medibank', 'slug' => 'medibank', 'parent_slug' => 'australia'],
        ['name' => 'Bupa', 'slug' => 'bupa', 'parent_slug' => 'australia'],
        ['name' => 'NIB', 'slug' => 'nib', 'parent_slug' => 'australia'],
        ['name' => 'HCF', 'slug' => 'hcf', 'parent_slug' => 'australia'],
        ['name' => 'Australian Unity', 'slug' => 'australian-unity', 'parent_slug' => 'australia'],
        ['name' => 'GMHBA', 'slug' => 'gmhba', 'parent_slug' => 'australia'],
        ['name' => 'AHM', 'slug' => 'ahm', 'parent_slug' => 'australia'],
        ['name' => 'Frank Health Insurance', 'slug' => 'frank-health-insurance', 'parent_slug' => 'australia'],
        ['name' => 'Teachers Health', 'slug' => 'teachers-health', 'parent_slug' => 'australia'],
        ['name' => 'Health Partners', 'slug' => 'health-partners', 'parent_slug' => 'australia'],
        ['name' => 'CBHS Health Fund', 'slug' => 'cbhs-health-fund', 'parent_slug' => 'australia'],
        ['name' => 'HBF', 'slug' => 'hbf', 'parent_slug' => 'australia'],
        ['name' => 'Defence Health', 'slug' => 'defence-health', 'parent_slug' => 'australia'],
        ['name' => 'Westfund', 'slug' => 'westfund', 'parent_slug' => 'australia'],
        ['name' => 'TUH Health Fund', 'slug' => 'tuh-health-fund', 'parent_slug' => 'australia'],
        ['name' => 'St.Lukes Health', 'slug' => 'st-lukes-health', 'parent_slug' => 'australia'],
        ['name' => 'CUA Health', 'slug' => 'cua-health', 'parent_slug' => 'australia'],
        ['name' => 'Peoplecare', 'slug' => 'peoplecare', 'parent_slug' => 'australia'],
        ['name' => 'Police Health', 'slug' => 'police-health', 'parent_slug' => 'australia'],
        ['name' => 'Navy Health', 'slug' => 'navy-health', 'parent_slug' => 'australia'],
        ['name' => 'Transport Health', 'slug' => 'transport-health', 'parent_slug' => 'australia'],
        ['name' => 'Other', 'slug' => 'other', 'parent_slug' => 'australia'],
    ],

    Categories::TERMS_PERIODS->value => [
        ['name' => 'One Time', 'slug' => 'one-time'],
        ['name' => 'Daily', 'slug' => 'daily'],
        ['name' => 'Weekly', 'slug' => 'weekly'],
        ['name' => 'Fortnightly', 'slug' => 'fortnightly'],
        ['name' => 'Monthly', 'slug' => 'monthly'],
        ['name' => 'Quarterly', 'slug' => 'quarterly'],
        ['name' => 'Semi-Annually', 'slug' => 'semi-annually'],
        ['name' => 'Annually', 'slug' => 'annually'],
        ['name' => 'Biennially', 'slug' => 'biennially'],
        ['name' => 'Triennially', 'slug' => 'triennially'],
    ],


    // Expense Tracker Module Options
    Categories::ORGANIZATION->value => [
        ['name' => 'Medical Care', 'slug' => 'medical-care'],
        ['name' => 'The Alfred', 'slug' => 'alfred'],
        ['name' => 'Royal North Shore Hospital', 'slug' => 'royal-north'],
        ['name' => 'Liverpool Hospital', 'slug' => 'liverpool'],
        ['name' => 'Orange Health Service', 'slug' => 'orange-health'],
        ['name' => 'Canberra Hospital', 'slug' => 'canberra-hospital'],
        ['name' => 'Robina Hospital', 'slug' => 'robina'],
    ],

    Categories::PROVIDER_NUMBER->value => [
        ['name' => '123456A', 'slug' => '123456A', 'parent_slug'=>'alfred'],
        ['name' => '123456B', 'slug' => '123456B',  'parent_slug'=>'alfred'],
        ['name' => '123456C', 'slug' => '123456C', 'parent_slug'=>'royal-north'],
        ['name' => '123456D', 'slug' => '123456D',  'parent_slug'=>'royal-north'],
        ['name' => '123456E', 'slug' => '123456E',   'parent_slug'=>'robina' ],
    ],
    Categories::DOCTORS->value => [
        ['name' => 'Kyle', 'slug' => 'kyle',  'parent_slug'=>'123456A'],
        ['name' => 'Smith', 'slug' => 'smith',  'parent_slug'=>'123456B'],
        ['name' => 'Jenifer', 'slug' => 'jenifer',  'parent_slug'=>'123456C'],
        ['name' => 'Andrew Garfield', 'slug' => 'andrew-garfield',  'parent_slug'=>'123456D'],
        ['name' => 'Steve', 'slug' => 'steve',  'parent_slug'=>'123456E'],
    ],
   

    // Medical Bills
    Categories::BILLS->value => [
        ['name' => 'Dental', 'slug' => 'dental'],
        ['name' => 'Operation', 'slug' => 'operation'],
        ['name' => 'Check Up', 'slug' => 'check-up'],
        ['name' => 'Insurance', 'slug' => 'insurance'],
        ['name' => 'Medicine', 'slug' => 'medicine'],
        ['name' => 'Physiotherapy', 'slug' => 'physiotherapy'],
        ['name' => 'Chiropractic', 'slug' => 'chiropractic'],
        ['name' => 'Mental Health Services', 'slug' => 'mental-health-services'],
        ['name' => 'Optometry', 'slug' => 'optometry'],
        ['name' => 'Radiology', 'slug' => 'radiology'],
        ['name' => 'Pathology', 'slug' => 'pathology'],
        ['name' => 'Surgery', 'slug' => 'surgery'],
        ['name' => 'Hospital Stay', 'slug' => 'hospital-stay'],
        ['name' => 'Rehabilitation', 'slug' => 'rehabilitation'],
        ['name' => 'Pediatrics', 'slug' => 'pediatrics'],
        ['name' => 'Dermatology', 'slug' => 'dermatology'],
        ['name' => 'Cardiology', 'slug' => 'cardiology'],
        ['name' => 'Orthopedics', 'slug' => 'orthopedics'],
        ['name' => 'Gynecology', 'slug' => 'gynecology'],
        ['name' => 'Oncology', 'slug' => 'oncology'],
    ],

    // Visits
    Categories::VISIT_TYPE->value => [
        ['name' => 'Pain Management', 'slug' => 'pain-management'],
        ['name' => 'Diarrhea', 'slug' => 'diarrhea'],
        ['name' => 'Psychiatrist', 'slug' => 'psychiatrist'],
        ['name' => 'Routine Check-Up', 'slug' => 'routine-check-up'],
        ['name' => 'Vaccination', 'slug' => 'vaccination'],
        ['name' => 'Flu Symptoms', 'slug' => 'flu-symptoms'],
        ['name' => 'Cold Symptoms', 'slug' => 'cold-symptoms'],
        ['name' => 'Allergy', 'slug' => 'allergy'],
        ['name' => 'Skin Rash', 'slug' => 'skin-rash'],
        ['name' => 'Injury', 'slug' => 'injury'],
        ['name' => 'Follow-Up Visit', 'slug' => 'follow-up-visit'],
        ['name' => 'Blood Pressure Check', 'slug' => 'blood-pressure-check'],
        ['name' => 'Diabetes Management', 'slug' => 'diabetes-management'],
        ['name' => 'Cholesterol Management', 'slug' => 'cholesterol-management'],
        ['name' => 'Chronic Disease Management', 'slug' => 'chronic-disease-management'],
        ['name' => 'Mental Health Consultation', 'slug' => 'mental-health-consultation'],
        ['name' => 'Pregnancy Check-Up', 'slug' => 'pregnancy-check-up'],
        ['name' => 'Pediatric Visit', 'slug' => 'pediatric-visit'],
        ['name' => 'Gastrointestinal Issues', 'slug' => 'gastrointestinal-issues'],
        ['name' => 'Respiratory Issues', 'slug' => 'respiratory-issues'],
    ],
    Categories::TRANSACTION_CATEGORIES->value  => [
        ['name' => 'Debit', 'slug' => 'debit'],
        ['name' => 'Credit', 'slug' => 'credit'],
    ],

    // Transactions
    Categories::TRANSACTIONS->value => [
        ['name' => 'Contributor Payment Received', 'slug' => 'contributor-payment-received', 'parent_slug' => 'credit'],
        ['name' => 'Investment', 'slug' => 'investment', 'parent_slug' => 'debit'],
        ['name' => 'Refund', 'slug' => 'refund', 'parent_slug' => 'credit'],
        ['name' => 'Transfer', 'slug' => 'transfer', 'parent_slug' => 'debit'],
        ['name' => 'Withdrawal', 'slug' => 'withdrawal', 'parent_slug' => 'debit'],
        ['name' => 'Deposit', 'slug' => 'deposit', 'parent_slug' => 'credit'],
        ['name' => 'Fee', 'slug' => 'fee', 'parent_slug' => 'debit'],
        ['name' => 'Chargeback', 'slug' => 'chargeback', 'parent_slug' => 'debit'],
        ['name' => 'Interest', 'slug' => 'interest', 'parent_slug' => 'credit'],
        ['name' => 'Loan Payment', 'slug' => 'loan-payment', 'parent_slug' => 'debit'],
        ['name' => 'Bill Payment', 'slug' => 'bill-payment', 'parent_slug' => 'debit'],
        ['name' => 'Bonus', 'slug' => 'bonus', 'parent_slug' => 'credit'],
        ['name' => 'Penalty', 'slug' => 'penalty', 'parent_slug' => 'debit'],
        ['name' => 'Commission', 'slug' => 'commission', 'parent_slug' => 'credit'],
    ],

];
