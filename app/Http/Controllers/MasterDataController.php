<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Crypt;
//use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

use App\model\Lead_strength;
use App\model\Lead_activity_mode;
use App\model\Product_category;
use App\model\Payment_option;
use App\model\Expense_type;
use App\model\Onboarding_operation_management;
use App\model\Weekly_thermometer_record;
use App\model\Website_setting;
use Validator;
use App\model\Outside_funding_arrangement;
use App\model\Report_bug;
use App\User;
use helper;
use Illuminate\Support\Facades\Config;
use Session;

class MasterDataController extends Controller
{
    public function __construct()
    {
       DB::enableQueryLog();
      // $this->items_per_page = '3';
        $this->items_per_page = Config::get('formArray.items_per_page');
      // $this->items_per_page = '3';
       //$this->middleware('IT');
    }

    public function master_loan_list()
    {
        $formfield      = helper::getFormFields("addloantype");
        $encrypted  = helper::encryptForm($formfield);
        //$Lead_strength = new Lead_strength ;
        //helper::pre($encrypted,1);
        $page = $this->items_per_page;
        $loan = Lead_strength::allstrengthlist($page);
        return view('master_loan_list',compact('loan','encrypted','page'));
    }

    public function add_master_loan(Request $request) {
        $dec_id = '';
        $allRequest = $request->all();
        //helper::pre($allRequest,1);
        $white_lists = helper::getFormFields("addloantype");
        $ignore_keys = array('_token');
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        if ($post_data['loan_type_id'] != '') {
            $dec_id = $post_data['loan_type_id'];
        }
        $rule = ['loan_type' => 'required|unique:lead_strengths,loan_type,' . $dec_id];

        $validator = Validator::make($post_data,$rule);
        if ($validator->fails()) {
            $p_data = ['loan_type' => $post_data['loan_type'],
                'loan_status' => $post_data['loan_status'],
            ];
            return redirect()->back()->withErrors($validator)->with('status', $p_data);
        }

        if ($post_data['loan_type_id'] == '') {
            $insert_array = array(
                'loan_type' => $post_data['loan_type'],
                'is_active' => $post_data['loan_status'],
                'color_code' => $post_data['color_code'],
                'key_details' => $post_data['key_details'],
            );
            //helper::pre($insert_array,1);
            $loan = Lead_strength::insert_data($insert_array);

            if (!empty($loan) && $loan['id'] != '') {
                return redirect('master_loan')->with('success_message', "Lead type added successfully");
            }
        } else {
            $update_array = array(
                'id' => $post_data['loan_type_id'],
                'loan_type' => $post_data['loan_type'],
                'is_active' => $post_data['loan_status'],
                'color_code' => $post_data['color_code'],
                'key_details' => $post_data['key_details'],
            );
            //helper::pre($update_array,1);
            $loan = Lead_strength::update_data($update_array);
            if ($loan == 1) {
                return redirect('master_loan')->with('success_message', "Lead type updated successfully");
            }
            else{
                return redirect('master_loan')->with('success_message', "Lead type updated successfully");
            }
        }
    }

    public function edit_master_loan_view($id) {
        $formfield = helper::getFormFields("addloantype");
        $encrypted = helper::encryptForm($formfield);
        $decryptedloanid = Crypt::decrypt($id);
        $loan_details = Lead_strength::find($decryptedloanid);
        $page = $this->items_per_page;
        $loan = Lead_strength::allstrengthlist($page);        //dd($loan);
        if (!empty($loan_details)) {
            return view('master_loan_list', compact('loan_details', 'loan', 'encrypted','page'));
        }
        //dd($Lead_strength);
    }

    public function delete_master_loan() {
        $id = $_POST['id'];
        $resp = Lead_strength::delete_data($id);
        if ($resp > 0) {
            return $resp;
        }
    }



    public function lead_activity_mode_list()
    {
       // echo '1'; die();
        $page  = $this->items_per_page;

        $formfield      = helper::getFormFields("addleadactivitymode");
        $encrypted  = helper::encryptForm($formfield);
      //  $Lead_activity_mode = new Lead_activity_mode ;
        $id = Lead_activity_mode::lastmode();
        if(!empty($id))
        {
            $lastmode = $id['id'];
        }
        else
        {
            $lastmode = '';
        }
        //echo $lastmode;exit;
        $loan = Lead_activity_mode::get_all_lead_activity($page);
   //     dd($encrypted);

        return view('lead_activity_mode',compact('encrypted','loan','page','lastmode'));
    }

