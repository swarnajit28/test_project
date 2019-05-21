<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
     */
    'items_per_page'       => 10,
    'FA_update_days'       => 7,

    'changePassword'       => [
        'old_password'          => "old_password",
        'password'              => "password",
        'password_confirmation' => "password_confirmation",
    ],

    'addcustomer'       => [
        'company_name'                      => "company_name",
        'is_active'                         => "is_active",
        'registration_number'               => 'registration_number',
        'is_outside_FA'                     => 'is_outside_FA',
        'address_line_1'                   => 'address_line_1',
        'address_line_2'                   => 'address_line_2',
        'address_line_3'                   => 'address_line_3',
        'city'                              => 'city',
        'county'                            => 'county',
        'country'                           => 'country',
        'postal_code'                       => 'postal_code',
        'contact_person_name'               => "contact_person_name",
        'contact_person_phone1'             => "contact_person_phone1",
        'contact_person_phone2'             => "contact_person_phone2",
        'contact_person_email1'             => "contact_person_email1",
        'contact_person_email2'             => "contact_person_email2",
        'contact_person_note'               => "contact_person_note",
        'contact_person_job_title'          => "contact_person_job_title",
        'contact_person_job_role'           => "contact_person_job_role",
        'customer_attachment_name'          => "customer_attachment_name",
        'customer_attachment_file_name'     => "customer_attachment_file_name",
        'id'                                => 'id',
        'hide_attach'                       => 'hide_attach',
        'sales_person_id'                   => 'sales_person_id',
        

    ],

    'addleadcustomer'       => [
        'lead'                              =>"lead",
        'company_name'                      => "company_name",
        'is_active'                         => "is_active",
        'registration_number'               => 'registration_number',
        'is_outside_FA'                     => 'is_outside_FA',
        'address_line_1'                   => 'address_line_1',
        'address_line_2'                   => 'address_line_2',
        'address_line_3'                   => 'address_line_3',
        'city'                              => 'city',
        'county'                            => 'county',
        'country'                           => 'country',
        'postal_code'                       => 'postal_code',
        'contact_person_name'               => "contact_person_name",
        'contact_person_phone1'             => "contact_person_phone1",
        'contact_person_phone2'             => "contact_person_phone2",
        'contact_person_email1'             => "contact_person_email1",
        'contact_person_email2'             => "contact_person_email2",
        'contact_person_note'               => "contact_person_note",
        'contact_person_job_title'          => "contact_person_job_title",
        'contact_person_job_role'           => "contact_person_job_role",
        'customer_attachment_name'          => "customer_attachment_name",
        'customer_attachment_file_name'     => "customer_attachment_file_name",
        'id'                                => 'id',
        'hide_attach'                       => 'hide_attach',
        'sales_person_id'                   => 'sales_person_id',
    ],


    
    'addproduct'       => [
        'id'                       => "id",
        'product_category'         => "product_category",
        'product_name'             => "product_name",
        'product_description'      => "product_description",
        'margin_gbp'               => "margin_gbp",
        'rebate_gbp'               => "rebate_gbp",
        'end_margin'               => "end_margin",
        'commission'               => "commission",

    ],

     'adduser'       => [
         'id'                   =>  "id",
         'name'                 =>  "name",
         'email'                =>  "email",
         'phone'                =>  "phone",
         'role'                 =>  "role",
         'status'               =>  "status",
         
    ],

    'edituser'       => [
         'user_id'              =>  "user_id",
         'name'                 =>  "name",
         'email'                =>  "email",
         'phone'                =>  "phone",
         'role'                 =>  "role",
         'status'               =>  "status",
         
    ],

    'addloantype'      => [
         'loan_type_id'    => "loan_type_id",
         'loan_type'       => "loan_type",
         'loan_status'     => "loan_status",
         'color_code'      => "color_code",
         'key_details'      => "key_details",
        
    ],

    'addleadactivitymode'      => [
         'activity_mode_id'    => "activity_mode_id",
         'activity_mode'       => "activity_mode",
         'mode_status'         => "mode_status",
    ],
    'addlead'       => [
        'id'                         => "id",
        'custom_id'                  => "custom_id",
        'customer_id'                => "customer_id",
        'customer_contact_person_id' => "customer_contact_person_id",
        'sales_person_id'            => "sales_person_id",
        'lead_source'                => "lead_source",
        'additional_info'            => "additional_info",
        'is_active'                  => "is_active",
        'prod_id'                    => "prod_id",
        'proposed_value'             => "proposed_value",
        'quantity'                   => "quantity",
        'products'                   => "products",
        'strength'                   => "strength" ,
        'supportdoc'                 => "supportdoc",
        'margin_value'               => "margin_value",
        'end_margin'                 => "end_margin"
    ],
    'userchange_password'       => [
         'user_id'              =>  "user_id",
         'old_password'         =>  "old_password",
         'password'             =>  "password",
         'password_confirmation' =>  "password_confirmation",
         
    ],

    'addproductcategory'      => [
         'product_category_id'    => "product_category_id",
         'product_category_name'       => "product_category_name",
         'product_category_status'       => "product_category_status",
    ],
    
    'addactivity' => [
        'lead_id' => "user_id",
        'activity_type' => "activity_type",
        'lead_activity_mode_id' => "lead_activity_mode_id",
        'activity_note' => "activity_note",
    ],

    'leaddetails'       => [
        'id'                         => "id" 
    ],
    
    'onboardingform'       => [
        'id'                   => "id",
        'agency_name'          => "agency_name",
        'reg_no'               => "reg_no",
        'agency_client'        => "agency_client",
        'business_sector'      => "business_sector",
        'new_business'         => "new_business",
        'payraoll'             => "payraoll",
        'compliance'           => "compliance",
        'initially'            => "initially",
        'one_month'            => "one_month",
        'six_month'            => "six_month",
        'product'              => "product",
        'weekly_invoice'       => "weekly_invoice",
        'margin'               => "margin",
        'credit'               => "credit",
        'rebate'               => "rebate",
        'rebate_threshold'     => "rebate_threshold",
        'start_date'           => "start_date",

    ],
    
    'weeekly_target'       => [
        'id'                 => "id",
        'start_date'         => "start_date",
        'end_date'           => "end_date",
        'week_number'        => "week_number",
        'cis_paid'           => "cis_paid",
        'umbrella_paid'      => "umbrella_paid",
        'other_paid'         => "other_paid",
    ],
    
    'addpaymentoption'      => [
         'payment_option_id'        => "payment_option_id",
         'payment_option_name'      => "payment_option_name",
         'payment_option_status'    => "payment_option_status",
         'is_reimbursable'          => "is_reimbursable",
    ],
    
    'addexpensetype'      => [
         'expense_type_id'        => "expense_type_id",
         'expense_type_name'      => "expense_type_name",
         'expense_type_status'    => "expense_type_status",
    ],
    'businessExpense'       => [
        'id'                         => "id",
        'sales_person_id'            => "sales_person_id",
        'company_name'               => "company_name",
        'return_to'                  => "return_to" ,
        'custom_id'                  => "custom_id",
        'customer_id'                => "customer_id",
        'reporting_period'           => "reporting_period" ,
        'st_ex_date'                 => "st_ex_date",
        'business_expense'           => "business_expense",
        'payment_option'             => "payment_option",
        'contact_person'             => "contact_person",
        'client_contact'             => "client_contact",
        'vat'                        => "vat",
        'total'                      => "total",
        'start_milage'               => "start_milage",
        'end_milage'                 => "end_milage",
        'mileage_date'               => "mileage_date",
        'location'                   => "location",
        'total_mileage'              => "total_mileage",
        'rate'                       => "rate",
        'contact_person1'            => "contact_person1",
        'total_price'                => "total_price",
        'fuel_reimburse'             => "fuel_reimburse",
        'total_cash_reimbursement'   => "total_cash_reimbursement",
        'all_total_reimburse'        => "all_total_reimburse",
        'net_expense'                => "net_expense",
        'vat_reclaimed'              => "vat_reclaimed",
        'gross_expense'              => "gross_expense",
        'sort_code'                  => "sort_code",
        'account_number'             => "account_number",
        'acknowledged_by'            => "acknowledged_by",
        'acknowledged_on'            => "acknowledged_on",
        'sign_type'                  => "sign_type",
        'signed_file'                => "signed_file",
        'upload_file'                => "upload_file",
        'sign_date'                  => "sign_date",
        'signature_file'             => "signature_file",
        'acknowledged_by1'           => "acknowledged_by1",
        'sign_type1'                 => "sign_type1",
        'signed_file1'               => "signed_file1",
        'upload_file1'               => "upload_file1",
        'signature_file1'            => "signature_file1",
        'acknowledged_on1'           => "acknowledged_on1",
     
    ],
    
    'umbrellaCalculator'      => [
        'your_name'             => "your_name",
        'individuals_name'      => "individuals_name",
        'individuals_email'     => "individuals_email",
        'rate_type'             => "rate_type",
        'rate_of_pay'           => "rate_of_pay",
        'total_hour_day'        => "total_hour_day",
        'include_pension'       => "include_pension",
        'input_margin'          => "input_margin",
    ],
    
    'documentStore' => [
        'doc_name'          => "doc_name",
        'doc_type'          => "doc_type",
        'file_name'         => "file_name",
        'doc_for'           => "doc_for",
    ],
];
