<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Crypt;
use Validator;
use helper;
use App\model\Lead_activity_mode;
use App\model\Lead_activity;
use App\model\Lead_strength;
use App\model\Lead;
use App\model\Product;
use App\User;
use App\model\Lead_product;
use Illuminate\Contracts\Encryption\DecryptException;

use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class ActivityController extends Controller
{
    public function __construct() {
        DB::enableQueryLog();
//       $this->middleware('MA');
        $this->items_per_page = Config::get('formArray.items_per_page');
    }

 public function activityManagerbyUser(Request $request)
    { 
        $postArr['user'] =99999;
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['activity_type'] = '';
        $postArr['activity_modes'] = '99999';
        $perPage  =  $this->items_per_page;
     
        if($request->has('user')){
            
            if($request->input('user')!=0)
            {
                $postArr['user'] = $request->input('user');
            }
            else
            {
                $postArr['user'] = 99999;
            }
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }

        if($request->has('activity_type')&&($request->input('activity_type')!=0)){
            $postArr['activity_type'] = $request->input('activity_type');
        }
        if($request->has('activity_modes')&&($request->input('activity_modes')!=99999)){
            $postArr['activity_modes'] = $request->input('activity_modes');
        }
     // helper::pre($postArr,0);
        $users= User::activeUser();
        $allLead = Lead::fetchLeadsbyUser($perPage,$postArr);
        //helper::pre($users,1);
       // $products = Product::activeProduct();
        
        $activity_modes = Lead_strength::strengthlist();
        //helper::pre($activity_modes,1);
       // $postArr['perPage']  = '6';

        return view('/activity_user_manage',compact('postArr','allLead','activity_modes','perPage','users'));
    }

    
    public function loadAudiTrailByUser(Request $request) {
        $output = '';
        $last_id= $request->input('last_id');
        $postArr['user'] =99999;
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['activity_type'] = '';
        $postArr['activity_modes'] = '99999';
        $postArr['last_id'] = $last_id;
        $perPage  =  $this->items_per_page;

     if($request->has('user')){
            
            if($request->input('user')!=0)
            {
                $postArr['user'] = $request->input('user');
            }
            else
            {
                $postArr['user'] = 99999;
            }
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }

        if($request->has('activity_type')&&($request->input('activity_type')!=0)){
            $postArr['activity_type'] = $request->input('activity_type');
        }
        if($request->has('activity_modes')&&($request->input('activity_modes')!=99999)){
            $postArr['activity_modes'] = $request->input('activity_modes');
        }

        $allLead = Lead::fetchLeadsbyUser($perPage,$postArr);
        //helper::pre($allLead,1);
        if (!empty($allLead)) 
        {            
            foreach ($allLead as $post) {
                if ($post['valuation'] != '') {
                    $num = str_replace(',', '', number_format($post['valuation'], 2));
                }
                if ($post['valuation'] == '') {
                    $num = '0.00';
                }
                $output .='<tr id="'.$post['lead_id'].'" class="note-row">
                  <td>L00'.$post['lead_id'].'</td>
                  <td>'.$post['display_name'].'</td>
                  <td>'.$post['company_name'].'</td>
                  <td>'.$num.'</td>';
                  if ($post['status'] == '') {
                    $output .= '<td>New</td>';
                } else {
                     $output .= '<td>'.$post['status'].'</td>';
                }  
                  if ($post['last_activity_type'] == '1') {
                    $output .= '<td>Automatic</td>';
                } else {
                    $output .= '<td>Manual</td>';
                }
                if ($post['last_activity_time'] != '') {
                    $output .= '<td>'.date('d/m/Y h:i A',strtotime($post['last_activity_time'])).'</td>';
                } 
                else{
                    $output .= '<td></td>';
                }
            
                $output .='<td class="text-center viewgrp-dropdown">';
                  $output .='<a class="table-link" href="'.url('lead_details/'.encrypt($post['lead_id'])).'"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                   
                  </td>
                </tr>';   
               
            }
           
            echo $output;
        }
    }
    
    
 public function activityManagerbyProduct(Request $request)
    { 
        $postArr['product'] =99999;
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['activity_type'] = '';
        $postArr['activity_modes'] = '99999';
        $postArr['last_id'] = '';
        $perPage  =  $this->items_per_page;
     
        if($request->has('product')){
            
            if($request->input('product')!=0)
            {
                $postArr['product'] = $request->input('product');
            }
            else
            {
                $postArr['product'] = 99999;
            }
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }

        if($request->has('activity_type')&&($request->input('activity_type')!=0)){
            $postArr['activity_type'] = $request->input('activity_type');
        }
        if($request->has('activity_modes')&&($request->input('activity_modes')!=99999)){
            $postArr['activity_modes'] = $request->input('activity_modes');
        }
      //helper::pre($postArr,0);
        //$users= User::activeUser();
        $allLead = Lead::fetchLeadsbyProduct($perPage,$postArr);
        $products = Product::activeProduct();
       // helper::pre($products,1);
        $activity_modes = Lead_strength::strengthlist();
        //helper::pre($activity_modes,1);
        //$postArr['perPage']  = '6';

        return view('/activity_product_manage',compact('postArr','allLead','activity_modes','perPage','products'));
    }
    
    
    public function loadAudiTrailByProduct(Request $request) {
        $output = '';
        $last_id= $request->input('last_id');
        $postArr['product'] =99999;
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['activity_type'] = '';
        $postArr['activity_modes'] = '99999';
        $postArr['last_id'] = $last_id;
        $perPage  =  $this->items_per_page;

      if($request->has('product')){
            
            if($request->input('product')!=0)
            {
                $postArr['product'] = $request->input('product');
            }
            else
            {
                $postArr['product'] = 99999;
            }
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }

        if($request->has('activity_type')&&($request->input('activity_type')!=0)){
            $postArr['activity_type'] = $request->input('activity_type');
        }
        if($request->has('activity_modes')&&($request->input('activity_modes')!=99999)){
            $postArr['activity_modes'] = $request->input('activity_modes');
        }

        $allLead = Lead::fetchLeadsbyProduct($perPage,$postArr);
        //helper::pre($allLead,1);
        if (!empty($allLead)) 
        {            
            foreach ($allLead as $post) {
                if ($post['valuation'] != '') {
                    $num = str_replace(',', '', number_format($post['valuation'], 2));
                }
                if ($post['valuation'] == '') {
                    $num = '0.00';
                }
               $output .='<tr id="'.$post['lead_id'].'" class="note-row">
                  <td>L00'.$post['lead_id'].'</td>
                  <td>'.$post['display_name'].'</td>
                  <td>'.$post['company_name'].'</td>
                  <td>'.$num.'</td>';
                 if ($post['status'] == '') {
                    $output .= '<td>New</td>';
                } else {
                     $output .= '<td>'.$post['status'].'</td>';
                }  

                  if ($post['last_activity_type'] == '1') {
                    $output .= '<td>Automatic</td>';
                } else {
                    $output .= '<td>Manual</td>';
                }
                if ($post['last_activity_time'] != '') {
                    $output .= '<td>'.date('d/m/Y h:i A',strtotime($post['last_activity_time'])).'</td>';
                } 
                else{
                    $output .= '<td></td>';
                }
            
                $output .='<td class="text-center viewgrp-dropdown">';
                  $output .='<a class="table-link" href="'.url('lead_details/'.encrypt($post['lead_id'])).'"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                   
                  </td>
                </tr>';   
               
            }
           
            echo $output;
        }
    }
   
  public function activityManagerByLead(Request $request)
    { 
        $postArr['lead'] =99999;
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['activity_type'] = '';
        $postArr['activity_modes'] = '99999';
        $perPage  =  $this->items_per_page;
     
        if($request->has('lead')){
            
            if($request->input('lead')!=0)
            {
                $postArr['lead'] = $request->input('lead');
            }
            else
            {
                $postArr['lead'] = 99999;
            }
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }

        if($request->has('activity_type')&&($request->input('activity_type')!=0)){
            $postArr['activity_type'] = $request->input('activity_type');
        }
        if($request->has('activity_modes')&&($request->input('activity_modes')!=99999)){
            $postArr['activity_modes'] = $request->input('activity_modes');
        }
     // helper::pre($postArr,0);
        $active_lead= Lead::activeLead();
        $allLead = Lead::fetchLeadsbyLead($perPage,$postArr);
       // helper::pre($allLead,1);
       // $products = Product::activeProduct();
        
        $activity_modes = Lead_strength::strengthlist();
        //helper::pre($activity_modes,1);
       // $postArr['perPage']  = '6';

        return view('/activity_lead_manage',compact('postArr','allLead','activity_modes','perPage','active_lead'));
    }
  
   public function loadAudiTrailByLead(Request $request) {
        $output = '';
        $last_id= $request->input('last_id');
        $postArr['lead'] =99999;
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        $postArr['activity_type'] = '';
        $postArr['activity_modes'] = '99999';
        $postArr['last_id'] = $last_id;
        $perPage  =  $this->items_per_page;

      if($request->has('lead')){
            
            if($request->input('lead')!=0)
            {
                $postArr['lead'] = $request->input('lead');
            }
            else
            {
                $postArr['lead'] = 99999;
            }
        }

        if($request->has('fromdate')){
            $postArr['fromdate'] = $request->input('fromdate');
        }

        if($request->has('todate')){
            $postArr['todate'] = $request->input('todate');
        }

        if($request->has('activity_type')&&($request->input('activity_type')!=0)){
            $postArr['activity_type'] = $request->input('activity_type');
        }
        if($request->has('activity_modes')&&($request->input('activity_modes')!=99999)){
            $postArr['activity_modes'] = $request->input('activity_modes');
        }

        $allLead = Lead::fetchLeadsbyLead($perPage,$postArr);
        //helper::pre($allLead,1);
        if (!empty($allLead)) 
        {            
            foreach ($allLead as $post) {
                if ($post['valuation'] != '') {
                    $num = str_replace(',', '', number_format($post['valuation'], 2));
                }
                if ($post['valuation'] == '') {
                    $num = '0.00';
                }
               $output .='<tr id="'.$post['lead_id'].'" class="note-row">
                  <td>L00'.$post['lead_id'].'</td>
                  <td>'.$post['display_name'].'</td>
                  <td>'.$post['company_name'].'</td>
                  <td>'.$num.'</td>';
                  if ($post['status'] == '') {
                    $output .= '<td>New</td>';
                } else {
                     $output .= '<td>'.$post['status'].'</td>';
                }  

                  if ($post['last_activity_type'] == '1') {
                    $output .= '<td>Automatic</td>';
                } else {
                    $output .= '<td>Manual</td>';
                }
                if ($post['last_activity_time'] != '') {
                    $output .= '<td>'.date('d/m/Y h:i A',strtotime($post['last_activity_time'])).'</td>';
                } 
                else{
                    $output .= '<td></td>';
                }
            
                $output .='<td class="text-center viewgrp-dropdown">';
                  $output .='<a class="table-link" href="'.url('lead_details/'.encrypt($post['lead_id'])).'"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                   
                  </td>
                </tr>';   
               
            }
           
            echo $output;
        }
    }
     

}