    public function add_lead_activity_mode(Request $request)
    {
        $dec_id = '';
        $allRequest = $request->all();
      //  helper::pre($allRequest,0); die();

        $white_lists = helper::getFormFields("addleadactivitymode");

    /*    helper::pre($allRequest,0);
        helper::pre($white_lists,0); */
        $ignore_keys                     = array('_token');
        $post_data                       = helper::decryptForm($allRequest, $white_lists, $ignore_keys);

        if($post_data['activity_mode_id'] != '')
        {
            $dec_id = $post_data['activity_mode_id'];
        }
        $rule = ['activity_mode'  => 'required|unique:lead_activity_modes,activity_mode,' .$dec_id] ;
       /* if($post_data['activity_mode_id'] == '')
        {
            $rule = ['activity_mode'  => 'required|unique:lead_activity_modes'] ;
        }
        else
        {
             $rule = ['activity_mode'  => 'required'] ;
        } */

        $validator = Validator::make($post_data, $rule);
        if ($validator->fails()) {
          //  echo '1'; die();
            $p_data = [ 'activity_mode' => $post_data['activity_mode'],
                        'mode_status' => $post_data['mode_status'],
            ];
            return redirect()->back()->withErrors($validator)->with('status', $p_data);
        }
        if($post_data['activity_mode_id'] == '')
        {
          //  echo '2'; die();
             $insert_array = array(
                    'activity_mode' => $post_data['activity_mode'],
                    'is_active' => $post_data['mode_status'],
            );
            $loan = Lead_activity_mode::insert_data($insert_array);
            // dd($loan);
            if (!empty($loan) && $loan['id'] != '')
            {
                return redirect('lead_activity_mode')->with('success_message', "Lead activity mode added successfully");
            }
        }
        else
        {
             $update_array = array(
            'id'        => $post_data['activity_mode_id'],
            'activity_mode' => $post_data['activity_mode'],
            'is_active' => $post_data['mode_status'],
            );
            //$loan = Lead_strength::where('id',$post_data['loan_type_id'])->update($insert_array);
            $loan = Lead_activity_mode::update_data($update_array);
            if ($loan == 1)
            {
                return redirect('lead_activity_mode')->with('success_message', "Lead activity mode updated successfully");
            }
            else{
                return redirect('lead_activity_mode')->with('success_message', "Lead activity mode updated successfully");
            }
        }

    }

    public function edit_lead_activity_mode($id)
    {
       // echo $id; die();
        $formfield      = helper::getFormFields("addleadactivitymode");
        $encrypted  = helper::encryptForm($formfield);
        $decryptedloanid = Crypt::decrypt($id);
        $loan_details = Lead_activity_mode::findorfail($decryptedloanid);

        $Lead_strength = new Lead_activity_mode ;
        $loan = $Lead_strength->get();
         //   dd($loan_details);
        $id = Lead_activity_mode::lastmode();
        if(!empty($id))
        {
            $lastmode = $id['id'];
        }
        else
        {
            $lastmode = '';
        }
        if(!empty($loan_details))
        {
            return view('lead_activity_mode',compact('loan_details','loan','encrypted','lastmode'));
        }
        //dd($Lead_strength);

    }

    public function delete_activity_mode()
    {
      //  echo $id; die();
        $id = $_POST['id'];
        $resp = Lead_activity_mode::delete_data($id) ;
        if($resp > 0)
        {
            return $resp;
        }

    }


    public function loanstatuschange(Request $request)
    {
       $id  = $request->input('id');
        $status = $request->input('status');
        $response = Lead_strength::loanstatuschange($id,$status);
        $newid = "'".$id."'";
        $newstat = "'".$status."'";
        if($status==0)
        {
            $output = '<i class="fa fa-close" aria-hidden="true" style="cursor: pointer;" onclick="change_loan_status('.$newstat.','.$newid.');" title="Inactive"></i>';
        }
        elseif($status==1)
        {
            $output = '<i class="fa fa-check" aria-hidden="true" style="cursor: pointer;" onclick="change_loan_status('.$newstat.','.$newid.');" title="Active"></i>';
        }
        return $output;
    }

    public function loadloan(Request $request)
    {
        $output = '';
        $id = $request->input('id');
        $page = $this->items_per_page;
        $loan = Lead_strength::allstrengthlist($page,$id);
        if(count($loan)>0)
        {
            foreach($loan as $data)
            {
                $change_status = 'change_loan_status('.$data['is_active'].','.$data['id'].')' ;
                $output .= '<tr id="'.$data['id'].'" class="note-row">'
                        . '<td>'.$data['loan_type'].'</td>';
                 $output .='<td style="text-align: center;"><input type="color" value="'. $data['color_code'].'" disabled></td>';
                 $output.='<td style="text-align: center;">'. $data['key_details'].'</td>';
                     $output .= '<td class="text-center" id="stat'.$data['id'].'">';

                    if($data['is_active'] == '1')
                    {
                        $output .= '<i class="fa fa-check" aria-hidden="true" title="active"  style="cursor: pointer;" onclick="'.$change_status.'" ></i>' ;
                    }
                    else
                    {
                        $output .= '<i class="fa fa-times" aria-hidden="true" title="Inactive" style="cursor: pointer;" onclick="'.$change_status.'"></i>' ;

                    }
                    $output .= '</td><td class="text-center viewgrp-dropdown dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a><ul class="dropdown-menu">
                        <li><a href="'.route('edit_master_loan_view',['id' => Crypt::encrypt($data['id'])]).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li></ul></td></tr>' ;
            }

        }

         echo $output;
    }

