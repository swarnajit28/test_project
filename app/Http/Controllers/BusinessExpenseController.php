<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\model\Payment_option;
use App\model\Expense_type;
use App\model\customer;
use App\model\Business_expense_document;
use App\model\Standard_expense;
use App\model\Mileage_expense;
use App\model\Umbrella_querie;
use App\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Validator;
use helper;

class BusinessExpenseController extends Controller
{
    public function __construct() {
        DB::enableQueryLog();
        $this->items_per_page = Config::get('formArray.items_per_page');
    }

 
    public function add_business_expense(Request $request) {
        $is_approved = 0;
        $salePerson = $request->sale_person;
        $year = $request->year;
        $month = $request->month;
        $date = date_parse($month);
        $number_of_month = $date['month'];

        $form = helper::getFormFields("businessExpense");
        $formfield = helper::encryptForm($form);
        $salesperson = User::selectactiveuser('SP');
        $payment_method = Payment_option::active_payment_method();
        $expense_type = Expense_type::active_expense_type();
        $salesperson_id = User::get_sp_by_name($salePerson);

        //helper::pre($salesperson_id,1);
        if ($salesperson_id) {
            $find_form = $business_expense = Business_expense_document::find_form($salesperson_id, $number_of_month, $year);
            //helper::pre($find_form,1);
            if ($find_form) {
                $fetch_business_from = Business_expense_document::fetch_business_from($salesperson_id, $number_of_month, $year);
                $business_expense_id = $fetch_business_from['id'];
                $fetch_standard_expenses = Standard_expense::fetch_standard_expenses($business_expense_id);
                $fetch_mileage_expenses = Mileage_expense::fetch_mileage_expenses($business_expense_id);
                $spcustomers = customer::spcustomers($fetch_business_from['sales_executive_id']);
                //helper::pre($fetch_business_from,1);
                if ($find_form['is_approved'] == 0) {
                   // echo("Edit form here");
                    return view('edit_business_expense', compact('formfield', 'salesperson', 'payment_method', 'expense_type', 'year', 'month', 'is_approved', 'fetch_business_from', 'fetch_standard_expenses', 'fetch_mileage_expenses', 'spcustomers'));
                } else {
                    $is_approved = 1;
                    //echo("Form in approved");
                    return view('edit_business_expense', compact('formfield', 'salesperson', 'payment_method', 'expense_type', 'year', 'month', 'is_approved', 'fetch_business_from', 'fetch_standard_expenses', 'fetch_mileage_expenses', 'spcustomers'));
                }
            } else {
                //echo("add form here");
                // die();

                return view('add_business_expense', compact('formfield', 'salesperson', 'payment_method', 'expense_type', 'year', 'month', 'is_approved'));
                //helper::pre($salesperson_id, 1);
            }
        } else {
            return redirect('/list_business_expense')->with('success_message', 'Please select saleperson first!');
        }

        //die();
    }

