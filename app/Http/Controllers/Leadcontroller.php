<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\model\customer;
use App\model\customer_contact_person;
use App\model\customer_attachment;
use App\model\Product;
use App\model\Lead;
use App\model\Lead_product;
use App\model\Lead_activity;
use App\model\Lead_activity_mode;
use App\model\Lead_strength;
use App\model\Lead_supporting_document;
use App\model\Map_customer_salesperson;
use App\model\Onboarding_document;
use App\model\Website_setting;
use App\User;
use Mail;
use App\Mail\newLeadCreateMail;
Use App\Mail\completeLeadSendMail;
Use App\Mail\onboardingMail;
use Session;
use Validator;
use helper;

class Leadcontroller extends Controller
{
	public function __construct()
    {
 
       //$this->items_per_page = '3'; 
        $this->items_per_page = Config::get('formArray.items_per_page');
    }
    
    public function add_lead(Request $request)
    { 
        $model      = helper::getFormFields("addlead");
        $formfield  = helper::encryptForm($model); 

        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectactiveuser('SP');
        $strengths = Lead_strength::strengthlist();
        $lead_data = array(
                            'id' =>''
                        );

        return view('/add_lead',compact('formfield','customers','products','salesperson','lead_data','strengths'));
    }

    public function edit_lead($id)
    { 
        try 
        {
            $id =  decrypt($id);
        } 
        catch (DecryptException $e) 
        {
           return redirect('/list_lead');
        }
        
        $iscomplete = false; 
        $lead_data = Lead::getlead($id);
        $iscomplete = $lead_data['is_completed'];
        $isactive = $lead_data['is_active'];
        if($iscomplete=='1')
        {
         return redirect('/list_lead');
        }

        if (Auth::user()->user_type == 'SP')
        {
            if($isactive=='0')
            {
             return redirect('/list_lead');
            }
        }

        $model      = helper::getFormFields("addlead");
        $formfield  = helper::encryptForm($model); 

        //$customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');
        //helper::pre($lead_data,1);
        //$lead_data = Lead::getlead($id);
        $leadproducts = Lead_product::fetchprod($id);
        $strengths = Lead_strength::strengthlist();

        $allprodid = Lead_product::fetchprodid($id);
        $existing_product = Product::leadproduct($allprodid);

        $customer_details = customer::fetchcustomcol('company_name',$lead_data['custom_id']);
        $customer_attachments = customer_attachment::attach_details($lead_data['custom_id']);      
        $contact_person = customer_contact_person::allcontact_person($lead_data['custom_id']);  
        $contact_person_details = customer_contact_person::persondetails($lead_data['customer_contact_person_id']);

        //$salesperson = User::all_sales_person();
        //helper::pre($lead_data['custom_id'],1);
        $leaSaleperson=$lead_data['sales_person_id'];
        $sp = Map_customer_salesperson::fetchSP($lead_data['custom_id']);
        //helper::pre($sp,1);
        $nonExclusiveSp = Map_customer_salesperson::non_lock_exclusive_sp($id, $sp, $leaSaleperson);
        //helper::pre($nonExclusiveSp,1);
        return view('/edit_lead',compact('formfield','customer_details','products','salesperson','nonExclusiveSp','lead_data','leadproducts','existing_product','customer_attachments','contact_person','contact_person_details','strengths'));
    }

    public function select_contact(Request $request)
    { 
        $id = $request->input('id');
        $contact_person = customer_contact_person::allcontact_person($id);
        if (count($contact_person)>0) 
        {     
            $output = '<option value="">Select</option>';       
            foreach ($contact_person as $post) 
            {
                $output .= '<option value="'.$post['id'].'">'.$post['contact_person_name'].'</option>';
            }
        }
        echo $output;
    }

    public function select_contact_details(Request $request)
    { 
        $id = $request->input('id');
        $details = customer_contact_person::persondetails($id);

        if (count($details)>0) 
        {     
            $output = '<div class="lead-person-details" >
                          <h3>'.$details['contact_person_name'].'</h3>
                          <p><a href="#"><i class="fa fa-globe"></i> '.$details['contact_person_email1'].'</a></p>
                          <p><i class="fa fa-phone"></i> '.$details['contact_person_phone1'].'</p>
                        </div>';
        }
        echo $output;
    }


    public function select_customer_attachment(Request $request)
    { 
        $id = $request->input('id');
        $attach_details = customer_attachment::attach_details($id);
        if(count($attach_details)>0)
        { 
            //helper::pre($attach_details);exit;
            $output = '<div class="document-block" >
                          <h4>Agreement Documents</h4><ul>';
            foreach($attach_details as $details)
            {
                $output .= ' <li>'.$details['customer_attachment_name'].'<a href="'.asset('public/uploads/customer/'.$details['customer_attachment_file_name']).'" download><i class="fa fa-eye" aria-hidden="true"></i></a></li>';
            }                     

            $output .= '</ul></div>';
        }
        else{
            $output = '<div class="document-block" >
                          <h4>Agreement Documents</h4>No attached documents</div>';
        }
        echo $output;
    }


