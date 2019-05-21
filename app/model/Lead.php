<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
use App\model\Lead_product;
use App\model\Lead_activity;
use App\model\Lead_supporting_document;
use helper;
use Carbon\Carbon;

class Lead extends Model
{
  	public $timestamps = false;
    protected $guarded = ['id'];

    public static function insertlead($data)
    {	
        $results = Lead::firstOrCreate($data)->toArray();
        return $results['id'];
    }

    public static function editlead($data)
    {  
        $lead_detail = Lead::find($data['id']);
        $lead_detail->customer_contact_person_id = $data['customer_contact_person_id'];
        if (Auth::user()->user_type != 'SP')
        {
            $lead_detail->sales_person_id = $data['sales_person_id'];
        }
        $lead_detail->is_active = $data['is_active'];
        $lead_detail->lead_source = $data['lead_source'];
        $lead_detail->additional_info = $data['additional_info'];
        $lead_detail->lead_strength_id = $data['lead_strength_id'];
        $lead_detail->updated_at = $data['updated_at'];
        $lead_detail->updated_by = $data['updated_by'];
        $lead_detail->update();
    }

    public static function getlead($id)
    { 
        $results = Lead::find($id)->toArray();
        return $results;
    }

    public static function fetchleads($perPage)
    {
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','c.registration_number','cc.display_name','mcs.lead_started_on','mcs.is_executive_for_life','mcs.is_lead_on_hold')
       		->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
       		->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
       		->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
       		->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id')
                ->leftjoin('map_customer_salespersons as mcs', 'mcs.customer_id', '=', 'c.id');
                
       	
        if(Auth::user()->user_type=='SP')
        {
            $all_lead->where('sales_person_id','=',Auth::id())->where('leads.is_active','=','1');
        }

        $all_lead = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->limit($perPage)->get();
        
        if ($all_lead->count()) {
            $all_lead = $all_lead->toArray();            
        } else {
            $all_lead = array();
        }

        return $all_lead;
    }



    public static function lastleadofSP()
    {
        $lead_detail = Lead::select('id')->where('sales_person_id','=',Auth::id())->where('leads.is_active','=','1')->orderBy('id','ASC')->limit('1')->get()->first();
        if(!empty($lead_detail))
        {
            if($lead_detail->count()){
                $id = $lead_detail->toArray();
            }
            else
            {
                $id = array();
            }
        }
        else{
            $id = array();
        }
        return $id;
    }

    public static function fetchleadsbyid($id)
    {
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','cc.display_name')
            ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
            ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
            ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
            ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
        
        $all_lead = $all_lead->where('leads.id','=',$id);

        $all_lead = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get();
        
        if ($all_lead->count()) {
            $all_lead = $all_lead->first()->toArray();            
        } else {
            $all_lead = array();
        }

        return $all_lead;
    }

    public static function searchleadsbyid($id,$data)
    {
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','cc.display_name')
            ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
            ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
            ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
            ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
        
        if(isset($data['customername']) && $data['customername']!='')
        {
            $all_lead->where('c.company_name', 'LIKE', '%' . $data['customername'] . '%');
        }

        if(isset($data['strength']) && $data['strength']!='0')
        {
            $all_lead->where('leads.lead_strength_id', '=', $data['strength']);
        }

        

        $all_lead = $all_lead->where('leads.id','=',$id);
        //echo $id;
        //echo $all_lead = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->toSql();exit;
        $all_lead = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get();
        
        if ($all_lead->count()) {
            $all_lead = $all_lead->first()->toArray();   
            //print_r($all_lead);         
        } else {
            $all_lead = array();
        }

        return $all_lead;
    }

    public static function loadAjaxlead($perPage,$id)
    {	
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','c.registration_number','cc.display_name','mcs.lead_started_on','mcs.is_executive_for_life','mcs.is_lead_on_hold')
       		->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
       		->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
       		->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
       		->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id')
                ->leftjoin('map_customer_salespersons as mcs', 'mcs.customer_id', '=', 'c.id'); 

        if(isset($id) && $id!='')
        {           
            $all_lead->where('leads.id', '<', $id);
        }
        if(Auth::user()->user_type=='SP')
        {
            $all_lead->where('sales_person_id','=',Auth::id())->where('leads.is_active','=','1');
        }
        /*echo $ajaxlead = $all_lead->groupBy('leads.id')
            ->orderBy('leads.id', 'DESC')
            ->limit($perPage)
            ->toSql();*/
       	$ajaxlead = $all_lead->groupBy('leads.id')
            ->orderBy('leads.id', 'DESC')
            ->limit($perPage)
       		->get();
        //print_r($ajaxlead);
       	return $ajaxlead;
    }