    public function submit_business_expense(Request $request) {
        //helper::pre($request->all(),1);
        $white_lists = helper::getFormFields("businessExpense");
        $ignore_keys = array('_token');
        $allRequest = $request->all();
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        $file_data = helper::decryptForm($_FILES, $white_lists, $ignore_keys);
        $month = explode("/", $post_data['reporting_period'])[0];
        $year = explode("/", $post_data['reporting_period'])[1];
        $added_by = Auth::user()->id;
        $acknowledged_on = str_replace('/', '-', $post_data['acknowledged_on']);
        $acknowledged_on = date('Y-m-d', strtotime($acknowledged_on));

        //helper::pre($acknowledged_on,1);
        //helper::pre($post_data,1);

        /*         * **********Input Type  Name**************** */
        if ($post_data['sign_type'] == 1) {
            $file_name = $post_data['signed_file'];
        }

        /*         * **********Input Type Sign Pad**************** */
        if ($post_data['sign_type'] == 2) {
            if (isset($post_data['signature_file'])) {
                $encoded_image_arr = explode(",", $post_data['signature_file']);
                if (is_array($encoded_image_arr) && (count($encoded_image_arr) > 1)) {
                    $encoded_image = explode(",", $post_data['signature_file'])[1];
                    $decoded_image = base64_decode($encoded_image);
                    $file_name = $added_by . "_" . date('Ymd_His') . ".png";
                    $destination = "public/uploads/signatures/" . $file_name;
                    file_put_contents($destination, $decoded_image);
                    //$reference_request->signed_file = isset($file_name) ? Crypt::encrypt($file_name) : Crypt::encrypt('');
                }
            }
        }
        /*         * *********** Input Type Image Upload************* */
        if ($post_data['sign_type'] == 3) {
            $file_name = '';
            if (isset($post_data['upload_file'])) {
                $file_ext = pathinfo($file_data['upload_file']['name'], PATHINFO_EXTENSION);
                $file_name = $added_by . "_" . date('Ymd_His') . "." . $file_ext;
                $destination = "public/uploads/signatures/" . $file_name;
                move_uploaded_file($post_data['upload_file'], $destination);
                // $reference_request->signed_file = isset($file_name) ? Crypt::encrypt($file_name) : Crypt::encrypt('');
            }
        }
        //helper::pre($post_data, 0);
        $date = date_parse($month);
        $number_of_month=$date['month'];

        $business_expense_insert_array = array(
            'company_name' => $post_data['company_name'],
            'sales_executive_id' => $post_data['sales_person_id'],
            'return_to' => $post_data['return_to'],
            'reporting_period_month' => $number_of_month,
            'reporting_period_year' => $year,
            'bank_sort_code' => $post_data['sort_code'],
            'bank_account_number' => $post_data['account_number'],
            'acknowledged_by' => $post_data['acknowledged_by'],
            'acknowledged_signature_type' => $post_data['sign_type'],
            'acknowledged_signature_file_name' => $file_name,
            'acknowledged_on' => $acknowledged_on,
            'is_approved' => 0,
            'added_by' => $added_by,
        );
        $business_expense = Business_expense_document::add_business_expense($business_expense_insert_array);
        if (isset($business_expense['id']) && $business_expense['id'] != '') {
            if (isset($post_data['st_ex_date'])) {
                $data = array();
                Standard_expense::delete_standard_expenses($business_expense['id']);
                //$data['business_expense_doc_id'] = $business_expense['id'];
                if (is_array($post_data['st_ex_date']) && count($post_data['st_ex_date'] > 0)) {
                    foreach ($post_data['st_ex_date'] as $key1 => $value1) {
                        $date_of_expense = str_replace('/', '-', $post_data['st_ex_date'][$key1]);
                        $date_of_expense = date('Y-m-d', strtotime($date_of_expense));
                        $payment_option = explode("||", $post_data['payment_option'][$key1])[0];
                        $arr['business_expense_doc_id'] = $business_expense['id'];
                        $arr['date_of_expense'] = $date_of_expense;
                        $arr['expense_type_id'] = isset($post_data['business_expense'][$key1]) ? $post_data['business_expense'][$key1] : '';
                        $arr['payment_option_id'] = isset($post_data['payment_option'][$key1]) ? $payment_option : '';
                        $arr['client_id'] = isset($post_data['contact_person'][$key1]) ? $post_data['contact_person'][$key1] : '';
                        $arr['client_contact'] = isset($post_data['client_contact'][$key1]) ? $post_data['client_contact'][$key1] : '';
                        $arr['is_vat_applicable'] = isset($post_data['vat'][$key1]) ? $post_data['vat'][$key1] : '';
                        $arr['expense_amount'] = isset($post_data['total'][$key1]) ? $post_data['total'][$key1] : '';
                        $arr['added_by_user_id'] = $added_by;
                        array_push($data, $arr);
                    }

                    Standard_expense::insert_standard_expenses($data);
                    //helper::pre($data, 1);
                }
            }
         if (isset($post_data['mileage_date'])) {
                $milage_data = array();
                Mileage_expense::delete_mileage_expenses($business_expense['id']);
                //$data['business_expense_doc_id'] = $business_expense['id'];
                if (is_array($post_data['mileage_date']) && count($post_data['mileage_date'] > 0)) {
                    foreach ($post_data['mileage_date'] as $key1 => $value1) {
                        $mileage_date = str_replace('/', '-', $post_data['mileage_date'][$key1]);
                        $mileage_date = date('Y-m-d', strtotime($mileage_date));
                        //$payment_option = explode("||", $post_data['payment_option'][$key1])[0];
                        $arr1['business_expense_doc_id'] = $business_expense['id'];
                        $arr1['date_of_expense'] = $date_of_expense;
                        $arr1['location_postal_code'] = isset($post_data['location'][$key1]) ? $post_data['location'][$key1] : '';
                        $arr1['miles_covered'] = isset($post_data['total_mileage'][$key1]) ? $post_data['total_mileage'][$key1] : '';
                        $arr1['mileage_rate'] = isset($post_data['rate'][$key1]) ? $post_data['rate'][$key1] : '';
                        $arr1['client_id'] = isset($post_data['contact_person1'][$key1]) ? $post_data['contact_person1'][$key1] : '';
                        $arr1['mileage_total'] = isset($post_data['total_price'][$key1]) ? $post_data['total_price'][$key1] : '';
                        $arr1['added_by_user_id'] = $added_by;
                        array_push($milage_data, $arr1);
                    }
                    
                    Mileage_expense::insert_mileage_expenses($milage_data);
                    //helper::pre($milage_data, 1);
                }
            }
            
        }
        //helper::pre($business_expense, 1);
        return redirect('/list_business_expense')->with('success_message', 'Business Expense added successfully!');
    }