    public function product_details(Request $request)
    { 
        $id = $request->input('id');
        $quantity = $request->input('quantity');
        $margin_value = $request->input('margin_value');
        $end_margin_field = $request->input('end_margin');
        $proddetail = Product::product_details($id);
        if (count($proddetail)>0) 
        {    
            $margin = "'".$proddetail['margin_value']."'"; 
            $end_margin = "'".$proddetail['end_margin']."'";  
            $script_quan = "'".$quantity."'";    
            $script_id = "'".$id."'";    
            $output = '<tr id="row'.$id.'">
                  <td>'.$proddetail['prod_name'].'</td>
                  <td>'.$proddetail['margin_value'].'<input type="hidden" name="'.$margin_value.'[]" value="'.$proddetail['margin_value'].'"></td>
                  <td>'.$proddetail['end_margin'].'<input type="hidden" name="'.$end_margin_field.'[]" value="'.$proddetail['end_margin'].'"></td>
                  <td>
                    <input type="text" id="'.$quantity.''.$id.'" name="'.$quantity.'[]"  class="form-control" min="1" onkeyup="changetotal('.$margin.','.$end_margin.','.$script_quan.','.$script_id.')">
                  </td>
                  <td class="text-center" id="grosstot'.$id.'">0</td>
                  <td class="text-center" id="nettot'.$id.'">0</td>
                  <td class="text-center viewgrp-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">
                      <li><a href="#" onclick="removeproduct('.$script_id.')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                    </ul>
                  </td>
                </tr>'; 
        }
        echo $output;
    }

    public function submit_lead(Request $request) {

        $white_lists = helper::getFormFields("addlead");
        $ignore_keys = array('_token');
        $allRequest = $request->all();
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        $return_id = 0;
        $file_data = helper::decryptForm($_FILES, $white_lists, $ignore_keys);
        $decrypt_customer_id=$post_data['customer_id'];
        $decrypt_id = 0;
        if ($post_data['id'] != '') {
            $decrypt_id = Crypt::decrypt($post_data['id']);
            $decrypt_customer_id=Crypt::decrypt($post_data['customer_id']);
        }
        //echo $decrypt_customer_id;exit;
        //helper::pre($post_data,1);
        if (!$post_data) {
            return redirect('/list_lead');
        } else {
            //helper::pre($decrypt_customer_id,1);
            Map_customer_salesperson::insertSP($decrypt_customer_id, $post_data['sales_person_id']);
            $all_data = array();

            $all_data['custom_id'] = isset($post_data['customer_id']) ? $post_data['customer_id'] : '';
            $all_data['customer_contact_person_id'] = isset($post_data['customer_contact_person_id']) ? $post_data['customer_contact_person_id'] : '';
            $all_data['sales_person_id'] = isset($post_data['sales_person_id']) ? $post_data['sales_person_id'] : '';
            $all_data['additional_info'] = isset($post_data['additional_info']) ? $post_data['additional_info'] : '';
            $all_data['lead_source'] = isset($post_data['lead_source']) ? $post_data['lead_source'] : '';
            $all_data['lead_created_on'] = date('Y-m-d H:i:s');
            $all_data['updated_at'] = date('Y-m-d H:i:s');
            $all_data['updated_by'] = Auth::id();
            $all_data['lead_strength_id'] = $post_data['strength'];
            $products = explode(',', $post_data['products']);
            //print_r($products);exit;
            if ($decrypt_id == 0) {
                $all_data['is_active'] = '1';
                $return_id = Lead::insertlead($all_data);
                Lead_activity::insertactivity($return_id, 'added', '1', '0');
            } elseif ($decrypt_id != 0) {
                $all_data['is_active'] = $post_data['is_active'];
                $return_id = $all_data['id'] = $decrypt_id;
                Lead::editlead($all_data);
                Lead_activity::insertactivity($return_id, 'edited', '1', '0');
            }
            //echo $decrypt_id;exit;
            if ($return_id != 0) {
                Lead_product::deleteleadproduct($return_id);
                if (count($products) > 1) {
                    if (isset($post_data['margin_value'])) {
                        $data = array();
                        $data['lead_id'] = $return_id;
                        if (is_array($post_data['margin_value']) && count($post_data['margin_value'] > 0)) {
                            foreach ($post_data['margin_value'] as $key1 => $value1) {
                                $arr['margin_value'] = isset($post_data['margin_value'][$key1]) ? $post_data['margin_value'][$key1] : '';
                                $arr['end_margin'] = isset($post_data['end_margin'][$key1]) ? $post_data['end_margin'][$key1] : '';
                                $arr['quantity'] = isset($post_data['quantity'][$key1]) ? $post_data['quantity'][$key1] : '';
                                $arr['prod_id'] = $products[$key1 + 1];
                                array_push($data, $arr);
                            }
                            Lead_product::insertlead($data);
                        }
                    }
                }
            }
  
//            $lead_type = Lead_strength::find($post_data['strength'])->toArray();
            $lead_type = Lead_strength::find($post_data['strength']);
            if(count($lead_type)>0)
            {
               $lead_type =$lead_type->toArray(); 
               $loan_type=$lead_type['loan_type'];
            }else{
               $loan_type='New' ;
            }
            //helper::pre($lead_type,1);
            
            if (strpos($loan_type, 'Hold') !== false ||strpos($loan_type, 'hold') !== false) {
                $customer_map = Map_customer_salesperson::where('customer_id', $decrypt_customer_id)->update(['is_lead_on_hold' => 1]);
            }
             else {
                $customer_map = Map_customer_salesperson::where('customer_id', $decrypt_customer_id)->update(['is_lead_on_hold' => 0,'lead_started_on' => date('Y-m-d H:i:s')]);
            }
          
            //helper::pre($lead_type,1);
            if ($decrypt_id == 0) {
                $user = Auth::user()->toArray();
                $all_sp = User::get_all_sales_person();
                $mail = array();
                foreach ($all_sp as $key => $value) {
                    array_push($mail, $value['email']);
                }
                $content = [
                    'subject' => "New lead created",
                    'user' => $user['display_name'],
                ];
                $mailstatus = Mail::to($mail)->send(new newLeadCreateMail($content));
                // echo(time());
                $customer_map = Map_customer_salesperson::where('customer_id', $decrypt_customer_id)->update(['lead_started_on' => date('Y-m-d H:i:s')]);
                return redirect('/add_lead')->with('success_message', 'Lead Added Successfully!');
            } elseif ($decrypt_id != 0) {
                return redirect("/edit_lead/" . $post_data['id'])->with('success_message', 'Lead edited successfully!');
            }
             

        }
    }

