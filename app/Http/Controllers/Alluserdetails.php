<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Validator;
use helper;
use Hash;
use Auth;
use App\User;
use App\model\User_email;
use App\model\User_phone;
use App\model\Lead;
use App\model\Lead_product;
use App\model\Lead_activity;
use App\model\Lead_activity_mode;
use App\model\Lead_strength;

use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class Alluserdetails extends Controller
{
    public function __construct() {
        DB::enableQueryLog();
       // $this->items_per_page = '4';
        $this->items_per_page = Config::get('formArray.items_per_page');
    }

    public function userdetails($id)
    { 
        try 
        {
            $id =  decrypt($id);
        } 
        catch (DecryptException $e) 
        {
           return redirect('/user_list');
        }
        
        $user = User::eachuser($id);
        
        $user_email = User_email::primaryemail($id);
        $user_phone = User_Phone::primaryphone($id);

        $postArr['leadno'] = $postArr['customername'] = $postArr['valuation'] = $postArr['status'] = $postArr['strength'] = '';
        
        //helper::pre($user_phone);exit;
        $data_array['id'] = $id;
        $data_array['email'] = $user_email['user_email'];
        if(!empty($user_phone))
        {
            $data_array['phone'] = $user_phone['user_phone'];
        }
        else{
            $data_array['phone'] = '';
        }
        $data_array['display_name'] = $user['display_name'];
        if($user['user_type']=='IT')
        {
            $data_array['user_type'] = "IT Manager";
        }
        if($user['user_type']=='MA')
        {
            $data_array['user_type'] = "Management";
        }
        if($user['user_type']=='LM')
        {
            $data_array['user_type'] = "Lead Manager";
        }
        if($user['user_type']=='SP')
        {
            $data_array['user_type'] = "Sales Person";
        }
        if($user['user_type']=='SM')
        {
            $data_array['user_type'] = "Senior Management";
        }
        if($user['user_type']=='OM')
        {
            $data_array['user_type'] = "Operations Management";
        }

        $all_activity = Lead_activity::fetchallactivity($id);
        $lead_details = array();
        if(!empty($all_activity))
        {
            foreach($all_activity as $key => $value)
            {
                $lead_details[] = Lead::fetchleadsbyid($all_activity[$key]['lead_id']);
            }
        }
        
        $strengths = Lead_strength::strengthlist();

        return view('/manageuser', compact('data_array','all_activity','lead_details','strengths','postArr'));
    }    

    public function searchuseractivity(Request $request)
    { 
        $id  = Crypt::decrypt($request->input('id'));
        $user = User::eachuser($id);
        
        $user_email = User_email::primaryemail($id);
        $user_phone = User_Phone::primaryphone($id);

        
        $data_array['id'] = $id;
        $data_array['email'] = $user_email['user_email'];
        if(!empty($user_phone))
        {
            $data_array['phone'] = $user_phone['user_phone'];
        }
        else{
            $data_array['phone'] = '';
        }
        $data_array['display_name'] = $user['display_name'];
        if($user['user_type']=='IT')
        {
            $data_array['user_type'] = "IT Manager";
        }
        if($user['user_type']=='MA')
        {
            $data_array['user_type'] = "Management";
        }
        if($user['user_type']=='LM')
        {
            $data_array['user_type'] = "Lead Manager";
        }
        if($user['user_type']=='SP')
        {
            $data_array['user_type'] = "Sales Person";
        }

        $postArr['leadno'] = $postArr['customername'] = $postArr['valuation'] = $postArr['status'] = $postArr['strength'] = '';

                
        if($request->has('leadno')){
            $postArr['leadno'] = $request->input('leadno');
        }

        if($request->has('customername')){
            $postArr['customername'] = $request->input('customername');
        }

        if($request->has('valuation')){
            $postArr['valuation'] = $request->input('valuation');
        }

        if($request->has('status')){
            $postArr['status'] = $request->input('status');
        }

        if($request->has('strength')){
            $postArr['strength'] = $request->input('strength');
        }


        //helper::pre($postArr);exit;

        $all_activity = Lead_activity::searchallactivity($id,$postArr);
        $lead_details = array();
        if(!empty($all_activity))
        {
            foreach($all_activity as $key => $value)
            {
                $lead_detailss = Lead::searchleadsbyid($all_activity[$key]['lead_id'],$postArr);
                if(!empty($lead_detailss))
                {                    
                    if($postArr['valuation']==''){
                        $lead_details[] = $lead_detailss;
                    }
                    elseif($postArr['valuation']!='')
                    {
                        if($lead_detailss['valuation']==$postArr['valuation'])
                        {
                            $lead_details[] = $lead_detailss;
                        }
                        else{
                            unset($all_activity[$key]);
                        }
                    }
                }
                else
                {
                    unset($all_activity[$key]);
                }
            }
        }
        $all_activity = array_values($all_activity);
        //helper::pre($lead_details);
        //helper::pre(array_values($all_activity));exit;
        $strengths = Lead_strength::strengthlist();

        return view('/manageuser', compact('data_array','all_activity','lead_details','strengths','postArr'));
    }
}