    public function fetch_sp_customer(Request $request)
    {
        $posts = customer::spcustomers($request->input('id'));
        //helper::pre($posts,1);
        if (count($posts)) 
        {  
            $output = '<option value="">Select</option>';       
            foreach ($posts as $post) {                
                $output .= '<option value="'.$post['id'].'">'.$post['company_name'].'</option>';
            }
        }
        else{
            $output = '<option value="">Select</option>'; 
        }
        echo $output;
    }
   
    public function list_business_expense(Request $request) {
        //helper::pre($request->all());
        $result_array=array();
        $search_year='';
        $search_sale_person='';
        $y = 2018;
        $sp_list = User::selectuser('SP');
        $month = array_reduce(range(1, 12), function($rslt, $m) {
            $rslt[$m] = date('F', mktime(0, 0, 0, $m, 10));
            return $rslt;
        });
        for ($i = 0; $i <= 10; $i++) {
            $year[] = $y;
            $y = $y + 1;
        }
        
        if (empty($request->input('search'))) {
            $search='no';
        } else {
            $search='yes';
        }
        if(($request->sale_person)!=''&& ($request->year)!=''){
            $search_year=$request->year;
            $search_sale_person=$request->sale_person;
            $expense_document = Business_expense_document::id_wise_document($request->sale_person,$request->year);
            $user_name=User::get_customer_by_id($request->sale_person);
           // helper::pre($expense_document);
            //helper::pre($user_name);
            foreach($month as $key=>$value)
            {
               $result_array[$key]['year']=$search_year; 
               $result_array[$key]['month']=$value;
               $result_array[$key]['saleperson']=$user_name['display_name'];
               foreach ($expense_document as $key1 => $value1) {
                   if($value1['reporting_period_month']==$key){
                   $result_array[$key]['is_approved']=$value1['is_approved'];
                   //echo($key);
                   }
                }
            }
            //helper::pre($result_array);
            //echo("if");
        }else{
            $result_array=array();
        }
        //helper::pre($year, 0);
        // helper::pre($month, 1);
        return view('list_business_expense', compact('sp_list', 'month', 'year','search','result_array','search_year','search_sale_person'));
    }