    public function list_lead(Request $request)
    { 
        $postArr['customer_name'] = '';
        $postArr['registration_number'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '1';
        
        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');
        $postArr['perPage']  = $this->items_per_page;

        if(Auth::user()->user_type=='SP')
        {
            $id = Lead::lastleadofSP();
            if(!empty($id))
            {              
                $lastlead = $id['id'];
            }
            else
            {
                $lastlead = '';
            }
        }
        else
        {
            $lastlead = '';
        }

        $perPage  = $this->items_per_page;
        $lead_details = Lead::fetchleads($perPage);
        $all_modes = Lead_activity_mode::fetchmodes();
        $lead_strengths= Lead_strength::all()->sortBy("loan_type")->toArray();
        $lock_days['customer_exclusive_lock_days'] = Website_setting::exclusive_lock_days();
         foreach ($lead_details as $key => $value) {
            if ($value['lead_started_on'] != ''&& $value['is_executive_for_life']!=1 && $value['is_lead_on_hold']!=1 ) {
                $today = date('Y-m-d H:i:s');
                $lead_date = $value['lead_started_on'];
                $date1 = date_create($today);
                $date2 = date_create($lead_date);
                $diff = date_diff($date2, $date1);
                $dayDiff = $diff->format("%R%a");
                $day_left = max($lock_days['customer_exclusive_lock_days'] - $dayDiff,0);
                $lead_details[$key]['lock_days'] = $day_left;
            } else {
                $lead_details[$key]['lock_days'] = '';
            }
        }
        //helper::pre($lead_details,1);
        return view('/lead_list',compact('postArr','lead_details','customers','products','salesperson','perPage','lastlead','all_modes','lead_strengths'));

    }

    public function loadlead(Request $request)
    {   
        $output = '';
        $id = $request->input('id');

        $postArr['perPage']  = $this->items_per_page;

        $perPage  = $this->items_per_page;
        $posts = Lead::loadAjaxlead($perPage,$id);
         $lock_days['customer_exclusive_lock_days'] = Website_setting::exclusive_lock_days();
         foreach ($posts as $key => $value) {
            if ($value['lead_started_on'] != ''&& $value['is_executive_for_life']!=1 && $value['is_lead_on_hold']!=1 ) {
                $today = date('Y-m-d H:i:s');
                $lead_date = $value['lead_started_on'];
                $date1 = date_create($today);
                $date2 = date_create($lead_date);
                $diff = date_diff($date2, $date1);
                $dayDiff = $diff->format("%R%a");
                $day_left = max($lock_days['customer_exclusive_lock_days'] - $dayDiff,0);
                $posts[$key]['lock_days'] = $day_left;
            } else {
                $posts[$key]['lock_days'] = '';
            }
        }    
       // helper::pre($posts,1);
        if (!$posts->isEmpty()) 
        {            
            foreach ($posts as $post) {
                  
                  $delid = "'".Crypt::encrypt($post['id'])."'";

                  $output .='<tr id="'.$post['id'].'">
                  <td><a class="table-link" href="'.route('lead-details',['id' => Crypt::encrypt($post['id']) ]).'">L00'.$post['id'].'</a></td>
                  <td>'.$post['company_name'].'<span class="pull-right-container"><small class="label pull-right bg-red">'.$post['lock_days'].'</small></span></td>';
                  $output .='<td>'.$post['registration_number'].'</td>';
                  if (Auth::user()->user_type != 'SP')
                  {
                  $output .='<td>'.$post['display_name'].'</td>';
                  }
                  $output .='<td>'.$post['totprod'].'</td>
                  <td>'.number_format($post['valuation'],2).'</td>';
                  if($post['lead_strength_id']!='0')
                  {
                  $output .='<td>'.$post['loan_type'].'</td>';
                  }
                  else if($post['lead_strength_id']=='0')
                  {
                  $output .='<td>New</td>';
                  }
                  
                  if($post['updated_at']!='')
                  {
                  $output .='<td>'.date('d/m/Y',strtotime($post['updated_at'])).'</td>';
                  }
                  else if($post['updated_at']=='')
                  {
                  $output .='<td></td>';
                  }
                  $output .='<td class="text-center viewgrp-dropdown dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">';
                  if($post['is_completed']=='0')
                  {
                   $output .= '<li><a href="'.route('edit-lead',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>';
                  }
                  $output .='<li><a href="'.route('lead-details',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-eye" aria-hidden="true"></i>View</a></li>';
                  if (Auth::user()->user_type == 'MA')
                  {    
                  $message = "'".'Are you sure want to delete this lead?'."'";             
                  $output.='<li><a href="'.route('delete-lead',['id' => Crypt::encrypt($post['id']) ]).'" onclick="return confirm('.$message.')"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</a></li>';
                  }  
                  if (Auth::user()->user_type == 'SP' && $post['is_completed']=='0')
                  {                 
                  $output.='<li><a href="javascript:void(0)" data-toggle="modal"  id="'.($post['id']).'" onClick="openModal('.$post['id'].')"><i class="fa fa-plus" aria-hidden="true"></i>Add Activity</a></li>';
                  }    
                  $output .='</ul>
                  </td>
                </tr>';           
            }
            echo $output;
        }
    }
    
    public function searchlead(Request $request)
    {  
        $postArr['customer_name'] = '';
        $postArr['registration_number'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '1';
        $postArr['perPage']  = $this->items_per_page;
        
        
        if($request->has('customer_name')){
            $postArr['customer_name'] = $request->input('customer_name');
        }
        
        if($request->has('registration_number')){
            $postArr['registration_number'] = $request->input('registration_number');
        }

        if($request->has('sale_person')){
            $postArr['sale_person'] = $request->input('sale_person');
        }

        if($request->has('product')){
            $postArr['product'] = $request->input('product');
        }

        if($request->has('lead_id')){
            $postArr['lead_id'] = $request->input('lead_id');
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }
        //echo $request->input('status');exit;
        if($request->has('status')){  
            if($request->input('status')=='1') 
            {
                $postArr['status'] = '1';
            }   
            elseif($request->input('status')=='0') 
            {
                $postArr['status'] = '0';
            }  
            //echo $postArr['status'];exit;
        }
        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');

        $lead_details = Lead::searchleads($postArr);
        $lock_days['customer_exclusive_lock_days'] = Website_setting::exclusive_lock_days();
        foreach ($lead_details as $key => $value) {
            if ($value['lead_started_on'] != '' && $value['is_executive_for_life']!=1 && $value['is_lead_on_hold']!=1 ) {
                $today = date('Y-m-d H:i:s');
                $lead_date = $value['lead_started_on'];
                $date1 = date_create($today);
                $date2 = date_create($lead_date);
                $diff = date_diff($date2, $date1);
                $dayDiff = $diff->format("%R%a");
                $day_left = max($lock_days['customer_exclusive_lock_days'] - $dayDiff,0);
                $lead_details[$key]['lock_days'] = $day_left;
            } else {
                $lead_details[$key]['lock_days'] = '';
            }
        }
        //helper::pre($lead_details,1);

        $lastlead = '';
        $all_modes = Lead_activity_mode::fetchmodes();
        $lead_strengths= Lead_strength::all()->sortBy("loan_type")->toArray();
        return view('/lead_list',compact('postArr','lead_details','customers','products','salesperson','lastlead','all_modes','lead_strengths'));
    }

    public function customer_add_lead($id,$comp_name)
    {
        $id  = Crypt::decrypt($id);
        $attach_details = customer_attachment::attach_details($id);
        ///echo count($attach_details);exit;
        if(count($attach_details)>0)
        { 
            $output = '<div class="document-block" >
                          <h4>Agreement Documents</h4><ul>';
            foreach($attach_details as $details)
            {
                $output .= ' <li>'.$details['customer_attachment_name'].'<a href="'.asset('public/uploads/customer/'.$details['customer_attachment_file_name']).'" download><i class="fa fa-eye" aria-hidden="true"></i></a></li>';
            }                     

            $output .= '</ul></div>';
        }
        else{
            $output = '<div class="document-block" >
                          <h4>Agreement Documents</h4>No attached documents</div>';
        }
        /*$contact_person = customer_contact_person::allcontact_person($id);  
        Session::flash('customer_attachments', $output);
        Session::flash('contact_person', $contact_person);
        Session::flash('customer_id', $id);
        Session::flash('customer', $comp_name);
        return redirect('/add_lead');*/

        $spIDs = Map_customer_salesperson::fetchSP($id);
        $spID = $spIDs['user_id'];
        $customerlist = customer::spcustomers($spID);
        $contact_person = customer_contact_person::allcontact_person($id); 

        Session::flash('customerlist', $customerlist);
        Session::flash('customer_attachments', $output);
        Session::flash('contact_person', $contact_person);
        Session::flash('customer_id', $id);
        Session::flash('customer',$comp_name);
        Session::flash('sp_id', $spID);
        return redirect('/add_lead');
    }   

    public function lead_details($id)
    { 

        try 
        {
            $id =  decrypt($id);
        } 
        catch (DecryptException $e) 
        {
           return redirect('/list_lead');
        }
        
        $lead_data = Lead::getlead($id);
        /*$isactive = $lead_data['is_active'];
        
        if (Auth::user()->user_type == 'SP')
        {
            if($isactive=='0')
            {
             return redirect('/list_lead');
            }
        }*/

        $postArr['act_type'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '2';
        $postArr['act_mode']  = '';

        $model      = helper::getFormFields("leaddetails");
        $formfield  = helper::encryptForm($model); 
        
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');
        //$lead_data = Lead::getlead($id);
        $leadproducts = Lead_product::fetchprod($id);

        $allprodid = Lead_product::fetchprodid($id);
        //print_r($allprodid);
        $existing_product = Product::leadproduct($allprodid);

        $customer_details = customer::fetchcustomcol('company_name',$lead_data['custom_id']); 
        $contact_person_details = customer_contact_person::persondetails($lead_data['customer_contact_person_id']);

        $all_activity = Lead_activity::fetchactivity($id);
        $all_modes = Lead_activity_mode::fetchmodes($id);
        //print_r($allprodid);exit;
       // helper::pre($all_modes,1);
        $supportdoc = Lead_supporting_document::supportdocs($id);
        return view('/lead_details',compact('formfield','customer_details','products','salesperson','lead_data','leadproducts','existing_product','contact_person_details','all_activity','all_modes','postArr','supportdoc'));
    }

    public function searchactivity(Request $request)
    {
        //$allRequest = $request->all();
        //helper::pre($allRequest,1);exit;
        $id  = Crypt::decrypt($request->input('id'));
        $postArr['act_type'] = '0';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['act_mode']  = '0';
        $postArr['id']  = $id;
                
        if($request->has('act_mode')){
            $postArr['act_mode'] = $request->input('act_mode');
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }
        
        if($request->has('act_type')){  
            if($request->input('act_type')=='1') 
            {
                $postArr['act_type'] = '1';
            }   
            elseif($request->input('act_type')=='2') 
            {
                $postArr['act_type'] = '2';
            }  
            else{
                $postArr['act_type'] = '0';
            }
        }

        $model      = helper::getFormFields("leaddetails");
        $formfield  = helper::encryptForm($model); 
        
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');
        $lead_data = Lead::getlead($id);
        $leadproducts = Lead_product::fetchprod($id);

        $allprodid = Lead_product::fetchprodid($id);
        $existing_product = Product::leadproduct($allprodid);

        $customer_details = customer::fetchcustomcol('company_name',$lead_data['custom_id']); 
        $contact_person_details = customer_contact_person::persondetails($lead_data['customer_contact_person_id']);

        
        $all_modes = Lead_activity_mode::fetchmodes($id);

        $all_activity = Lead_activity::searchleadactivity($postArr);
        
        //helper::pre($all_activity);exit;
        return view('/lead_details',compact('formfield','customer_details','products','salesperson','lead_data','leadproducts','existing_product','contact_person_details','all_activity','all_modes','postArr'));
    }


    
    
    public function add_activity(Request $request) {
        $allRequest = $request->all();
//        helper::pre($allRequest,1);
        $lead_id = $request->lead_id;
        $note = $request->activity_note;
        $activity_mode_id = $request->act_mode;
        Lead_activity::insertactivity($lead_id, $note, '2', $activity_mode_id);
        //return redirect()->back()->with('success_message', 'New Activity Added Successfully!');
         return redirect('list_lead')->with('success_message', 'New Activity Added Successfully!');
    }
    public function add_activity_lead_deatais_page(Request $request) {
        $allRequest = $request->all();
//        helper::pre($allRequest,1);
        $lead_id = $request->lead_id;
        $note = $request->activity_note;
        $activity_mode_id = $request->act_mode;
        Lead_activity::insertactivity($lead_id, $note, '2', $activity_mode_id);
        return redirect()->back()->with('success_message', 'New Activity Added Successfully!');
         //return redirect('list_lead')->with('success_message', 'New Activity Added Successfully!');
    }
    
    public function Upload_Support_Docs(Request $request) {

        $white_lists = helper::getFormFields("addlead");
        $ignore_keys = array('_token');
        $allRequest = $request->all();
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        $file_data = helper::decryptForm($_FILES, $white_lists, $ignore_keys);
        //helper::pre($post_data,0);
        $decrypt_id = Crypt::decrypt($post_data['id']);
        //helper::pre($decrypt_id,1);
        $data = array();
        $data['lead_id'] = $decrypt_id;
        if (is_array($post_data['supportdoc']) && count($post_data['supportdoc'] > 0)) {
            //print_r($post_data);
            foreach ($post_data['supportdoc'] as $key1 => $value1) {
                $file_name = '';

                if (isset($post_data['supportdoc'][$key1])) {
                    $file_ext = pathinfo($file_data['supportdoc']['name'][$key1], PATHINFO_EXTENSION);
                    $microsec = explode(".", microtime(true));
                    $file_name[$key1] = $decrypt_id . "_" . date('Ymd_His') . $microsec[1] . "." . $file_ext;
                    $destination =  $file_name[$key1];
                    move_uploaded_file($post_data['supportdoc'][$key1], $destination);
                    $arr['supportdoc'] = $destination;
                }
                array_push($data, $arr);
            }
        }
        Lead_supporting_document::upload_documents($data);
        
        
        return redirect('list_lead')->with('success_message', 'Supported document added successfully!');
    }
    
    public function complete_lead(Request $request) {
        //helper::pre($request->all(),1);
        //die();
        $white_lists = helper::getFormFields("addlead");
        $ignore_keys = array('_token','Yes');
        $allRequest = $request->all();
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
       // $file_data = helper::decryptForm($_FILES, $white_lists, $ignore_keys);
        helper::pre($post_data,0);
        $decrypt_id = Crypt::decrypt($post_data['id']);
        //helper::pre($decrypt_id,1);
        $data = array();
        $data['lead_id'] = $decrypt_id;
        Lead_supporting_document::completeLead($data);

        //********            customar executed for life time */

        $lead_type = Lead::find($decrypt_id)->toArray();
        $customer_map = Map_customer_salesperson::where('customer_id', $lead_type['custom_id'])->update(['is_executive_for_life' => 1]);

        //*******************         */

        $user = Auth::user()->toArray();
        $all_sp = User::get_all_sales_person();
        $mail = array();
        foreach ($all_sp as $key => $value) {
            array_push($mail, $value['email']);
        }
        $content = [
            'subject' => "Lead completed successfully",
            'user' => $user['display_name'],
        ];
        $mailstatus = Mail::to($mail)->send(new completeLeadSendMail($content));
        return redirect('/submit_onboarding_info/' . $post_data['id']);
    }

    public function list_sp_lead($id)
    { 
        try 
        {
            $id =  decrypt($id);
        } 
        catch (DecryptException $e) 
        {
           return redirect('/salepersons');
        }

        $postArr['customer_name'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '1';
        
        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');
        $postArr['perPage']  = $this->items_per_page;

        if(Auth::user()->user_type=='SP')
        {
            $id = Lead::lastleadofSP();
            if(!empty($id))
            {              
                $lastlead = $id['id'];
            }
            else
            {
                $lastlead = '';
            }
        }
        else
        {
            $lastlead = '';
        }

        $perPage  = $this->items_per_page;
        $lead_details = Lead::fetchSPleads($perPage,$id);
        $salperson = $id;
        $all_modes = Lead_activity_mode::fetchmodes();
        return view('/lead_sp_list',compact('postArr','lead_details','customers','products','salesperson','perPage','lastlead','all_modes','salperson'));

    }



    public function loadsplead(Request $request)
    {   
        //echo $request->input('salperson');exit;
        $output = '';
        $id = $request->input('id');
      //  helper::pre($request,1);
        $salperson = $request->input('salperson');
        $postArr['perPage']  = $this->items_per_page;

        $perPage  = $this->items_per_page;
        $posts = Lead::loadAjaxSPlead($perPage,$id,$salperson);
        if (!$posts->isEmpty()) 
        {            
            foreach ($posts as $post) {
                
                  $output .='<tr id="'.$post['id'].'">
                  <td><a class="table-link" href="'.route('lead-details',['id' => Crypt::encrypt($post['id']) ]).'">L00'.$post['id'].'</a></td>
                  <td>'.$post['company_name'].'</td>';
                  
                  $output .='<td>'.$post['totprod'].'</td>
                  <td>'.number_format($post['valuation'],2).'</td>';
                  
                  if($post['lead_strength_id']!='0')
                  {
                  $output .='<td>'.$post['loan_type'].'</td>';
                  }
                  else if($post['lead_strength_id']=='0')
                  {
                  $output .='<td>New</td>';
                  }
                  
                  if($post['updated_at']!='')
                  {
                  $output .='<td>'.date('d/m/Y',strtotime($post['updated_at'])).'</td>';
                  }
                  else if($post['updated_at']=='')
                  {
                  $output .='<td></td>';
                  }
                  $output .='<td class="text-center viewgrp-dropdown dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">';
                  if($post['is_completed']=='0')
                  {
                   $output .= '<li><a href="'.route('edit-lead',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>';
                  }
                  $output .='<li><a href="'.route('lead-details',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-eye" aria-hidden="true"></i>View</a></li>';
                  if (Auth::user()->user_type == 'SP' && $post['is_completed']=='0')
                  {                 
                  $output.='<li><a href="javascript:void(0)" data-toggle="modal"  id="'.($post['id']).'" onClick="openModal('.$post['id'].')"><i class="fa fa-plus" aria-hidden="true"></i>Add Activity</a></li>';
                  }    
                  $output .='</ul>
                  </td>
                </tr>';           
            }
            echo $output;
        }
    }
    

    
    public function searchsplead(Request $request)
    {  
        $salperson = $request->input('salperson');
        $postArr['customer_name'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '1';
        $postArr['perPage']  = $this->items_per_page;
        
        
        if($request->has('customer_name')){
            $postArr['customer_name'] = $request->input('customer_name');
        }

        if($request->has('sale_person')){
            $postArr['sale_person'] = $request->input('sale_person');
        }

        if($request->has('product')){
            $postArr['product'] = $request->input('product');
        }

        if($request->has('lead_id')){
            $postArr['lead_id'] = $request->input('lead_id');
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }
        //echo $request->input('status');exit;
        if($request->has('status')){  
            if($request->input('status')=='1') 
            {
                $postArr['status'] = '1';
            }   
            elseif($request->input('status')=='0') 
            {
                $postArr['status'] = '0';
            }  
            //echo $postArr['status'];exit;
        }
        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');

        $lead_details = Lead::searchspleads($postArr,$salperson);
        

        $lastlead = '';
        $all_modes = Lead_activity_mode::fetchmodes();
        return view('/lead_sp_list',compact('postArr','lead_details','customers','products','salesperson','lastlead','all_modes','salperson'));
    }

public function report_dashbord(Request $request)
    {
    return view('report');
    }


    public function Leadreport(Request $request)
    { 
        $postArr['customer_name'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '';
        
        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');


        $perPage  = $this->items_per_page;
        $lead_details = Lead::leadReport();      
        $all_modes = Lead_activity_mode::fetchmodes();
        return view('/salereport',compact('postArr','lead_details','customers','products','salesperson','all_modes'));

    }

    public function SearchLeadReport(Request $request)
    {  
        $postArr['customer_name'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '1';
        
        
        if($request->has('customer_name')){
            $postArr['customer_name'] = $request->input('customer_name');
        }

        if($request->has('sale_person')){
            $postArr['sale_person'] = $request->input('sale_person');
        }

        if($request->has('product')){
            $postArr['product'] = $request->input('product');
        }

        if($request->has('lead_id')){
            $postArr['lead_id'] = $request->input('lead_id');
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }
        
        if($request->has('status')){  
            $postArr['status'] = $request->input('status');
        }

        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');

        $lead_details = Lead::searchleadsreport($postArr);
        

        $lastlead = '';
        $all_modes = Lead_activity_mode::fetchmodes();
        return view('/salereport',compact('postArr','lead_details','customers','products','salesperson','all_modes'));
    }

    public function Leadspreport(Request $request)
    { 
        $postArr['customer_name'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '';
        
        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');


        $perPage  = $this->items_per_page;
        $lead_details = Lead::leadReport();      
        $all_modes = Lead_activity_mode::fetchmodes();
        return view('/salereport',compact('postArr','lead_details','customers','products','salesperson','all_modes'));

    }

    public function SearchLeadspReport(Request $request)
    {  
        $postArr['customer_name'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '1';
        
        
        if($request->has('customer_name')){
            $postArr['customer_name'] = $request->input('customer_name');
        }

        if($request->has('sale_person')){
            $postArr['sale_person'] = $request->input('sale_person');
        }

        if($request->has('product')){
            $postArr['product'] = $request->input('product');
        }

        if($request->has('lead_id')){
            $postArr['lead_id'] = $request->input('lead_id');
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }
        
        if($request->has('status')){  
            $postArr['status'] = $request->input('status');
        }

        $customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');

        $lead_details = Lead::searchleadsreport($postArr);
        

        $lastlead = '';
        $all_modes = Lead_activity_mode::fetchmodes();
        return view('/salereport',compact('postArr','lead_details','customers','products','salesperson','all_modes'));
    }


    public function list_lead_by_status(Request $request)
    {
        //helper::pre($request->all(),0);
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['salePerson_id'] = $request->input('salePersonId');
        
        $status = $request->input('leadType');
        $salePersonId= $request->input('salePersonId');
        $dtime= $request->input('dtime');
         if ($dtime == 1) {
            $postArr['fromdate'] = date('d/m/Y', strtotime('-1 days'));
        } else if ($dtime == 2) {
            $postArr['fromdate'] = date('d/m/Y', strtotime('-7 days'));
        } else if ($dtime == 3) {
            $postArr['fromdate'] = date('d/m/Y', strtotime('-1 MONTH'));
        } else if ($dtime == 4) {
            $postArr['fromdate'] = date('d/m/Y', strtotime('-1 YEAR'));
        }
        if($salePersonId!=0){
            $postArr['sale_person'] =$salePersonId;
        }
        //$status = $status ;
        //$customers = customer::activecustomers();
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');
        $postArr['perPage']  = $this->items_per_page;

        if(Auth::user()->user_type=='SP')
        {
            $id = Lead::lastleadofSP();
            if(!empty($id))
            {              
                $lastlead = $id['id'];
            }
            else
            {
                $lastlead = '';
            }
        }
        else
        {
            $lastlead = '';
        } 

        $perPage  = $this->items_per_page;
        $lead_details = Lead::get_list_by_status($status,$salePersonId,$dtime,$perPage);
       // $lead_details = Lead::fetchleads($perPage);

        $all_modes = Lead_activity_mode::fetchmodes();
        return view('/lead_list_by_status',compact('postArr','lead_details','products','salesperson','perPage','lastlead','all_modes','status'));
        //echo $status; die();
    }

    public function searchlead_from_dashboard(Request $request)
    { 
        //helper::pre($request->all());exit;
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['perPage']  = $this->items_per_page;
        $status = $request->input('lead_status');
        $postArr['salePerson_id'] = $request->input('salePersonId');
        

        if($request->has('sale_person')){
            $postArr['sale_person'] = $request->input('sale_person');
        }

        if($request->has('product')){
            $postArr['product'] = $request->input('product');
        }

        if($request->has('lead_id')){
            $postArr['lead_id'] = $request->input('lead_id');
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }
        
        $products = Product::activeproduct();
        $salesperson = User::selectuser('SP');

        $lead_details = Lead::search_lead_by_leadstatus($postArr,$status);
        
        $lastlead = '';
        $all_modes = Lead_activity_mode::fetchmodes();
        return view('/lead_list_by_status',compact('postArr','lead_details','products','salesperson','lastlead','all_modes','status'));
    }


    public function loadleadbystatus(Request $request)
    {   
        //helper::pre($request->all(),1);
        $postArr['customer_name'] = '';
        $postArr['sale_person'] = '';
        $postArr['product'] = '';
        $postArr['lead_id'] = '';
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['status'] = '999999';
        $output = '';
        $last_id='';
        $salePersonId=0;
       if (Auth::user()->user_type == 'SP') {
           $salePersonId=Auth::user()->id;
       }
        $dtime='0000-00-00';
        if($request->has('last_id')){
            //$postArr['fromdate'] = $request->input('fromdate');
            $last_id = $request->input('last_id');
        }
        
        if(($request->has('fromdate'))&&($request->input('fromdate'))!=''){
            $postArr['fromdate'] = $request->input('fromdate');
            $dtime = $request->input('fromdate');
        }
        if($request->has('lead_status')){
            $status = $request->input('lead_status');
        }
        if($request->has('salePerson_id')){
            $salePersonId = $request->input('salePerson_id');
        }
       
        $status = $request->input('lead_status');

        $perPage  = $this->items_per_page;
        $posts = Lead::get_list_by_status($status,$salePersonId,$dtime,$perPage,$last_id);
        if (count($posts)>0) 
        {            
            foreach ($posts as $post) {
                
                  $output .='<tr id="'.$post['id'].'" class="note-row">
                  <td><a class="table-link" href="'.route('lead-details',['id' => Crypt::encrypt($post['id']) ]).'">L00'.$post['id'].'</a></td>
                  <td>'.$post['company_name'].'</td>';
                  
                  $output .='<td>'.$post['display_name'].'</td>';
                
                  $output .='<td>'.$post['totprod'].'</td>
                  <td>'.number_format($post['valuation'],2).'</td>';
                  if($post['lead_strength_id']!='0')
                  {
                  $output .='<td>'.$post['loan_type'].'</td>';
                  }
                  else if($post['lead_strength_id']=='0')
                  {
                  $output .='<td>New</td>';
                  }
                  
                  if($post['updated_at']!='')
                  {
                  $output .='<td>'.date('d/m/Y',strtotime($post['updated_at'])).'</td>';
                  }
                  else if($post['updated_at']=='')
                  {
                  $output .='<td></td>';
                  }
                  $output .='<td class="text-center viewgrp-dropdown dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">';
                  if($post['is_completed']=='0')
                  {
                   $output .= '<li><a href="'.route('edit-lead',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>';
                  }
                  $output .='<li><a href="'.route('lead-details',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-eye" aria-hidden="true"></i>View</a></li>';
                  if (Auth::user()->user_type == 'SP' && $post['is_completed']=='0')
                  {                 
                  $output.='<li><a href="javascript:void(0)" data-toggle="modal"  id="'.($post['id']).'" onClick="openModal('.$post['id'].')"><i class="fa fa-plus" aria-hidden="true"></i>Add Activity</a></li>';
                  }    
                  $output .='</ul>
                  </td>
                </tr>';           
            }
            echo $output;
        }
    }


    public function deletelead($id)
    {
        try 
        {
            $id =  decrypt($id);
        } 
        catch (DecryptException $e) 
        {
            return redirect('/list_lead');
        }
        $resp = Lead::delete_lead($id) ; 
        if($resp==1)
        {
        return redirect('/list_lead')->with('success_message', 'Lead deleted Successfully!');
        }
        else if($resp==0)
        {
        return redirect('/list_lead')->with('error_message', 'Error occured');
        }
    }

    public function select_sp_customers(Request $request)
    {
        $posts = customer::spcustomers($request->input('id'));
        if (count($posts)) 
        {  
            $output=array();
            foreach ($posts as $post) {                
                $result = array(
                    "label" => $post['company_name'],
                    "data" => $post['id']
                );
                array_push($output,$result);
            }
        }
        else{
            $output=array();
        }
        $output = json_encode($output);
        return $output;
    }
    
    
    public function submit_onboarding_info(Request $request, $id = '') {

        if ($request->has('_token')) {
            //helper::pre($request->all(),1); 
            $allRequest = $request->all();
            $white_lists = helper::getFormFields("onboardingform");
            $ignore_keys = array('_token');
            $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
            //helper::pre($post_data, 1);

            $rule = [
                'agency_name' => 'required',
                'reg_no' => 'required',
                'agency_client' => 'required',
                'compliance' => 'required',
                'initially' => 'required|numeric',
                'one_month' => 'required|numeric',
                'six_month' => 'required|numeric',
                'product' => 'required',
                'margin' => 'required|numeric',
                'credit' => 'required|numeric',
                'rebate' => 'required|numeric',
            ];
            $validator = Validator::make($post_data, $rule);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $sperson_id = Auth::user()->id;
            $newDate = str_replace('/', '-', $post_data['start_date']);
            $date = date('Y-m-d H:i:s', strtotime($newDate));
            $insert_array = array(
                'lead_id' => $post_data['id'],
                'agency_name' => $post_data['agency_name'],
                'company_registration_number' => $post_data['reg_no'],
                'agency_end_client' => $post_data['agency_client'],
                'business_sector' => $post_data['business_sector'],
                'new_business_contact' => $post_data['new_business'],
                'accounts_payroll_contact' => $post_data['payraoll'],
                'compliance_contracts' => $post_data['compliance'],
                'initially_proposed_volume' => $post_data['initially'],
                'after_one_month_volume' => $post_data['one_month'],
                'after_six_month_volume' => $post_data['six_month'],
                'proposed_products' => $post_data['product'],
                'approx_weekly_invoice' => $post_data['weekly_invoice'],
                'contractor_margin' => $post_data['margin'],
                'proposed_credit' => $post_data['credit'],
                'proposed_rebate' => $post_data['rebate'],
                'rebate_applicability_threshold' => $post_data['rebate_threshold'],
                'expected_start_date' => $date,
                'created_by' => $sperson_id,
            );
            // helper::pre($insert_array, 1);
            if ($post_data['id'] != '') {
                $insert = Onboarding_document::add_onboard_info($insert_array);
                //die();
                if ($insert['id'] != 'null' && !empty($insert)) {
                    $user = Auth::user()->toArray();
                    $all_OM = User::get_all_OM_onboardemail();
                    $mail = array();
                    foreach ($all_OM as $key => $value) {
                        array_push($mail, $value['email']);
                    }
                    //helper::pre($mail, 1);
                    $content = [
                        'subject' => "Onboarding information submitted successfully",
                        'user' => $user['display_name'],
                        'lead_id' => $post_data['id'],
                    ];
                    $mailstatus = Mail::to($mail)->send(new onboardingMail($content));
                    return redirect('list_lead')->with('success_message', 'Lead completed Successfully!');
                }
            } else {
                return redirect('list_lead');
            }
        } else {
            $id = Crypt::decrypt($id);
            if ($id == '') {
                return redirect('list_lead');
            }
            $formfield = helper::getFormFields("onboardingform");
            //helper::pre($formfield,1); 
            $encrypted = helper::encryptForm($formfield);
            $regno = Onboarding_document::lead_customar_regno($id);
            $p_data = array(
                'id' => $id,
                'agency_name' => '',
                'reg_no' => $regno,
                'agency_client' => '',
                'business_sector' => '',
                'new_business' => '',
                'payraoll' => '',
                'compliance' => '',
                'initially' => '',
                'one_month' => '',
                'six_month' => '',
                'product' => '',
                'weekly_invoice' => '',
                'margin' => '',
                'credit' => '',
                'rebate' => '',
                'rebate_threshold' => '',
                'start_date' => '',
                'success_message' => 'Lead successfully converted to a client.  Well Done!  Please now complete the onboarding form which will be emailed to the operations team.',
            );
            return view('onboarding_info', compact('encrypted', 'p_data'));
        }
    }

    public function test1()
    {
         return view('umbrella_calculator');
        
    }
}