    public function loadleadactivitymode(Request $request)
    {
        $output = '';
        $id = $request->input('id');
        $page = $this->items_per_page;
        $loan = Lead_activity_mode::get_load_lead_activity_mode($page,$id);
      //  helper::pre($loan,1);
      if(count($loan)>0)
      {
        foreach($loan as $data)
        {
            $output .= '<tr id="'.$data['id'].'"><td>'.$data['activity_mode'].'</td><td class="text-center" id="stat'.$data['id'].'">';
                if($data['is_active'] == '1')
                {
                    $newstat = "'".$data['is_active']."'";
                    $newid = "'".$data['id']."'";
                    $output .= '<i class="fa fa-check" aria-hidden="true" title="Active" style="cursor: pointer;" onclick="change_activity_status('.$newstat.','.$newid.');"></i>';
                }
                else
                {
                    $newstat = "'".$data['is_active']."'";
                    $newid = "'".$data['id']."'";
                    $output .= '<i class="fa fa-times" aria-hidden="true" title="Inactive" style="cursor: pointer;" onclick="change_activity_status('.$newstat.','.$newid.');"></i>';
                }
            $output .= '</td><td class="text-center viewgrp-dropdown dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a><ul class="dropdown-menu"><li><a href="'.route('edit_lead_activity_mode',['id' => Crypt::encrypt($data['id'])]).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li></ul></td></tr>';
        }
        $output .= '<input type="hidden" name="activity_mode_id" id="activity_mode_id" value="'.$data['id'].'"/>';
        }
        echo $output ;
    }



    public function activitymodestatchange(Request $request)
    {
        $id  = $request->input('id');
        $status = $request->input('status');
        $response = Lead_activity_mode::activitymodestatchange($id,$status);
        /*if($response > 0)
        {
            return $response ;
        }
        else
        {
            return 0;
        }*/
        $newid = "'".$id."'";
        $newstat = "'".$status."'";
        if($status==0)
        {
            $output = '<i class="fa fa-close" aria-hidden="true" onclick="change_activity_status('.$newstat.','.$newid.');" title="Inactive"></i>';
        }
        elseif($status==1)
        {
            $output = '<i class="fa fa-check" aria-hidden="true" onclick="change_activity_status('.$newstat.','.$newid.');" title="Active"></i>';
        }
        return $output;
    }

    public function product_category_list()
    {
        $formfield      = helper::getFormFields("addproductcategory");
        $encrypted  = helper::encryptForm($formfield);
        //$Lead_strength = new Lead_strength ;

        $page = $this->items_per_page;
        $category = Product_category::all_product_categories($page);
        $id = Product_category::lastcategory();
        if(!empty($id))
        {
            $lastmode = $id['id'];
        }
        else
        {
            $lastmode = '';
        }
        return view('product_category',compact('category','encrypted','page','lastmode'));
    }

    public function add_product_category(Request $request)
    {
        $dec_id = '';
         $allRequest = $request->all();

        $white_lists = helper::getFormFields("addproductcategory");

        $ignore_keys  = array('_token');
        $post_data    = helper::decryptForm($allRequest, $white_lists, $ignore_keys);

     //   helper::pre($post_data,1);
        if($post_data['product_category_id'] != '')
        {
            $dec_id = $post_data['product_category_id'];
        }
        $rule = ['product_category_name'  => 'required|unique:product_categories,category_name,' .$dec_id] ;
        $validator = Validator::make($post_data,$rule);
        if ($validator->fails()) {
            $p_data = ['product_category_name' => $post_data['product_category_name'],
                'product_category_status' => $post_data['product_category_status'],
            ];
            return redirect()->back()->withErrors($validator)->with('status', $p_data);
        }
        else
        {
            if ($post_data['product_category_id'] == '') {
            $insert_array = array(
                'category_name' => $post_data['product_category_name'],
                'is_active' => $post_data['product_category_status'],
            );
            $category = Product_category::addProductCategory($insert_array);
                if (!empty($category) && $category['id'] != '') {
                    return redirect('product_category')->with('success_message', "Product Category added successfully");
                }
            }
            else
            {
                $update_array = array(
                    'id' => $post_data['product_category_id'],
                    'category_name' => $post_data['product_category_name'],
                    'is_active' => $post_data['product_category_status'],
                );
              /*  $post = Product_category::findOrFail($post_data['product_category_id']);
                $post->fill($update_array);
                $post->save(); */
                $post = Product_category::updateProductCategory($update_array);
                if($post == '1')
                {
                    return redirect('product_category')->with('success_message', "Product Category updated successfully");
                }
                else
                {
                    return redirect('product_category')->with('success_message', "Product Category not updated");
                }


            }
       }
    }

    public function productCategorystatchange(Request $request)
    {
        $id  = $request->input('id');
        $status = $request->input('status');
        $response = Product_category::productCategorystatchange($id,$status);
        /*if($response > 0)
        {
            return $response ;
        }
        else
        {
            return 0;
        }*/
        $newid = "'".$id."'";
        $newstat = "'".$status."'";
        if($status==0)
        {
            $output = '<i class="fa fa-close" aria-hidden="true" onclick="change_category_status('.$newstat.','.$newid.');" title="Inactive"></i>';
        }
        elseif($status==1)
        {
            $output = '<i class="fa fa-check" aria-hidden="true" onclick="change_category_status('.$newstat.','.$newid.');" title="Active"></i>';
        }
        return $output;
    }