    public function editsubmit_business_expense(Request $request) {
        //helper::pre($request->all(),1);
        $is_approved = 0;
        $sign_type1 = NULL;
        $file_name = '';
        $file_name1 = '';
        $acknowledged_on1 = NULL;
        $approved_by = NULL;
        $white_lists = helper::getFormFields("businessExpense");
        $ignore_keys = array('_token');
        $allRequest = $request->all();
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        $file_data = helper::decryptForm($_FILES, $white_lists, $ignore_keys);
        $month = explode("/", $post_data['reporting_period'])[0];
        $year = explode("/", $post_data['reporting_period'])[1];
        $added_by = Auth::user()->id;
        $acknowledged_on = str_replace('/', '-', $post_data['acknowledged_on']);
        $acknowledged_on = date('Y-m-d', strtotime($acknowledged_on));
        //helper::pre($post_data,1);
        /*         * **********Input Type  Name**************** */
        if (isset($post_data['signed_file'])) {
            $file_name = $post_data['signed_file'];
        }
        if ($post_data['sign_type'] == 1) {
            $file_name = $post_data['signed_file'];
        }

        /*         * **********Input Type Sign Pad**************** */
        if ($post_data['sign_type'] == 2) {
            if (isset($post_data['signature_file'])) {
                $encoded_image_arr = explode(",", $post_data['signature_file']);
                if (is_array($encoded_image_arr) && (count($encoded_image_arr) > 1)) {
                    $encoded_image = explode(",", $post_data['signature_file'])[1];
                    $decoded_image = base64_decode($encoded_image);
                    $file_name = $added_by . "_" . date('Ymd_His') . ".png";
                    $destination = "public/uploads/signatures/" . $file_name;
                    file_put_contents($destination, $decoded_image);
                    //$reference_request->signed_file = isset($file_name) ? Crypt::encrypt($file_name) : Crypt::encrypt('');
                }
            }
        }
        /*         * *********** Input Type Image Upload************* */
        if ($post_data['sign_type'] == 3) {
            if (isset($post_data['upload_file'])) {
                $file_ext = pathinfo($file_data['upload_file']['name'], PATHINFO_EXTENSION);
                $file_name = $added_by . "_" . date('Ymd_His') . "." . $file_ext;
                $destination = "public/uploads/signatures/" . $file_name;
                move_uploaded_file($post_data['upload_file'], $destination);
                // $reference_request->signed_file = isset($file_name) ? Crypt::encrypt($file_name) : Crypt::encrypt('');
            }
        }


        if (isset($post_data['acknowledged_by1']) &&($post_data['acknowledged_by1'] != '')) {
            $is_approved = 1;
            $approved_by = $post_data['acknowledged_by1'];
        }
        if (isset($post_data['sign_type1']) && ($post_data['sign_type1'] != '')) {
            $sign_type1 = $post_data['sign_type1'];
        }
        if (isset($post_data['signed_file1']) && ($post_data['signed_file1'] != '')) {
            $signed_file1 = $post_data['signed_file1'];
        }
        if (isset($post_data['acknowledged_on1'])&&($post_data['acknowledged_on1'] != '')) {
            $acknowledged_on1 = str_replace('/', '-', $post_data['acknowledged_on1']);
            $acknowledged_on1 = date('Y-m-d', strtotime($acknowledged_on1));
        }


        /*         * **********Input Type ACKNOWLEDGE Name**************** */
        if (isset($post_data['signed_file1'])) {
            $file_name1 = $post_data['signed_file1'];
        }
        if (isset($post_data['sign_type1']) && ($post_data['sign_type1'] == 1)) {
            $file_name1 = $post_data['signed_file1'];
        }
        //helper::pre($file_name1,1);
        /*         * **********Input Type ACKNOWLEDGE Sign Pad**************** */
        if (isset($post_data['sign_type1']) && ($post_data['sign_type1'] == 2)) {
            if (isset($post_data['signature_file1'])) {
                $encoded_image_arr = explode(",", $post_data['signature_file1']);
                if (is_array($encoded_image_arr) && (count($encoded_image_arr) > 1)) {
                    $encoded_image = explode(",", $post_data['signature_file1'])[1];
                    $decoded_image = base64_decode($encoded_image);
                    $file_name1 = "approved" . $added_by . "_" . date('Ymd_His') . ".png";
                    $destination = "public/uploads/signatures/" . $file_name1;
                    file_put_contents($destination, $decoded_image);
                    //helper::pre($file_name1,1);
                    //$reference_request->signed_file = isset($file_name) ? Crypt::encrypt($file_name) : Crypt::encrypt('');
                }
            }
        }
        /*         * *********** Input Type Image ACKNOWLEDGE Upload************* */
        if (isset($post_data['sign_type1']) && ($post_data['sign_type1'] == 3)) {

            if (isset($post_data['upload_file1'])) {
                $file_ext = pathinfo($file_data['upload_file1']['name'], PATHINFO_EXTENSION);
                $file_name1 = "approved" . $added_by . "_" . date('Ymd_His') . "." . $file_ext;
                $destination = "public/uploads/signatures/" . $file_name1;
                move_uploaded_file($post_data['upload_file1'], $destination);
                // $reference_request->signed_file = isset($file_name) ? Crypt::encrypt($file_name) : Crypt::encrypt('');
            }
        }


        //helper::pre($post_data, 0);
        $date = date_parse($month);
        $number_of_month = $date['month'];
        $post = Business_expense_document::findOrFail($post_data['id']);
        if (isset($post_data['sign_type1'])){
        $data = array(
            'company_name' => $post_data['company_name'],
            'sales_executive_id' => $post_data['sales_person_id'],
            'return_to' => $post_data['return_to'],
            'reporting_period_month' => $number_of_month,
            'reporting_period_year' => $year,
            'bank_sort_code' => $post_data['sort_code'],
            'bank_account_number' => $post_data['account_number'],
            'acknowledged_by' => $post_data['acknowledged_by'],
            'acknowledged_signature_type' => $post_data['sign_type'],
            'acknowledged_signature_file_name' => $file_name,
            'acknowledged_on' => $acknowledged_on,
            'is_approved' => $is_approved,
            'approved_by' => $approved_by,
            'approved_signature_type' => $sign_type1,
            'approved_signature_file_name' => $file_name1,
            'approved_on' => $acknowledged_on1,
        );
        //helper::pre($data, 1);
        $post->fill($data);
        }else{
         $data = array(
            'company_name' => $post_data['company_name'],
            'sales_executive_id' => $post_data['sales_person_id'],
            'return_to' => $post_data['return_to'],
            'reporting_period_month' => $number_of_month,
            'reporting_period_year' => $year,
            'bank_sort_code' => $post_data['sort_code'],
            'bank_account_number' => $post_data['account_number'],
            'acknowledged_by' => $post_data['acknowledged_by'],
            'acknowledged_signature_type' => $post_data['sign_type'],
            'acknowledged_signature_file_name' => $file_name,
            'acknowledged_on' => $acknowledged_on,
            
        );
        //helper::pre($data, 1);
        $post->fill($data);
        }
        $post->save();
        //helper::pre($post_data,1);
        if (isset($post_data['id']) && $post_data['id'] != '') {
            if (isset($post_data['st_ex_date'])) {
                $data = array();
                Standard_expense::delete_standard_expenses($post_data['id']);
                //$data['business_expense_doc_id'] = $business_expense['id'];
                if (is_array($post_data['st_ex_date']) && count($post_data['st_ex_date'] > 0)) {
                    foreach ($post_data['st_ex_date'] as $key1 => $value1) {
                        $date_of_expense = str_replace('/', '-', $post_data['st_ex_date'][$key1]);
                        $date_of_expense = date('Y-m-d', strtotime($date_of_expense));
                        $payment_option = explode("||", $post_data['payment_option'][$key1])[0];
                        $arr['business_expense_doc_id'] = $post_data['id'];
                        $arr['date_of_expense'] = $date_of_expense;
                        $arr['expense_type_id'] = isset($post_data['business_expense'][$key1]) ? $post_data['business_expense'][$key1] : '';
                        $arr['payment_option_id'] = isset($post_data['payment_option'][$key1]) ? $payment_option : '';
                        $arr['client_id'] = isset($post_data['contact_person'][$key1]) ? $post_data['contact_person'][$key1] : '';
                        $arr['client_contact'] = isset($post_data['client_contact'][$key1]) ? $post_data['client_contact'][$key1] : '';
                        $arr['is_vat_applicable'] = isset($post_data['vat'][$key1]) ? $post_data['vat'][$key1] : '';
                        $arr['expense_amount'] = isset($post_data['total'][$key1]) ? $post_data['total'][$key1] : '';
                        $arr['added_by_user_id'] = $added_by;
                        array_push($data, $arr);
                    }

                    Standard_expense::insert_standard_expenses($data);
                    //helper::pre($data, 1);
                }
            }
            if (isset($post_data['mileage_date'])) {
                $milage_data = array();
                Mileage_expense::delete_mileage_expenses($post_data['id']);
                //$data['business_expense_doc_id'] = $business_expense['id'];
                if (is_array($post_data['mileage_date']) && count($post_data['mileage_date'] > 0)) {
                    foreach ($post_data['mileage_date'] as $key1 => $value1) {
                        $mileage_date = str_replace('/', '-', $post_data['mileage_date'][$key1]);
                        $mileage_date = date('Y-m-d', strtotime($mileage_date));
                        //$payment_option = explode("||", $post_data['payment_option'][$key1])[0];
                        $arr1['business_expense_doc_id'] = $post_data['id'];
                        $arr1['date_of_expense'] = $date_of_expense;
                        $arr1['location_postal_code'] = isset($post_data['location'][$key1]) ? $post_data['location'][$key1] : '';
                        $arr1['miles_covered'] = isset($post_data['total_mileage'][$key1]) ? $post_data['total_mileage'][$key1] : '';
                        $arr1['mileage_rate'] = isset($post_data['rate'][$key1]) ? $post_data['rate'][$key1] : '';
                        $arr1['client_id'] = isset($post_data['contact_person1'][$key1]) ? $post_data['contact_person1'][$key1] : '';
                        $arr1['mileage_total'] = isset($post_data['total_price'][$key1]) ? $post_data['total_price'][$key1] : '';
                        $arr1['added_by_user_id'] = $added_by;
                        array_push($milage_data, $arr1);
                    }

                    Mileage_expense::insert_mileage_expenses($milage_data);
                    //helper::pre($milage_data, 1);
                }
            }
        }
        return redirect('/list_business_expense')->with('success_message', 'Editted Successfully!');
        //helper::pre($post->save(),1);
        //helper::pre($post_data,1);
    }