    public static function searchleads($query)
    {
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','c.registration_number','cc.display_name','mcs.lead_started_on','mcs.is_executive_for_life','mcs.is_lead_on_hold')
       		->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
       		->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
       		->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
       		//->leftjoin('lead_activities as la','la.lead_id', '=', 'leads.id')
       		->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id')
                ->leftjoin('map_customer_salespersons as mcs', 'mcs.customer_id', '=', 'c.id');

        if(Auth::user()->user_type=='SP')
        {
            $all_lead->where('sales_person_id','=',Auth::id())->where('leads.is_active','=','1');
        }

        if(isset($query['customer_name']) && $query['customer_name']!=''){
            $all_lead->where('c.company_name', 'LIKE', '%' . $query['customer_name'] . '%');
        }

        if(isset($query['registration_number']) && $query['registration_number']!=''){
            $all_lead->where('c.registration_number', 'LIKE', '%' . $query['registration_number'] . '%');
        }
        
        if(isset($query['sale_person']) && $query['sale_person']!=''){
            $all_lead->where('cc.display_name', 'LIKE', '%' . $query['sale_person'] . '%');
        }

        if(isset($query['product']) && $query['product']!='0'){
        	$leadids = DB::table('lead_products')->select('lead_id')->where('prod_id', '=',$query['product'])->get();
        	$newleads  = array();
        	if ($leadids->count()) 
        	{
	            $leadids = $leadids->toArray();
	            
	            foreach($leadids as $leadval)
	            {
	            	array_push($newleads, $leadval->lead_id);
	            }
	        }
	        else
	        {
	        	$leadids = array();
	        }
            $all_lead->whereIn('leads.id',$newleads);
        }

        if(isset($query['lead_id']) && $query['lead_id']!=''){

        	$lead = preg_replace('/[^0-9]/', '', $query['lead_id']);
            $all_lead->where('leads.id', 'LIKE', '%' . $lead . '%');
        }


        if(isset($query['fromdate']) && $query['fromdate']!='' && !isset($query['todate'])){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $leadids = DB::table('lead_activities')->select('lead_id')->where('activity_time', '>=',$fromdate)->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        else if(isset($query['todate']) && $query['todate']!='' && !isset($query['fromdate'])){           
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            $leadids = DB::table('lead_activities')->select('lead_id')->where('activity_time', '<=',$todate)->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        else if(isset($query['todate']) && $query['todate']!='' && isset($query['fromdate']) && $query['fromdate']!=''){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            //$all_lead->whereBetween('la.activity_time', array($fromdate, $todate));
            $leadids = DB::table('lead_activities')->select('lead_id')->whereBetween('activity_time', array($fromdate, $todate))->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        if(isset($query['status']) && $query['status']!=''){          	
            $all_lead->where('leads.is_active', '=', $query['status']);
        } 
        //echo $query['status'];
        $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get();  
        //echo $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->toSql();exit;

        if ($result->count()) {
            $result = $result->toArray();
        }
        //print_r($result);exit;
        return $result;
    }
    
     
    public static function fetchLeadsbyUser($perPage, $postArray) {

//        $subquery= DB::select('SELECT * FROM lead_activities where id IN (select max(id) as id from lead_activities group by lead_id DESC) ORDER BY lead_id DESC');     
//        echo('<pre>');
//        print_r($subquery);
//        echo('</pre>');
//       die();

        $subquery = '(SELECT * FROM lead_activities where id IN (select max(id) as id from lead_activities group by lead_id DESC) ORDER BY lead_id DESC) 
                AS temp_activity';

        $lead_list = Lead::select('leads.id as lead_id', 'leads.sales_person_id', 'u.display_name', 'c.company_name', 'ls.loan_type as status', DB::raw('sum(lp.margin_value * lp.quantity) as valuation'), 'temp_activity.id  as last_activity_id', 'temp_activity.activity_type as last_activity_type', 'temp_activity.activity_time as last_activity_time', 'temp_activity.activity_note as last_activity_note')
                ->leftjoin('users as u', 'u.id', '=', 'leads.sales_person_id')
                ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
                ->leftjoin('lead_strengths as ls', 'ls.id', '=', 'leads.lead_strength_id')
                ->leftjoin('lead_products as lp', 'lp.lead_id', '=', 'leads.id')
                ->leftJoin(DB::raw($subquery), 'temp_activity.lead_id', '=', 'leads.id');
        if (isset($postArray['user']) && $postArray['user']!= 99999) {
           
           $lead_list = $lead_list->where('u.id', '=', $postArray['user']);
        }

        if (isset($postArray['fromdate']) && $postArray['fromdate']!= '') {
            $fromDate = str_replace('/', '-',$postArray['fromdate']);
            $fromDate = date('Y-m-d H:i:s',strtotime($fromDate));
            $lead_list = $lead_list->where('temp_activity.activity_time', '>', $fromDate);
        }
        
         if (isset($postArray['todate']) && $postArray['todate']!= '') {

            $toDate = str_replace('/', '-',$postArray['todate']);
            $toDate = date('Y-m-d H:i:s',strtotime($toDate. ' +1 day'));
            $lead_list = $lead_list->where('temp_activity.activity_time', '<', $toDate);
        }
        if (isset($postArray['activity_type']) && $postArray['activity_type']!='') {
           
           $lead_list = $lead_list->where('temp_activity.activity_type', '=', $postArray['activity_type']);
        }
        
        if (isset($postArray['activity_modes']) && $postArray['activity_modes']!=99999) {
           if($postArray['activity_modes']==0)
           {
               $lead_list = $lead_list->where('leads.lead_strength_id', '=', $postArray['activity_modes']);
           }
           else{
               $lead_list = $lead_list->where('ls.id', '=', $postArray['activity_modes']);
           }
        }
        
        if (isset($postArray['last_id']) && $postArray['last_id']!='') {
           
           $lead_list = $lead_list->where('leads.id', '<', $postArray['last_id']);
        }
        
//        $lead_list = $lead_list->where('u.is_active', '=', 1);
        $lead_list = $lead_list->where('leads.is_active', '=', 1)
                ->groupBy('leads.id')
                ->orderBy('leads.id', 'DESC')
                ->limit($perPage)
                ->get()
                ->toArray();


        return $lead_list;
    }
    
    
  public static function fetchLeadsbyProduct($perPage, $postArray) {
      if (isset($postArray['product']) && $postArray['product'] != 99999) {
          $new_array = array();
            $product_id = $postArray['product'];
           $product_list  = Lead_product::select('lead_products.lead_id')
                    ->leftjoin('products as p', 'p.id', '=', 'lead_products.prod_id');
           
            $product_list=$product_list->where('p.id', '=', $product_id)
                    ->orderBy('lead_products.id', 'DESC')
                    ->get()
                    ->toArray();
            
           foreach($product_list as $product)
	            {
	            	array_push($new_array, $product['lead_id']);
	            }

        }
      

        $subquery = '(SELECT * FROM lead_activities where id IN (select max(id) as id from lead_activities group by lead_id DESC) ORDER BY lead_id DESC) 
                AS temp_activity';

        $lead_list = Lead::select('leads.id as lead_id', 'leads.sales_person_id', 'u.display_name', 'c.company_name', 'ls.loan_type as status', DB::raw('sum(lp.margin_value * lp.quantity) as valuation'), 'temp_activity.id  as last_activity_id', 'temp_activity.activity_type as last_activity_type', 'temp_activity.activity_time as last_activity_time', 'temp_activity.activity_note as last_activity_note')
                ->leftjoin('users as u', 'u.id', '=', 'leads.sales_person_id')
                ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
                ->leftjoin('lead_strengths as ls', 'ls.id', '=', 'leads.lead_strength_id')
                ->leftjoin('lead_products as lp', 'lp.lead_id', '=', 'leads.id')
                ->leftJoin(DB::raw($subquery), 'temp_activity.lead_id', '=', 'leads.id');
//                ->leftjoin('products as p', 'p.id', '=', 'lp.prod_id');
       
        if (isset($postArray['fromdate']) && $postArray['fromdate']!= '') {
            $fromDate = str_replace('/', '-',$postArray['fromdate']);
            $fromDate = date('Y-m-d H:i:s',strtotime($fromDate));
            $lead_list = $lead_list->where('temp_activity.activity_time', '>', $fromDate);
        }
        
         if (isset($postArray['todate']) && $postArray['todate']!= '') {
            $toDate = str_replace('/', '-',$postArray['todate']);
            $toDate = date('Y-m-d H:i:s',strtotime($toDate. ' +1 day'));
            $lead_list = $lead_list->where('temp_activity.activity_time', '<', $toDate);
        }
        if (isset($postArray['activity_type']) && $postArray['activity_type']!='') {
           
           $lead_list = $lead_list->where('temp_activity.activity_type', '=', $postArray['activity_type']);
        }
        
        if (isset($postArray['activity_modes']) && $postArray['activity_modes']!=99999) {
           if($postArray['activity_modes']==0)
           {
               $lead_list = $lead_list->where('leads.lead_strength_id', '=', $postArray['activity_modes']);
           }
           else{
               $lead_list = $lead_list->where('ls.id', '=', $postArray['activity_modes']);
           }
        }
        
        if (isset($postArray['last_id']) && $postArray['last_id']!='') {
           
           $lead_list = $lead_list->where('leads.id', '<', $postArray['last_id']);
        }
        
//        $lead_list = $lead_list->where('u.is_active', '=', 1);
        $lead_list = $lead_list->where('leads.is_active', '=', 1);
             if (isset($postArray['product']) && $postArray['product'] != 99999) {
                  $lead_list->whereIn('leads.id',$new_array);
             }    
              $lead_list = $lead_list->groupBy('leads.id')
                ->orderBy('leads.id', 'DESC')
                ->limit($perPage)
                ->get()
                ->toArray();
        
//        if (isset($postArray['product']) && $postArray['product'] != 99999) {
//
//            $product_id = $postArray['product'];
//           $product_list  = Lead_product::select('lead_products.lead_id')
//                    ->leftjoin('products as p', 'p.id', '=', 'lead_products.prod_id');
//           
//            if (isset($postArray['last_id']) && $postArray['last_id'] != '') {
//                $lead_list = $product_list->where('lead_products.id', '<', $postArray['last_id']);
//            }
//            $product_list=$product_list->where('p.id', '=', $product_id)
//                    ->limit($perPage)
//                    ->orderBy('lead_products.id', 'DESC')
//                    ->get()
//                    ->toArray();
//            $new_array = array();
//            foreach ($lead_list as $key => $value) {
//                foreach ($product_list as $key1 => $value1) {
//                    //$lead_id=$value['lead_id'];
//                    if ($value['lead_id'] == $value1['lead_id']) {
//                        $new_array[] = $value;
//                        //echo($value1['lead_id']);
//                    }
//                }
//            }
//            $lead_list = $new_array;
//        }
        
//        echo("<pre>");
//        print_r($new_array);
//            echo("</pre>");
        return $lead_list;
    }
   
    
    
    


    public static function fetchSPleads($perPage,$id)
    {
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','cc.display_name')
            ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
            ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
            ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
            ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');

        $all_lead = $all_lead->where('sales_person_id','=',$id)->groupBy('leads.id')->orderBy('leads.id', 'DESC')->limit($perPage)->get();
        
        if ($all_lead->count()) {
            $all_lead = $all_lead->toArray();            
        } else {
            $all_lead = array();
        }

        return $all_lead;
    }


    public static function loadAjaxSPlead($perPage,$id,$salperson)
    {   
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','cc.display_name')
            ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
            ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
            ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
            ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
        

        
        $ajaxlead = $all_lead->where('leads.id', '<', $id)->where('sales_person_id','=',$salperson)->groupBy('leads.id')
            ->orderBy('leads.id', 'DESC')
            ->limit($perPage)
            ->get();
        //print_r($ajaxlead);
        return $ajaxlead;
    }



    public static function searchspleads($query,$id)
    {
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','cc.display_name','la.activity_time')
            ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
            ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
            ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
            ->leftjoin('lead_activities as la','la.lead_id', '=', 'leads.id')
            ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id')
            ->where('sales_person_id','=',$id);


        if(isset($query['customer_name']) && $query['customer_name']!=''){
            $all_lead->where('c.company_name', 'LIKE', '%' . $query['customer_name'] . '%');
        }

        if(isset($query['sale_person']) && $query['sale_person']!=''){
            $all_lead->where('cc.display_name', 'LIKE', '%' . $query['sale_person'] . '%');
        }

        if(isset($query['product']) && $query['product']!='0'){
            $leadids = DB::table('lead_products')->select('lead_id')->where('prod_id', '=',$query['product'])->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $leadval)
                {
                    array_push($newleads, $leadval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        if(isset($query['lead_id']) && $query['lead_id']!=''){

            $lead = preg_replace('/[^0-9]/', '', $query['lead_id']);
            $all_lead->where('leads.id', 'LIKE', '%' . $lead . '%');
        }

        if(isset($query['fromdate']) && $query['fromdate']!='' && !isset($query['todate'])){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $all_lead->where('la.activity_time', '>=', $fromdate);
        }

        else if(isset($query['todate']) && $query['todate']!='' && !isset($query['fromdate'])){
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            $all_lead->where('la.activity_time', '<=', $todate);
        }

        else if(isset($query['todate']) && $query['todate']!='' && isset($query['fromdate']) && $query['fromdate']!=''){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            $all_lead->whereBetween('la.activity_time', array($fromdate, $todate));
        }
        
        if(isset($query['status']) && $query['status']!=''){            
            $all_lead->where('leads.is_active', '=', $query['status']);
        } 
        //echo $query['status'];
        $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get();  
        //echo $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->toSql();exit;

        if ($result->count()) {
            $result = $result->toArray();
        }
        //print_r($result);exit;
        return $result;
    }

    public static function leadReport()
    {
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','cc.display_name')
            ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
            ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
            ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
            ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
        
        
        if(Auth::user()->user_type=='SP')
        {
            $all_lead->where('sales_person_id','=',Auth::id());
        }
        //echo $all_lead = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->toSql();exit;
        $all_lead = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get();
        
        if ($all_lead->count()) {
            $all_lead = $all_lead->toArray();            
        } else {
            $all_lead = array();
        }

        return $all_lead;
    }   

    public static function searchleadsreport($query)
    {
        //print_r($query);exit;
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','cc.display_name')
            ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
            ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
            ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
            //->leftjoin('lead_activities as la','la.lead_id', '=', 'leads.id')
            ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');


        if(Auth::user()->user_type=='SP')
        {
            $all_lead->where('sales_person_id','=',Auth::id());
        }

        if(isset($query['customer_name']) && $query['customer_name']!=''){
            $all_lead->where('c.company_name', 'LIKE', '%' . $query['customer_name'] . '%');
        }

        if(isset($query['sale_person']) && $query['sale_person']!='0'){
            $all_lead->where('sales_person_id','=', $query['sale_person']);
        }

        if(isset($query['product']) && $query['product']!='0'){
            $leadids = DB::table('lead_products')->select('lead_id')->where('prod_id', '=',$query['product'])->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $leadval)
                {
                    array_push($newleads, $leadval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        if(isset($query['lead_id']) && $query['lead_id']!=''){

            $lead = preg_replace('/[^0-9]/', '', $query['lead_id']);
            $all_lead->where('leads.id', 'LIKE', '%' . $lead . '%');
        }

        if(isset($query['fromdate']) && $query['fromdate']!='' && !isset($query['todate'])){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $leadids = DB::table('lead_activities')->select('lead_id')->where('activity_time', '>=',$fromdate)->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        else if(isset($query['todate']) && $query['todate']!='' && !isset($query['fromdate'])){
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            $leadids = DB::table('lead_activities')->select('lead_id')->where('activity_time', '<=',$todate)->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        else if(isset($query['todate']) && $query['todate']!='' && isset($query['fromdate']) && $query['fromdate']!=''){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            //$all_lead->whereBetween('la.activity_time', array($fromdate, $todate));
            $leadids = DB::table('lead_activities')->select('lead_id')->whereBetween('activity_time', array($fromdate, $todate))->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }
        
        if(isset($query['status']) && $query['status']!='0'){  
            if($query['status']=='close')  
            {
                $all_lead->where('leads.is_completed', '=', '1');
            }  
            elseif($query['status']=='dead')  
            {
                $all_lead->where('leads.is_active', '=', '0');
            }  
            elseif($query['status']=='new')  
            {
                $all_lead->where('leads.lead_strength_id', '=', '0')->where('leads.is_completed', '=', '0')->where('leads.is_active', '=', '1');
            } 
            elseif($query['status']=='open')  
            {
                $all_lead->where('leads.lead_strength_id', '<>', '0')->where('leads.is_completed', '=', '0')->where('leads.is_active', '=', '1');
            }       
            
        } 
        //echo $query['status'];

        $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get();  
        //echo $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->toSql();exit;

        if ($result->count()) {
            $result = $result->toArray();
        }
        //print_r($result);exit;
        return $result;
    }

    
    
    public static function activeLead()
    {
         $activeLead = Lead::select('id as lead_id')
            ->where('is_active','=',1)
            ->orderBy('id', 'DESC')
            ->get()
           ->toArray();
       	return $activeLead;	
    }
    
  public static function fetchLeadsbyLead($perPage, $postArray) {


        $subquery = '(SELECT * FROM lead_activities where id IN (select max(id) as id from lead_activities group by lead_id DESC) ORDER BY lead_id DESC) 
                AS temp_activity';

        $lead_list = Lead::select('leads.id as lead_id', 'leads.sales_person_id', 'u.display_name', 'c.company_name', 'ls.loan_type as status', DB::raw('sum(lp.margin_value * lp.quantity) as valuation'), 'temp_activity.id  as last_activity_id', 'temp_activity.activity_type as last_activity_type', 'temp_activity.activity_time as last_activity_time', 'temp_activity.activity_note as last_activity_note')
                ->leftjoin('users as u', 'u.id', '=', 'leads.sales_person_id')
                ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
                ->leftjoin('lead_strengths as ls', 'ls.id', '=', 'leads.lead_strength_id')
                ->leftjoin('lead_products as lp', 'lp.lead_id', '=', 'leads.id')
                ->leftJoin(DB::raw($subquery), 'temp_activity.lead_id', '=', 'leads.id');
        if (isset($postArray['lead']) && $postArray['lead']!= 99999) {
           
           $lead_list = $lead_list->where('leads.id', '=', $postArray['lead']);
        }

        if (isset($postArray['fromdate']) && $postArray['fromdate']!= '') {
            $fromDate = str_replace('/', '-',$postArray['fromdate']);
            $fromDate = date('Y-m-d H:i:s',strtotime($fromDate));
            $lead_list = $lead_list->where('temp_activity.activity_time', '>', $fromDate);
        }
        
         if (isset($postArray['todate']) && $postArray['todate']!= '') {
            $toDate = str_replace('/', '-',$postArray['todate']);
            $toDate = date('Y-m-d H:i:s',strtotime($toDate. ' +1 day'));
            $lead_list = $lead_list->where('temp_activity.activity_time', '<', $toDate);
        }
        if (isset($postArray['activity_type']) && $postArray['activity_type']!='') {
           
           $lead_list = $lead_list->where('temp_activity.activity_type', '=', $postArray['activity_type']);
        }
        
        if (isset($postArray['activity_modes']) && $postArray['activity_modes']!=99999) {
           if($postArray['activity_modes']==0)
           {
               $lead_list = $lead_list->where('leads.lead_strength_id', '=', $postArray['activity_modes']);
           }
           else{
               $lead_list = $lead_list->where('ls.id', '=', $postArray['activity_modes']);
           }
        }
        
        if (isset($postArray['last_id']) && $postArray['last_id']!='') {
           
           $lead_list = $lead_list->where('leads.id', '<', $postArray['last_id']);
        }
        
//        $lead_list = $lead_list->where('u.is_active', '=', 1);
        $lead_list = $lead_list->where('leads.is_active', '=', 1)
                ->groupBy('leads.id')
                ->orderBy('leads.id', 'DESC')
                ->limit($perPage)
                ->get()
                ->toArray();


        return $lead_list;
    }  
    



    public static function count_close_lead()
    {
        $data = Lead::select(DB::raw('COUNT(*) as close_lead_count'))->where('is_completed','=','1');
        if (Auth::user()->user_type == 'SP')
        {
            $data->where('sales_person_id','=',Auth::user()->id);
        }
        $data = $data->first()->toArray();
        return $data ;
    }

    public static function count_dead_lead()
    {
        $data = Lead::select(DB::raw('COUNT(*) as dead_lead_count'))->where('is_active','=','0');
        
        if (Auth::user()->user_type == 'SP')
        {
            $data->where('sales_person_id','=',Auth::user()->id);
        }
        $data = $data->first()->toArray();
        return $data ;
    }

    public static function count_open_lead()
    {
        $data = Lead::select(DB::raw('COUNT(*) as open_lead_count'))->where('lead_strength_id','<>','0')->where('is_completed','=','0')->where('is_active','=','1');
        if (Auth::user()->user_type == 'SP')
        {
            $data->where('sales_person_id','=',Auth::user()->id);
        }
        $data = $data->first()->toArray();
        return $data ;
    }

    public static function count_new_lead()
    {
        $data = Lead::select(DB::raw('COUNT(*) as new_lead_count'))->where('lead_strength_id','=','0')->where('is_completed','=','0')->where('is_active','=','1');        
        if (Auth::user()->user_type == 'SP')
        {
            $data->where('sales_person_id','=',Auth::user()->id);
        }
        $data = $data->first()->toArray();
        return $data ;
    }

    public static function count_all_leads($saleperson_id = '', $time = '') {
        $leads = Lead::select(DB::raw('COUNT(*) as lead_count'));
        if ($saleperson_id != '' && $saleperson_id != 0) {
            $leads = $leads->where('sales_person_id', '=', $saleperson_id);
        }
       if ($time == 1) {
            $dtime = date('Y-m-d', strtotime('-1 days'));
            $leads->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 2) {
            $dtime = date('Y-m-d', strtotime('-7 days'));
            $leads->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 3) {
            $dtime = date('Y-m-d', strtotime('-1 MONTH'));
            $leads->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 4) {
            $dtime = date('Y-m-d', strtotime('-1 YEAR'));
            $leads->where('leads.lead_created_on', '>=', $dtime);
        }

        $leads = $leads->first()->toArray();
        return $leads;
    }

    public static function get_list_by_status($status='', $salePersonId = '',$time='',$perPage = '',$last_id='') {
         
           
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at', 'leads.lead_created_on', 'leads.lead_completed_date', 'leads.is_completed', 'l.loan_type', 'p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'), 'p.quantity', 'c.company_name', 'cc.display_name')
                ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
                ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
                ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
                ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
        if (isset($last_id) && $last_id != '') {
            $time = str_replace('/', '-',$time);
            $fromdate = date('Y-m-d H:i:s',strtotime($time));
            $all_lead->where('leads.id', '<', $last_id)->where('leads.lead_created_on', '>=', $fromdate);
        }
        if ($status == 'close') {
            $all_lead->where('leads.is_completed', '=', '1');
        } elseif ($status == 'dead') {
            $all_lead->where('leads.is_active', '=', '0');
        } elseif ($status == 'new') {
            $all_lead->where('leads.lead_strength_id', '=', '0')->where('leads.is_completed', '=', '0')->where('leads.is_active', '=', '1');
        } elseif ($status == 'open') {
            $all_lead->where('leads.lead_strength_id', '<>', '0')->where('leads.is_completed', '=', '0')->where('leads.is_active', '=', '1');
        }
        if ($time == 1) {
            $dtime = date('Y-m-d', strtotime('-1 days'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 2) {
            $dtime = date('Y-m-d', strtotime('-7 days'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 3) {
            $dtime = date('Y-m-d', strtotime('-1 MONTH'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 4) {
            $dtime = date('Y-m-d', strtotime('-1 YEAR'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        }
       
        if ($salePersonId != '0') {
            $all_lead->where('leads.sales_person_id', '=', $salePersonId);
        }
        
        if ($perPage != '') {
            $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->limit($perPage)->get()->toArray();
        } else {
            $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get()->toArray();
        }

        return $result;
    }

    public static function search_lead_by_leadstatus($query,$lstatus)
    {
         $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at','leads.is_completed', 'leads.is_active','l.loan_type','p.margin_value', DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'p.quantity','c.company_name','cc.display_name')
            ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id')
            ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
            ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
            ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
        

        if(isset($query['sale_person']) && $query['sale_person']!=''){
            $all_lead->where('sales_person_id', '=', $query['sale_person']);
        }

        if(isset($query['product']) && $query['product']!='0'){
            $leadids = DB::table('lead_products')->select('lead_id')->where('prod_id', '=',$query['product'])->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $leadval)
                {
                    array_push($newleads, $leadval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        if(isset($query['lead_id']) && $query['lead_id']!=''){

            $lead = preg_replace('/[^0-9]/', '', $query['lead_id']);
            $all_lead->where('leads.id', 'LIKE', '%' . $lead . '%');
        }
        

        if(isset($query['fromdate']) && $query['fromdate']!='' && !isset($query['todate'])){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $leadids = DB::table('lead_activities')->select('lead_id')->where('activity_time', '>=',$fromdate)->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        else if(isset($query['todate']) && $query['todate']!='' && !isset($query['fromdate'])){
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            $leadids = DB::table('lead_activities')->select('lead_id')->where('activity_time', '<=',$todate)->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        else if(isset($query['todate']) && $query['todate']!='' && isset($query['fromdate']) && $query['fromdate']!=''){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            //$all_lead->whereBetween('la.activity_time', array($fromdate, $todate));
            $leadids = DB::table('lead_activities')->select('lead_id')->whereBetween('activity_time', array($fromdate, $todate))->get();
            $newleads  = array();
            if ($leadids->count()) 
            {
                $leadids = $leadids->toArray();
                
                foreach($leadids as $actval)
                {
                    array_push($newleads, $actval->lead_id);
                }
            }
            else
            {
                $leadids = array();
            }
            $all_lead->whereIn('leads.id',$newleads);
        }

        if($lstatus == 'close')  
        {
            $all_lead->where('leads.is_completed', '=', '1');
        }  
        elseif($lstatus == 'dead')  
        {
            $all_lead->where('leads.is_active', '=', '0');
        }  
        elseif($lstatus == 'new')  
        {
            $all_lead->where('leads.lead_strength_id', '=', '0')->where('leads.is_completed','=','0')->where('leads.is_active','=','1');
        } 
        elseif($lstatus == 'open')  
        {
            $all_lead->where('leads.lead_strength_id','<>','0')->where('leads.is_completed','=','0')->where('leads.is_active','=','1');
        }

             $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get();  
        //echo $result = $all_lead->groupBy('leads.id')->orderBy('leads.id', 'DESC')->toSql();exit;

        if ($result->count()) {
            $result = $result->toArray();
        }
        //print_r($result);exit;
        return $result;
    }

    public static function search_leads_from_dashboard($status, $id = '', $time = '') {

        if ($status == 'close') {
            $all_lead = Lead::select(DB::raw('COUNT(*) as close_lead_count'));
            $all_lead->where('leads.is_completed', '=', '1');
        } elseif ($status == 'dead') {
            $all_lead = Lead::select(DB::raw('COUNT(*) as dead_lead_count'));
            $all_lead->where('leads.is_active', '=', '0');
        } elseif ($status == 'new') {
            $all_lead = Lead::select(DB::raw('COUNT(*) as new_lead_count'));
            $all_lead->where('leads.lead_strength_id', '=', '0')->where('leads.is_completed', '=', '0')->where('leads.is_active', '=', '1');
        } elseif ($status == 'open') {
            $all_lead = Lead::select(DB::raw('COUNT(*) as open_lead_count'));
            $all_lead->where('leads.lead_strength_id', '<>', '0')->where('leads.is_completed', '=', '0')->where('leads.is_active', '=', '1');
        }
        if ($time == 1) {
            $dtime = date('Y-m-d', strtotime('-1 days'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 2) {
            $dtime = date('Y-m-d', strtotime('-7 days'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 3) {
            $dtime = date('Y-m-d', strtotime('-1 MONTH'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 4) {
            $dtime = date('Y-m-d', strtotime('-1 YEAR'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        }
        if ($id != '0') {
            $all_lead->where('sales_person_id', '=', $id);
        }

        $response = $all_lead->get()->first()->toArray();

        return $response;
    }

    public static function get_leads_by_id($status, $id, $time = '') {
        $all_lead = Lead::select('leads.id', 'leads.custom_id', 'leads.customer_contact_person_id', 'leads.sales_person_id', 'leads.lead_strength_id', 'leads.updated_at', 'leads.lead_created_on', 'leads.lead_completed_date', 'leads.is_completed', 'leads.is_active');

        if ($status == 'close') {
            $all_lead->where('leads.is_completed', '=', '1');
        } elseif ($status == 'dead') {
            $all_lead->where('leads.is_active', '=', '0');
        } elseif ($status == 'new') {
            $all_lead->where('leads.lead_strength_id', '=', '0')->where('leads.is_completed', '=', '0')->where('leads.is_active', '=', '1');
        } elseif ($status == 'open') {
            $all_lead->where('leads.lead_strength_id', '<>', '0')->where('leads.is_completed', '=', '0')->where('leads.is_active', '=', '1');
        }
        if ($time == 1) {
            $dtime = date('Y-m-d', strtotime('-1 days'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 2) {
            $dtime = date('Y-m-d', strtotime('-7 days'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 3) {
            $dtime = date('Y-m-d', strtotime('-1 MONTH'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 4) {
            $dtime = date('Y-m-d', strtotime('-1 YEAR'));
            $all_lead->where('leads.lead_created_on', '>=', $dtime);
        }
        if ($id != 0) {
        $all_lead = $all_lead->where('leads.sales_person_id', '=', $id);
        }
        if (Auth::user()->user_type == 'SP')
        {
            $all_lead = $all_lead->where('leads.sales_person_id', '=', Auth::user()->id);
        }
        $response=$all_lead->get()->toArray();
        return $response;
    }

    public static function dashBordPieCalculation($saleperson_id = '', $time = '') {
        $dashBordPie = Lead::select('leads.lead_strength_id', DB::raw('COUNT(leads.lead_strength_id) as LeadStreangthCount'), 'l.loan_type as lead_strength','l.color_code','l.key_details')
                ->leftjoin('lead_strengths as l', 'l.id', '=', 'leads.lead_strength_id');
        
        if (Auth::user()->user_type == 'SP')
        {
            $dashBordPie = $dashBordPie->where('leads.sales_person_id', '=', Auth::user()->id);
        }
        if ($saleperson_id != '' && $saleperson_id != 0) {
            $dashBordPie = $dashBordPie->where('leads.sales_person_id', '=', $saleperson_id);
        }
        if ($time == 1) {
            $dtime = date('Y-m-d', strtotime('-1 days'));
            $dashBordPie->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 2) {
            $dtime = date('Y-m-d', strtotime('-7 days'));
            $dashBordPie->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 3) {
            $dtime = date('Y-m-d', strtotime('-1 MONTH'));
            $dashBordPie->where('leads.lead_created_on', '>=', $dtime);
        } else if ($time == 4) {
            $dtime = date('Y-m-d', strtotime('-1 YEAR'));
            $dashBordPie->where('leads.lead_created_on', '>=', $dtime);
        }

        $dashBordPie = $dashBordPie->groupBy('leads.lead_strength_id')
                ->orderBy('leads.lead_strength_id', 'DESC')
                ->get()
                ->toArray();
        return $dashBordPie;
    }



    public static function checkCustomer($id)
    {       
      $numprod = Lead::where('custom_id', '=', $id)->count();
      return $numprod;
    }


    public static function delete_lead($id)
    {
        Lead_activity::where('lead_id', '=', $id)->delete();
        Lead_supporting_document::where('lead_id', '=', $id)->delete();
        Lead_product::where('lead_id', '=', $id)->delete();
        $res = Lead::where('id', '=', $id)->delete();
        if($res)
        {
            return 1;
        }
        else{
            return 0;
        }
    }

    public static function checkSPUser($id)
    {       
      $numprod = Lead::where('sales_person_id', '=', $id)->count();
      return $numprod;
    }

    public static function checkUpdatedBy($id)
    {       
      $numprod = Lead::where('updated_by', '=', $id)->count();
      return $numprod;
    }
        
}