    public function edit_product_category($id)
    {
       // echo $id; die();
        $formfield      = helper::getFormFields("addproductcategory");
        $encrypted  = helper::encryptForm($formfield);
        $decryptedid = Crypt::decrypt($id);
        $category_details = Product_category::findorfail($decryptedid);

        $Product_category = new Product_category ;
        $category = $Product_category->get();
         //   dd($loan_details);
        $id = Product_category::lastcategory();
        if(!empty($id))
        {
            $lastmode = $id['id'];
        }
        else
        {
            $lastmode = '';
        }
        if(!empty($category_details))
        {
            return view('product_category',compact('category_details','category','encrypted','lastmode'));
        }
        //dd($Lead_strength);

    }


    public function loadproductcategory(Request $request)
    {
        $output = '';
        $id = $request->input('id');
        $page = $this->items_per_page;
        $loan = Product_category::get_load_product_category($page,$id);
      //  helper::pre($loan,1);
      if(count($loan)>0)
      {
        foreach($loan as $data)
        {
            $output .= '<tr id="'.$data['id'].'"><td>'.$data['category_name'].'</td><td class="text-center" id="stat'.$data['id'].'">';
                if($data['is_active'] == '1')
                {
                    $newstat = "'".$data['is_active']."'";
                    $newid = "'".$data['id']."'";
                    $output .= '<i class="fa fa-check" aria-hidden="true" title="Active" style="cursor: pointer;" onclick="change_category_status('.$newstat.','.$newid.');"></i>';
                }
                else
                {
                    $newstat = "'".$data['is_active']."'";
                    $newid = "'".$data['id']."'";
                    $output .= '<i class="fa fa-times" aria-hidden="true" title="Inactive" style="cursor: pointer;" onclick="change_category_status('.$newstat.','.$newid.');"></i>';
                }
            $output .= '</td><td class="text-center viewgrp-dropdown dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a><ul class="dropdown-menu"><li><a href="'.route('edit_product_category',['id' => Crypt::encrypt($data['id'])]).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li></ul></td></tr>';
        }
        $output .= '<input type="hidden" name="product_category_id" id="product_category_id" value="'.$data['id'].'"/>';
        }
        echo $output ;
    }

    public function onboard_email() {
        $newArray = array();
        $allOM_list = User::get_all_OM();
        $allOnBoard = Onboarding_operation_management::all()->toArray();
        foreach ($allOnBoard as $key => $value) {
            array_push($newArray, $value['user_id']);
        }
        foreach ($allOM_list as $key1 => $value1) {
            if (in_array($value1['id'], $newArray)) {
                $allOM_list[$key1]['checked'] = 'yes';
            } else {
                $allOM_list[$key1]['checked'] = 'no';
            }
        }
        //helper::pre($allOM_list, 0);
        return view('onboard_email',compact('allOM_list'));
    }

    public function submit_onBoardEmail(Request $request) {
        $tmpArr = array();
        $allRequest = $request->all();
        //helper::pre($allRequest,1);
        Onboarding_operation_management::truncate();
        if (isset($allRequest['list_om'])) {
            foreach ($allRequest['list_om'] as $key => $value) {
                $tmpArr[]['user_id'] = $value;
            }
        }
        Onboarding_operation_management::insert($tmpArr);
        return redirect('onboard_email')->with('success_message', "On board email changed successfully");
    }

    public function five_K_project_details(Request $request) {
        //$allRequest = $request->all();
        //helper::pre($allRequest,1);
          $year= date("Y");
            if ($request->has('_token')) {
            if ($request->has('year')) {
                $year=$request->year;
            }else{
                $allRequest = $request->all();
                $target_setting = Website_setting::find(1);
                $target_setting->weekly_project_target = $allRequest['target_value'];
                $target_setting->save();
                //helper::pre($allRequest,1);
            }
        }
        $weekly_project_target['target_value'] = Website_setting::weekly_project_target();
        //helper::pre($weekly_project_target,1);

        $weekly_list=Weekly_thermometer_record::all_record($year);
        $select=array();
        $year= date("Y");
        for ($k = 0 ; $k < 5; $k++){
            $select[]=$year;
            $year--;
        }
        //helper::pre($weekly_list,1);
        return view('five_k_project', compact('weekly_project_target','weekly_list','select'));
    }