    public function business_expense_report(Request $request) {
        $total_standard_expense = 0;
        $total_business_expense = 0;
        $total_expense=0;
        $grand_total=0;
        $client_idwise_standard_expense = array();
        $client_idwise_mileage_expenses = array();
        $new_array=array();
        $se_array= array();
        $me_array=array();
        $expense_array=array();
        $search_from_date='';
        $search_to_date='';
        $search_array['from_date']='';
        $search_array['to_date']='';
        $search_array['sp_id']='';
        $sp_list = User::selectuser('SP');
         if($request->has('sale_person')&&($request->input('sale_person')!='')){
            $search_array['sp_id']= $request->input('sale_person');
        }
        if ($request->has('fromdate')&& ($request->input('fromdate')!='')) {
            $search_from_date=$request->input('fromdate');
            $from_date = str_replace('/', '-', $request->input('fromdate'));
            $search_array['from_date'] = date('Y-m-d', strtotime($from_date));
        }
        if($request->has('todate')&& ($request->input('todate')!='')){
            $search_to_date=$request->input('todate');
            $to_date = str_replace('/', '-', $request->input('todate')); 
            $search_array['to_date'] = date('Y-m-d', strtotime($to_date));
        }
        $standard_expense = Business_expense_document::fetch_standard_expense($search_array);
        $mileage_expenses = Business_expense_document::fetch_mileage_expenses($search_array);
        //helper::pre($standard_expense,0);
        //helper::pre($mileage_expenses,0);
        foreach ($standard_expense as $key => $value) {
            $client_idwise_standard_expense[$value['client_id']][] = $value;
        }
        foreach ($mileage_expenses as $key1 => $value1) {
            $client_idwise_mileage_expenses[$value1['client_id']][] = $value1;
        }
        
        //helper:: pre($client_idwise_standard_expense,0);
        //helper:: pre($client_idwise_mileage_expenses,0);
        
        foreach ($client_idwise_standard_expense as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                if ($value3['is_reimbursable'] != 0) {
                    $total_standard_expense = $total_standard_expense + $value3['expense_amount'];
                }
            }
            $se_array[$key2]['company_name'] = $value3['company_name'];
            $se_array[$key2]['client_id'] = $value3['client_id'];
            $se_array[$key2]['total_standard'] = $total_standard_expense;
            $total_standard_expense = 0;
        }
        
