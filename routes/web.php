<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::user()) {
        return redirect()->route('user-type-dashboard');
    } else {
        return view('auth.login');
    }
});

Auth::routes();

Route::get('/artisan-call/{type?}', function($type='') {
        if($type==''){
            return "1--->php artisan cache:clear"."<br>"."2--->php artisan route:cache(CANT RUN THIS)"."<br>"."3--->php artisan view:clear"."<br>"."4--->php artisan config:cache"."<br><br>"."please write option type";
        }else{
            if($type == 1 /*|| $type == 2*/ || $type == 3 || $type == 4){
                if($type==1){
                    $command = "cache:clear";
                }
                /*if($type==2){
                    $command = "route:cache";
                }*/
                if($type==3){
                    $command = "view:clear";
                }
                if($type==4){
                    $command = "config:cache";
                }
                $exitCode = Artisan::call($command);    
                return "RUN--->>>  php artisan ".$command."<<<---SUCCESSFULL";
            }else{
                return "Select proper option";
            }
        }
        
    });

Route::get('/home', 'HomeController@index')->name('home');

/********   For authentication error and Logout      *************/
Route::get('authentication-error', function(){
    Auth::logout();
    return view('errors.auth_error');
})->name('authentication-error');  

Route::group(['middleware' => 'auth'], function() {
    // *******************for all user Dashboard***********************************   
    Route::get('user-type-dashboard', 'UsertypeController@dashboard')->name('user-type-dashboard');
    Route::post('/loadAjaxDashboard', 'UsertypeController@loadAjaxDashboard')->name('loadAjaxDashboard');

    // *******************for product***********************************
    Route::group(['middleware' => 'MA.SP.SM.OM'], function() {
        Route::get('/add_product', 'ProductController@add_product')->name('add_product');
        Route::post('/submit_product', 'ProductController@store')->name('submit_product');
        Route::get('/ListAllproduct', 'ProductController@list_product')->name('list_product');
        Route::post('/loadDataAjax', 'ProductController@loadDataAjax')->name('loadDataAjax');
        Route::post('/searchProduct', 'ProductController@searchProduct')->name('searchProduct');
        Route::get('/editProduct/{id}', 'ProductController@productEdit')->name('editProduct');
        Route::post('duplicateNameCheck', 'ProductController@checkNameExist')->name('duplicateNameCheck');
        Route::post('productstatuschange', 'ProductController@productstatuschange')->name('productstatuschange');
    });
    // *******************for lead report ********************************** 

    Route::group(['middleware' => 'MA.LM.SM'], function() {
        Route::get('lead-report', 'Leadcontroller@Leadreport')->name('lead-report');
        Route::get('report_dashbord', 'Leadcontroller@report_dashbord')->name('report_dashbord');
        Route::post('/search-lead-report', 'Leadcontroller@SearchLeadReport')->name('search-lead-report');
    });

    // ******************* for create lead ********************************** 
    Route::group(['middleware' => 'MA.LM.SP.SM'], function() {
        Route::get('add_lead', 'Leadcontroller@add_lead')->name('add-lead');
        Route::get('customer_add_lead/{id}/{comp_name}', 'Leadcontroller@customer_add_lead')->name('customer-add-lead');
        Route::post('select_contact', 'Leadcontroller@select_contact')->name('select-contact');
        Route::post('select_customer_attachment', 'Leadcontroller@select_customer_attachment')->name('select-customer-attachment');
        Route::get('list_lead', 'Leadcontroller@list_lead')->name('list-lead');
        Route::get('add_customer', 'Customercontroller@add_customer')->name('add-customer');
        Route::POST('import_add_customer', 'Customercontroller@import_add_customer')->name('import_add-customer');
        Route::any('clientDownloadExcel', 'Customercontroller@export')->name('client_download_Excel');
        Route::post('product_details', 'Leadcontroller@product_details')->name('product-details');
        Route::post('/loadlead', 'Leadcontroller@loadlead')->name('loadlead');
        Route::post('/searchlead', 'Leadcontroller@searchlead')->name('search-lead');
        Route::get('edit_lead/{id}', 'Leadcontroller@edit_lead')->name('edit-lead');
        Route::post('/searchactivity', 'Leadcontroller@searchactivity')->name('search-activity');
        Route::post('submit_lead', 'Leadcontroller@submit_lead')->name('submit-lead');
        Route::post('select_contact_details', 'Leadcontroller@select_contact_details')->name('select-contact-details');
        Route::post('/list_lead_status/', 'Leadcontroller@list_lead_by_status')->name('list_lead_status');
        Route::post('/loadleadbystatus', 'Leadcontroller@loadleadbystatus')->name('loadleadbystatus');
        Route::post('/searchleadfromdashboard', 'Leadcontroller@searchlead_from_dashboard')->name('search-lead-from-dashboard');
        Route::post('select_sp_customers', 'Leadcontroller@select_sp_customers')->name('select-sp-customers');
        Route::get('lead_details/{id}', 'Leadcontroller@lead_details')->name('lead-details');
    });

    // *******************for client and customer**********************************  
    Route::group(['middleware' => 'MA.LM.SP.SM.OM'], function() {
        Route::get('manage_customer', 'Customercontroller@listcustomer')->name('list-customer');
        Route::get('manage_client', 'Customercontroller@listclient')->name('list-client');
        Route::get('edit_customer/{id}', 'Customercontroller@edit_customer')->name('edit-customer');
//      Route::get('add_customer', 'Customercontroller@add_customer')->name('add-customer');
        Route::post('submit_customer', 'Customercontroller@submitcustomer')->name('submit-customer');
        Route::post('/loadcustomer', 'Customercontroller@loadcustomer')->name('loadcustomer');
        Route::post('/loadclient', 'Customercontroller@loadclient')->name('loadclient');
        Route::post('/customerstatuschange', 'Customercontroller@customerstatuschange')->name('customerstatuschange');
        Route::get('add_lead_customer', 'Customercontroller@add_lead_customer')->name('add-lead-customer');
        Route::get('add_lead_customer_now/{id}', 'Customercontroller@add_lead_customer_now')->name('add-lead-customer-now');
        Route::post('crypt_id', 'Customercontroller@crypt_id')->name('crypt-id');
        Route::post('/sessionCustomer', 'Customercontroller@sessionCustomer')->name('session-Customer');
        Route::post('submitleadcustomer', 'Customercontroller@submitleadcustomer')->name('submit-lead-customer');
        Route::post('check_email', 'Customercontroller@checkEmailExist')->name('check-email');
        Route::post('duplicateCustomerCheck', 'Customercontroller@checkCustomerExist')->name('duplicateCustomerCheck');
        Route::post('duplicateRegistrationCheck', 'Customercontroller@checkRegistratonExist')->name('duplicateRegistrationCheck');
        Route::post('/outside_FA', 'Customercontroller@outside_FA')->name('outside_FA');
    });

    // *******************for only sale person lead ********************************** 
    Route::group(['middleware' => 'SP'], function() {
        Route::post('add_activity', 'Leadcontroller@add_activity')->name('add_activity');
        Route::post('add_activity_lead_deatais_page', 'Leadcontroller@add_activity_lead_deatais_page')->name('add_activity_lead_deatais_page');
        Route::post('uploadsupportdoc', 'Leadcontroller@Upload_Support_Docs')->name('upload-support-doc');
        Route::post('completeLead', 'Leadcontroller@complete_lead')->name('complete_lead');
        Route::any('submit_onboarding_info/{id?}', 'Leadcontroller@submit_onboarding_info')->name('submit_onboarding_info');
        Route::get('sp-lead-report', 'Leadcontroller@Leadspreport')->name('sp-lead-report');
        Route::post('/search-sp-lead-report', 'Leadcontroller@SearchLeadspReport')->name('search-sp-lead-report');
    });


    // *******************for only IT         ********************************** 
    Route::group(['middleware' => 'IT'], function() {
        Route::get('addNewUser', 'UserManageController@add_User')->name('addNewUser');
        Route::post('/storeNewUser', 'UserManageController@store')->name('submit_user');
        Route::get('userdetails/{id}', 'Alluserdetails@userdetails')->name('user-details');
        Route::post('/userstatuschange', 'UserManageController@userstatuschange')->name('userstatuschange');
        Route::post('/searchuseractivity', 'Alluserdetails@searchuseractivity')->name('search-user-activity');
        // *******************Master Data Controller   **********************************  
        Route::get('/master_loan', 'MasterDataController@master_loan_list')->name('master_loan');
        Route::get('edit_master_loan_view/{id}', 'MasterDataController@edit_master_loan_view')->name('edit_master_loan_view');
        Route::post('/delete_master_loan', 'MasterDataController@delete_master_loan')->name('delete_master_loan');
        Route::post('add_master_loan_type', 'MasterDataController@add_master_loan')->name('add_master_loan_type');
        Route::post('/loanstatuschange', 'MasterDataController@loanstatuschange')->name('loanstatuschange');
        Route::post('/loadloan', 'MasterDataController@loadloan')->name('loadloan');
        Route::get('/onboard_email', 'MasterDataController@onboard_email')->name('onboard_email');
        Route::post('/submit_onBoardEmail', 'MasterDataController@submit_onBoardEmail')->name('submit_onBoardEmail');
        Route::get('/lead_activity_mode', 'MasterDataController@lead_activity_mode_list')->name('lead_activity_mode');
        Route::post('add_lead_activity_mode', 'MasterDataController@add_lead_activity_mode')->name('add_lead_activity_mode');
        Route::get('edit_lead_activity_mode/{id}', 'MasterDataController@edit_lead_activity_mode')->name('edit_lead_activity_mode');
        Route::post('/delete_activity_mode', 'MasterDataController@delete_activity_mode')->name('delete_activity_mode');
        Route::post('/loadLeadActivityMode', 'MasterDataController@loadleadactivitymode')->name('loadLeadActivityMode');
        Route::get('/product_category', 'MasterDataController@product_category_list')->name('product_category')->middleware('IT');
        Route::post('/add_product_category', 'MasterDataController@add_product_category')->name('add_product_category')->middleware('IT');
        Route::post('/productcategorystatchange', 'MasterDataController@productCategorystatchange')->name('product-category-stat-change');
        Route::get('/edit_product_category/{id}', 'MasterDataController@edit_product_category')->name('edit_product_category');
        Route::post('/loadProductCategory', 'MasterDataController@loadproductcategory')->name('loadProductCategory');
        Route::get('user_list', 'UserManageController@user_list')->name('user_list');
        Route::post('/search_user', 'UserManageController@search_user')->name('search_user');
        Route::post('/loaduser', 'UserManageController@loaduser')->name('loaduser');
        Route::post('/search_activity_product_manage', 'Leadcontroller@search_activity_product_manage')->name('search-activity-product-manage');
    });

    // ***************************Activity controller By It Manager  **********************************  
    Route::group(['middleware' => 'IT'], function() {
        Route::any('activity-manager-by-product', 'ActivityController@activityManagerbyProduct')->name('activityManagerByProduct');
        Route::post('loadAudiTrailByProduct', 'ActivityController@loadAudiTrailByProduct')->name('loadAudiTrailByProduct');
        Route::any('activity-manager-by-user', 'ActivityController@activityManagerbyUser')->name('activity-manager-by-user');
        Route::post('loadAudiTrailByUser', 'ActivityController@loadAudiTrailByUser')->name('loadAudiTrailByUser');
        Route::any('activity-manager-by-lead', 'ActivityController@activityManagerByLead')->name('activityManagerByLead');
        Route::post('loadAudiTrailByLead', 'ActivityController@loadAudiTrailByLead')->name('loadAudiTrailByLead');
        Route::get('/deleteUser/{id}', 'UserManageController@deleteUser')->name('delete-user');
        Route::any('/fiveKproject', 'MasterDataController@five_K_project_details')->name('fiveKproject');
        Route::any('/addfiveKproject/{id?}', 'MasterDataController@addfiveKproject')->name('addfiveKproject');
        Route::post('/ajaxWeekNumber', 'MasterDataController@ajaxWeekNumber')->name('ajaxWeekNumber');
        Route::any('/lockExclusiveDays', 'MasterDataController@lock_exclusive_days')->name('lockExclusiveDays');
        Route::any('DocumentsUpload', 'DocumentsManagementController@upload_documents')->name('DocumentsUpload');
    });

    // *******************for only MA       ********************************** 
    Route::group(['middleware' => 'MA'], function() {
        Route::any('payment-options', 'MasterDataController@listPaymentOptions')->name('payment-options');
        Route::post('/addPaymentOption', 'MasterDataController@add_payment_option')->name('addPaymentOption');
        Route::post('/paymentOptionStatusChange', 'MasterDataController@payment_option_status_change')->name('paymentOptionStatusChange');
        Route::get('/editPaymentOption/{id}', 'MasterDataController@edit_payment_option')->name('editPaymentOption');
        Route::post('/loadPaymentOption', 'MasterDataController@load_payment_option')->name('loadPaymentOption');
        Route::any('expense-type', 'MasterDataController@listExpenseType')->name('expense-type');
        Route::post('/addExpenseType', 'MasterDataController@add_expense_type')->name('addExpenseType');
        Route::post('/expenseTypeStatusChange', 'MasterDataController@expense_type_status_change')->name('expenseTypeStatusChange');
        Route::get('/editExpenseType/{id}', 'MasterDataController@edit_expense_type')->name('editExpenseType');
        Route::post('/loadExpenseType', 'MasterDataController@load_expense_type')->name('loadExpenseType');
    });

    /*     * *****************************Sales Persons For Management******************************* */
    Route::group(['middleware' => 'MA.SM'], function() {
        Route::get('salepersons', 'UserManageController@splist')->name('sp-list');
        Route::post('/loadsplist', 'UserManageController@loadsplist')->name('loadsplist');
        Route::get('list_sp_lead/{id}', 'Leadcontroller@list_sp_lead')->name('list-sp-lead');
        Route::post('/loadsplead', 'Leadcontroller@loadsplead')->name('loadsplead');
        Route::post('/searchsplead', 'Leadcontroller@searchsplead')->name('search-sp-lead');
        Route::post('/spstatuschange', 'UserManageController@spstatuschange')->name('spstatuschange');
        Route::get('/deletelead/{id}', 'Leadcontroller@deletelead')->name('delete-lead');
        Route::get('/deleteProduct/{id}', 'ProductController@productDelete')->name('delete-product');
        Route::get('/deleteCustomer/{id}', 'Customercontroller@deleteCustomer')->name('delete-customer');
        Route::any('umbrella_report', 'Customercontroller@umbrella_report')->name('umbrella-report');
        Route::post('loadUmbrellaReport', 'Customercontroller@load_umbrella_report')->name('loadUmbrellaReport');
    });

    Route::group(['middleware' => 'MA.SP'], function() {
        Route::any('business_expense', 'BusinessExpenseController@add_business_expense')->name('business_expense');
        //Route::get('business_expense1/{year?}/{month?}', 'BusinessExpenseController@add_business_expense')->name('business_expense1');
        Route::post('submitBusinessExpense', 'BusinessExpenseController@submit_business_expense')->name('submitBusinessExpense');
        Route::post('editsubmitBusinessExpense', 'BusinessExpenseController@editsubmit_business_expense')->name('editsubmitBusinessExpense');
        Route::post('fetch_sp_customer', 'BusinessExpenseController@fetch_sp_customer')->name('fetch_sp_customer');
        Route::any('list_business_expense', 'BusinessExpenseController@list_business_expense')->name('list_business_expense');
        Route::get('business_expense_report', 'BusinessExpenseController@business_expense_report')->name('business_expense_report');
    });

    Route::group(['middleware' => 'SM.OM'], function() {
        Route::any('uploadExcel', 'MasterDataController@import')->name('upload_excel');
        Route::any('downloadExcel', 'MasterDataController@export')->name('download_excel');
    });

    // *******************User Management controller  **********************************  

    Route::get('/user_change_password', 'UserManageController@user_change_password_view')->name('user_change_password');
    Route::post('/save_change_password', 'UserManageController@save_user_change_password')->name('save_change_password');
    Route::get('/view_profile', 'UserManageController@view_profile')->name('view_profile');
    Route::get('/edit_profile/{id}', 'UserManageController@edit_profile')->name('edit_profile');
    Route::post('/update_profile', 'UserManageController@update_user_details')->name('update_profile');
    Route::post('/delete_user_email', 'UserManageController@delete_user_email')->name('delete_user_email');
    Route::post('/delete_user_phone', 'UserManageController@delete_user_phone')->name('delete_user_phone');
    Route::post('/set_primary_email', 'UserManageController@set_primary_email')->name('set_primary_email');
    Route::post('/sendVerificationMail', 'UserManageController@sendverificationmail')->name('sendVerificationMail');
    Route::post('/loadactivityproductmanage', 'Leadcontroller@loadactivityproductmanage')->name('loadactivityproductmanage');
    Route::post('/activitymodestatchange', 'MasterDataController@activitymodestatchange')->name('activity-mode-stat-change');
    Route::get('/verify_email/{id}', 'UserManageController@verify_email')->name('verify_email');
    Route::get('/verify_mail_user/{id}', 'UserManageController@verify_mail_user')->name('verify_mail_user');


    /*     * *****************************Documents Management******************************* */

    Route::post('checkactiveuser', 'Controller@checkactiveuser')->name('check-active-user');
    Route::any('umbrella_calculator', 'Customercontroller@umbrella_calculator')->name('umbrella_calculator');
    Route::post('reportBug', 'MasterDataController@report_bug')->name('report_bug');
    Route::any('BugReportList', 'MasterDataController@bug_report_list')->name('bug_report_list');
    Route::post('loadBugReport', 'MasterDataController@load_bug_report')->name('loadBugReport');
    Route::any('DocumentStore', 'DocumentsManagementController@view_document_store')->name('DocumentStore');
    Route::post('loadDocumentStore', 'DocumentsManagementController@load_document_store')->name('loadDocumentStore');
    Route::post('check_email_user', 'UserManageController@checkEmailExist')->name('check_email_user');
    Route::post('check_phone_user', 'UserManageController@checkPhoneExist')->name('check_phone_user');
    Route::get('viewUserProfile', 'UserManageController@viewUserProfile')->name('view_user_profile');
    Route::post('/searchactivity', 'Leadcontroller@searchactivity')->name('search-activity');
    Route::any('test', 'Customercontroller@test')->name('test');
});