    public function addfiveKproject(Request $request, $id = '') {
        $id_wise_data = array(
            'id' => '',
            'week_start_date' => '',
            'week_end_date' => '',
            'week_number' => '',
            'cis_paid' => '',
            'umbrella_paid' => '',
            'other_paid' => '',
            'week_year' => '',
        );
        if ($request->has('_token')) {
            $id = '';
            $allRequest = $request->all();
            //helper::pre($allRequest, 1);
            $white_lists = helper::getFormFields("weeekly_target");
            $ignore_keys = array('_token');
            $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
            $rule = [
                'start_date' => 'required',
                'end_date' => 'required',
                'week_number' => 'required',
                'cis_paid' => 'required',
                'umbrella_paid' => 'required|numeric',
                'other_paid' => 'required|numeric',
            ];
            $validator = Validator::make($post_data, $rule);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $week_start_date = str_replace('/', '-', $post_data['start_date']);
            $week_start_date = date('Y-m-d', strtotime($week_start_date));
            $week_end_date = str_replace('/', '-', $post_data['end_date']);
            $week_end_date = date('Y-m-d', strtotime($week_end_date));
            $week_year = date('Y', strtotime($week_start_date));

            $insert_array = array(
                'week_start_date' => $week_start_date,
                'week_end_date' => $week_end_date,
                'week_number' => $post_data['week_number'],
                'cis_paid' => $post_data['cis_paid'],
                'umbrella_paid' => $post_data['umbrella_paid'],
                'other_paid' => $post_data['other_paid'],
                'week_year' => $week_year,
            );
            if ($post_data['id'] != '') {
                $update_obj = Weekly_thermometer_record::findOrFail($post_data['id']);
                $update_obj->update($insert_array);
                return redirect('fiveKproject')->with('success_message', "Weekly record updated successfully");
            } else {
                $check_week = Weekly_thermometer_record::check_week_exists($insert_array);
                if ($check_week == 0) {
                    $insert = Weekly_thermometer_record::add_weekly_thermometer_record($insert_array);
                    return redirect('fiveKproject')->with('success_message', "Weekly record added successfully");
                } else {
                    return redirect('fiveKproject')->with('success_message', "Weekly data already exit");
                }
            }
        } else {
            if ($id != '') {
                $id = Crypt::decrypt($id);
                $id_wise_data = Weekly_thermometer_record::where('id', $id)->first()->toArray();

            }
            $formfield = helper::getFormFields("weeekly_target");
            $encrypted = helper::encryptForm($formfield);
            return view('weekly_target', compact('encrypted', 'id_wise_data'));
        }
    }

    public function ajaxWeekNumber(Request $request) {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $start_date_string = str_replace('/', '-', $start_date);
        $end_date_string = str_replace('/', '-', $end_date);
        $start_date_week = date("W", strtotime($start_date_string));
        $end_date_week = date("W", strtotime($end_date_string));
        if ($start_date_week != $end_date_week) {
            echo ("Error week");
        } else {
            echo($end_date_week);
        }
    }

    public function lock_exclusive_days(Request $request)
    {
       if ($request->has('_token')) {
           $allRequest = $request->all();
                $target_setting = Website_setting::find(1);
                $target_setting->customer_exclusive_lock_days = $allRequest['lock_days'];
                $target_setting->save();
                return redirect('lockExclusiveDays')->with('success_message', "Lock executive days modified successfully");
       }
       $lock_days['customer_exclusive_lock_days'] = Website_setting::exclusive_lock_days();
       //helper::pre($lock_days,1);
       return view('lead_exclusive_date_settings',compact('lock_days'));
    }

    public function listPaymentOptions() {
        $formfield = helper::getFormFields("addpaymentoption");
        $encrypted = helper::encryptForm($formfield);
        $page = $this->items_per_page;
        $category = Payment_option::all_payment_option($page);
        $id = Payment_option::lastpayment();
        if (!empty($id)) {
            $lastmode = $id['id'];
        } else {
            $lastmode = '';
        }
        //helper::pre($id,0);
        //helper::pre($category,1);
        return view('payment_option', compact('category', 'encrypted',  'lastmode'));
    }

    public function add_payment_option(Request $request) {
        $dec_id = '';
        $allRequest = $request->all();

        $white_lists = helper::getFormFields("addpaymentoption");

        $ignore_keys = array('_token');
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);