        //helper:: pre($se_array,0);
        
         foreach($client_idwise_mileage_expenses as $key=>$value)
        {
            foreach($value as $key1=>$value1){
                    $total_business_expense = $total_business_expense + $value1['mileage_total'];
               
            }
                $me_array[$key]['company_name']=$value1['company_name'];
                $me_array[$key]['client_id']=$value1['client_id'];
                $me_array[$key]['total_business'] = $total_business_expense;
                $total_business_expense = 0;
        }
        //helper:: pre($me_array,0);
        $result = array_merge_recursive($se_array, $me_array);
        //helper:: pre($result,0);
        foreach ($result as $key => $value) {
            $new_array[$value['client_id']][] = $value;
        }
        //helper:: pre($new_array,0);
        //die();
      foreach ($new_array as $key=>$value)
        {
            foreach($value as $key1=>$value1){
                if(isset($value1['total_standard'])){
                     $total_expense=$total_expense+$value1['total_standard'];
                     $grand_total+=$value1['total_standard'];
                } 
                if(isset($value1['total_business'])){
                     $total_expense=$total_expense+$value1['total_business'];
                     $grand_total+=$value1['total_business'];
                }
                    
            }
             $expense_array[$key]['company_name']=$value1['company_name'];
             $expense_array[$key]['client_id']=$value1['client_id'];
             $expense_array[$key]['total_expense']=$total_expense;
             $total_expense=0;
        }  
      // helper:: pre($expense_array,0); 
       return view('business_expense_report', compact('sp_list','expense_array','grand_total','search_array','search_from_date','search_to_date'));        
    }

   
}