        //   helper::pre($post_data,1);
        if ($post_data['payment_option_id'] != '') {
            $dec_id = $post_data['payment_option_id'];
        }
        $rule = ['payment_option_name' => 'required|unique:payment_options,payment_option,' . $dec_id];
        $validator = Validator::make($post_data, $rule);
        if ($validator->fails()) {
            $p_data = ['payment_option_name' => $post_data['payment_option_name'],
                'payment_option_status' => $post_data['payment_option_status'],
            ];
            return redirect()->back()->withErrors($validator)->with('status', $p_data);
        } else {
            if ($post_data['payment_option_id'] == '') {
                $insert_array = array(
                    'payment_option' => $post_data['payment_option_name'],
                    'is_active' => $post_data['payment_option_status'],
                    'is_reimbursable'=> $post_data['is_reimbursable'],
                );
                $option = Payment_option::addPaymentOption($insert_array);
                if (!empty($option) && $option['id'] != '') {
                    return redirect('payment-options')->with('success_message', "payment option added successfully");
                }
            } else {
                $update_array = array(
                    'id' => $post_data['payment_option_id'],
                    'payment_option' => $post_data['payment_option_name'],
                    'is_active' => $post_data['payment_option_status'],
                    'is_reimbursable'=> $post_data['is_reimbursable'],
                );
                /*  $post = Product_category::findOrFail($post_data['product_category_id']);
                  $post->fill($update_array);
                  $post->save(); */
                $post = Payment_option::updatePaymentOption($update_array);
                if ($post == '1') {
                    return redirect('payment-options')->with('success_message', "Payment Option updated successfully");
                } else {
                    return redirect('payment-options')->with('success_message', "Payment Option not updated");
                }
            }
        }
    }

    public function payment_option_status_change(Request $request) {
        $id = $request->input('id');
        $status = $request->input('status');
        $response = Payment_option::payment_option_status_change($id, $status);
        $newid = "'" . $id . "'";
        $newstat = "'" . $status . "'";
        if ($status == 0) {
            $output = '<i class="fa fa-close" aria-hidden="true" onclick="change_category_status(' . $newstat . ',' . $newid . ');" title="Inactive"></i>';
        } elseif ($status == 1) {
            $output = '<i class="fa fa-check" aria-hidden="true" onclick="change_category_status(' . $newstat . ',' . $newid . ');" title="Active"></i>';
        }
        return $output;
    }

    public function edit_payment_option($id) {
        //echo $id; die();
        $formfield = helper::getFormFields("addpaymentoption");
        $encrypted = helper::encryptForm($formfield);
        $decryptedid = Crypt::decrypt($id);
        $category_details = Payment_option::findorfail($decryptedid);
        $Payment_option = new Payment_option;
        $category = $Payment_option->get()->toArray();
        // helper::pre($category,1);
        $id = Payment_option ::lastpayment();
        if (!empty($id)) {
            $lastmode = $id['id'];
        } else {
            $lastmode = '';
        }
        if (!empty($category_details)) {
            return view('payment_option', compact('category_details', 'category', 'encrypted', 'lastmode'));
        }
        //dd($Lead_strength);
    }

    public function load_payment_option(Request $request) {
        $output = '';
        $id = $request->input('id');
        $page = $this->items_per_page;
        $loan = Payment_option::get_load_product_category($page, $id);
        //  helper::pre($loan,1);
        if (count($loan) > 0) {
            foreach ($loan as $data) {
                $output .= '<tr id="' . $data['id'] . '"><td>' . $data['payment_option'] . '</td><td class="text-center" id="stat' . $data['id'] . '">';
                if ($data['is_active'] == '1') {
                    $newstat = "'" . $data['is_active'] . "'";
                    $newid = "'" . $data['id'] . "'";
                    $output .= '<i class="fa fa-check" aria-hidden="true" title="Active" style="cursor: pointer;" onclick="change_category_status(' . $newstat . ',' . $newid . ');"></i>';
                } else {
                    $newstat = "'" . $data['is_active'] . "'";
                    $newid = "'" . $data['id'] . "'";
                    $output .= '<i class="fa fa-times" aria-hidden="true" title="Inactive" style="cursor: pointer;" onclick="change_category_status(' . $newstat . ',' . $newid . ');"></i>';
                }
                if ($data['is_reimbursable'] == '1') {
                $output .='<td data-hide="phone,tablet" class="text-center">Yes</td>';
                }else{
                  $output .='<td data-hide="phone,tablet" class="text-center">No</td>';
                }
                $output .= '</td><td class="text-center viewgrp-dropdown dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a><ul class="dropdown-menu"><li><a href="' . route('editPaymentOption', ['id' => Crypt::encrypt($data['id'])]) . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li></ul></td></tr>';
            }

        }
        echo $output;
    }

 public function listExpenseType() {
        $formfield = helper::getFormFields("addexpensetype");
        $encrypted = helper::encryptForm($formfield);
        $page = $this->items_per_page;
        $category = Expense_type::all_expense_type($page);
        $id = Expense_type::lastexpense();
        if (!empty($id)) {
            $lastmode = $id['id'];
        } else {
            $lastmode = '';
        }
        //helper::pre($category,1);
        return view('expense_type', compact('category', 'encrypted',  'lastmode'));
    }

    public function add_expense_type(Request $request) {
        $dec_id = '';
        $allRequest = $request->all();

        $white_lists = helper::getFormFields("addexpensetype");

        $ignore_keys = array('_token');
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);

        //helper::pre($post_data,1);
        if ($post_data['expense_type_id'] != '') {
            $dec_id = $post_data['expense_type_id'];
        }
        $rule = ['expense_type_name' => 'required|unique:expense_types,expense_type,' . $dec_id];
        $validator = Validator::make($post_data, $rule);
        if ($validator->fails()) {
            $p_data = ['expense_type_name' => $post_data['expense_type_name'],
                'expense_type_status' => $post_data['expense_type_status'],
            ];
            return redirect()->back()->withErrors($validator)->with('status', $p_data);
        } else {
            if ($post_data['expense_type_id'] == '') {
                $insert_array = array(
                    'expense_type' => $post_data['expense_type_name'],
                    'is_active' => $post_data['expense_type_status'],
                );
                $option = Expense_type::addExpenseType($insert_array);
                if (!empty($option) && $option['id'] != '') {
                    return redirect('expense-type')->with('success_message', "Expense type added successfully");
                }
            } else {
                $update_array = array(
                    'id' => $post_data['expense_type_id'],
                    'expense_type' => $post_data['expense_type_name'],
                    'is_active' => $post_data['expense_type_status'],
                );
                /*  $post = Product_category::findOrFail($post_data['product_category_id']);
                  $post->fill($update_array);
                  $post->save(); */
                $post = Expense_type::updateExpenseType($update_array);
                if ($post == '1') {
                    return redirect('expense-type')->with('success_message', "Expense typen updated successfully");
                } else {
                    return redirect('expense-type')->with('success_message', "Expense type not updated");
                }
            }
        }
    }

 public function expense_type_status_change(Request $request) {
        $id = $request->input('id');
        $status = $request->input('status');
        $response = Expense_type::expense_type_status_change($id, $status);
        $newid = "'" . $id . "'";
        $newstat = "'" . $status . "'";
        if ($status == 0) {
            $output = '<i class="fa fa-close" aria-hidden="true" onclick="change_category_status(' . $newstat . ',' . $newid . ');" title="Inactive"></i>';
        } elseif ($status == 1) {
            $output = '<i class="fa fa-check" aria-hidden="true" onclick="change_category_status(' . $newstat . ',' . $newid . ');" title="Active"></i>';
        }
        return $output;
    }

 public function edit_expense_type($id) {
        //echo $id; die();
        $formfield = helper::getFormFields("addexpensetype");
        $encrypted = helper::encryptForm($formfield);
        $decryptedid = Crypt::decrypt($id);
        $category_details = Expense_type::findorfail($decryptedid);
        $Expense_type = new Expense_type;
        $category = $Expense_type->get()->toArray();
        // helper::pre($category,1);
        $id = Expense_type ::lastexpense();
        if (!empty($id)) {
            $lastmode = $id['id'];
        } else {
            $lastmode = '';
        }
        if (!empty($category_details)) {
            return view('expense_type', compact('category_details', 'category', 'encrypted', 'lastmode'));
        }
        //dd($Lead_strength);
    }

        public function load_expense_type(Request $request) {
        $output = '';
        $id = $request->input('id');
        $page = $this->items_per_page;
        $loan = Expense_type::get_load_expense_type($page, $id);
        //  helper::pre($loan,1);
        if (count($loan) > 0) {
            foreach ($loan as $data) {
                $output .= '<tr id="' . $data['id'] . '"><td>' . $data['expense_type'] . '</td><td class="text-center" id="stat' . $data['id'] . '">';
                if ($data['is_active'] == '1') {
                    $newstat = "'" . $data['is_active'] . "'";
                    $newid = "'" . $data['id'] . "'";
                    $output .= '<i class="fa fa-check" aria-hidden="true" title="Active" style="cursor: pointer;" onclick="change_category_status(' . $newstat . ',' . $newid . ');"></i>';
                } else {
                    $newstat = "'" . $data['is_active'] . "'";
                    $newid = "'" . $data['id'] . "'";
                    $output .= '<i class="fa fa-times" aria-hidden="true" title="Inactive" style="cursor: pointer;" onclick="change_category_status(' . $newstat . ',' . $newid . ');"></i>';
                }
                $output .= '</td><td class="text-center viewgrp-dropdown dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a><ul class="dropdown-menu"><li><a href="' . route('editExpenseType', ['id' => Crypt::encrypt($data['id'])]) . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li></ul></td></tr>';
            }

        }
        echo $output;
    }

    public function import(Request $request) {
        $allRequest = $request->all();
        //helper::pre($allRequest,1);
        if (count($allRequest)) {
            $expfile = $_FILES["uploadBtn"]["name"];
            $expfilename = explode('.', $expfile);
            $fileext = end($expfilename);
            if ($fileext != 'xlsx') {
                return redirect('uploadExcel')->with('error_message', 'Please upload .xlsx file only!');
            }
            $file = $request->uploadBtn;
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rowcount = 1;
            $arrayValIndexes = array();
            $upload_data = array();
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $arrayVal = array();
                $innercount = 1;
                foreach ($cellIterator as $cell) {
                    if ($rowcount == 1) {
                        array_push($arrayValIndexes, $cell->getValue());
                    }
                    if ($rowcount > 1) {
                        if ($innercount == 1) {
                            $arrIndex = 'client';
                        }
                        if ($innercount == 2) {
                            $arrIndex = 'agreed_funding_terms';
                        }
                        if ($innercount == 3) {
                            $arrIndex = 'current_funding_position';
                        }
                        if ($innercount == 4) {
                            $arrIndex = 'exposure_to_the_business';
                        }
                        if ($innercount == 5) {
                            $arrIndex = 'sales_executive';
                        }

                        $arrayVal[$arrIndex] = $cell->getValue();
                        $innercount++;
                    }
                }
                if ($rowcount > 1) {
                    array_push($upload_data, $arrayVal);
                }
                $rowcount++;
            }
            // helper::pre($upload_data, 0);
            //helper::pre($arrayValIndexes, 0);
            $all_client = Outside_funding_arrangement::client_details();
            //helper::pre($all_client, 0);
            $insert_array = array();
            Outside_funding_arrangement::truncate_table();
            foreach ($upload_data as $key => $value) {
                foreach ($all_client as $key1 => $value1) {
                    if ($value1['company_name'] == $value['client']) {
                        $insert_array['client_id'] = $value1['customer_id'];
                        $insert_array['agreed_funding_terms'] = $value['agreed_funding_terms'];
                        $insert_array['current_funding_position'] = $value['current_funding_position'];
                        $insert_array['exposure_to_the_business'] = $value['exposure_to_the_business'];
                        $insert_array['sale_id'] = $value1['sale_id'];
                        $insert = Outside_funding_arrangement::funding_update($insert_array);
                    }
                }
            }
            return redirect('uploadExcel')->with('success_message', 'Data uploaded Successfully');
        }
        $outside_funding = Outside_funding_arrangement::funding_list();
        //helper::pre( $outside_funding, 1);
        return view('import',compact('outside_funding'));
    }

    public function export() {
        $new_arrray = array();
        $all_data = Outside_funding_arrangement::client_details();
        //helper::pre($all_data, 1);
        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        /*         * *********for bold heading  ************* */
        $sheet->getStyle('A1')->applyFromArray($styleArray);
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('C1')->applyFromArray($styleArray);
        $sheet->getStyle('D1')->applyFromArray($styleArray);
        $sheet->getStyle('E1')->applyFromArray($styleArray);
        /*         * *********for set column width  ************* */
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->setCellValue('A1', 'Client');
        $sheet->setCellValue('B1', 'Agreed Funding Terms');
        $sheet->setCellValue('C1', 'Current Funding Position');
        $sheet->setCellValue('D1', 'Exposure to the Business');
        $sheet->setCellValue('E1', 'Sales Executive');
        $outside_funding = Outside_funding_arrangement::funding_details();
        //helper::pre($outside_funding, 1);
        //helper::pre(count($outside_funding), 1);
        foreach ($all_data as $key => $value) {
            if(count($outside_funding)!=0){
            foreach ($outside_funding as $key1 => $value1) {
                    if ($value1['client_id'] == $value['customer_id']) {
                        $new_arrray[$key]['company_name'] = $value['company_name'];
                        $new_arrray[$key]['agreed_funding_terms'] = $value1['agreed_funding_terms'];
                        $new_arrray[$key]['current_funding_position'] = $value1['current_funding_position'];
                        $new_arrray[$key]['exposure_to_business'] = $value1['exposure_to_business'];
                        $new_arrray[$key]['sale_executive'] = $value['sale_executive'];
                        break;
                    } else {
                        $new_arrray[$key]['company_name'] = $value['company_name'];
                        $new_arrray[$key]['agreed_funding_terms'] = '';
                        $new_arrray[$key]['current_funding_position'] = '';
                        $new_arrray[$key]['exposure_to_business'] = '';
                        $new_arrray[$key]['sale_executive'] = $value['sale_executive'];
                    }
                }
            } else {
                $new_arrray[$key]['company_name'] = $value['company_name'];
                $new_arrray[$key]['agreed_funding_terms'] = '';
                $new_arrray[$key]['current_funding_position'] = '';
                $new_arrray[$key]['exposure_to_business'] = '';
                $new_arrray[$key]['sale_executive'] = $value['sale_executive'];
            }
        }
        $rowCount = 2;
        foreach ($new_arrray as $element) {
            $sheet->SetCellValue('A' . $rowCount, $element['company_name']);
            $sheet->SetCellValue('B' . $rowCount, $element['agreed_funding_terms']);
            $sheet->SetCellValue('C' . $rowCount, $element['current_funding_position']);
            $sheet->SetCellValue('D' . $rowCount, $element['exposure_to_business']);
            $sheet->SetCellValue('E' . $rowCount, $element['sale_executive']);
            $rowCount++;
        }

        //helper::pre($new_arrray, 1);
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
//         header("Content-Type: application/vnd.ms-excel");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="outside_funding.xlsx"');
        $writer->save("php://output");
        die();
    }

    public function report_bug(Request $request) {
        $data_array= array();
        if (($request->subject) == '' || ($request->description) == '') {
            return redirect()->back();
        } else {
            $data_array['bug_subject']=$request->subject;
            $data_array['bug_details']=$request->description;
            $data_array['added_by_user_id']=Auth::id();
            $data_array['bug_status']=1;
            $insert = Report_bug::add_bug_report($data_array);
            if($insert){
                return redirect()->back();
            }
        }
    }

     public function bug_report_list(Request $request) {
        $search='no';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        //helper::pre($request->all(),0);
        if ($request->has('_token')) {
            if ($request->input('fromdate') != '') {
                $postArr['fromdate'] = $request->input('fromdate');
            }
            if ($request->input('todate') != '') {
                $postArr['todate'] = $request->input('todate');
            }
            $search='yes';
            $all_data = Report_bug::searchQuerie($postArr);
            //helper::pre($all_data,0);
        } else {
            $per_page = $this->items_per_page;
            $all_data = Report_bug::fetch_all_bug_report($per_page);
        }
        //helper:: pre($all_data,1);
        return view('bug_list', compact('all_data','postArr','search'));
    }

     public function load_bug_report(Request $request) {
        $output = '';
        $id = $request->input('id');
        $page = $this->items_per_page;
        $report = Report_bug::get_load_bug_report($page, $id);
        if (count($report) > 0) {
            foreach ($report as $data) {
                $output .= '<tr id="' . $data['id'] . '"><td>BUG_00' . $data['id'] . '</td>'
                        . '<td>' . $data['bug_subject'] . '</td>'
                        . '<td>' . $data['bug_details'] . '</td>'
                        . '<td>' . $data['added_by_user_id'] . '</td>'
                        . '<td>' . date('d/m/Y',strtotime($data['bug_posted_on'])). '</td>';
//                if ($data['bug_status'] == '1') {
//                    $output .= '<td>Active</td>';
//                } else {
//                    $output .= '<td>Close</td>';
//                }

            }
        }
        echo $output;
    }

}



?>
