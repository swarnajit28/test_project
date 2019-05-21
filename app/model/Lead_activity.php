<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\model\Lead;
use helper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class Lead_activity extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public static function insertactivity($id, $note, $act_type, $act_mode) {
        $lead_activity = new Lead_activity;
        $lead_activity->lead_id = $id;
        $lead_activity->activity_time = date('Y-m-d H:i:s');
        $lead_activity->activity_type = $act_type;
        $lead_activity->lead_activity_mode_id = $act_mode;
        $lead_activity->activity_note = $note;
        $lead_activity->activity_done_by_user_id = Auth::id();
        $lead_activity->save();
    }


    public static function fetchallactivity($id)
    {
        $activity_details = Lead_activity::select('*','l.activity_mode')
                          ->leftjoin('lead_activity_modes as l', 'l.id', '=', 'lead_activities.lead_activity_mode_id')                          
                          ->where("lead_activities.activity_time",">=",Carbon::now()->subDays(1)->toDateTimeString())
                          ->where('activity_done_by_user_id','=',$id)
                          ->orderBy('lead_activities.id', 'DESC')
                          ->get();
        //helper::pre($activity_details->toArray());
        if ($activity_details->count()) {
            $activity_details = $activity_details->toArray();
        }
        else
        {
            $activity_details = array();
        }
        return $activity_details;
    }
  

    public static function searchallactivity($id,$data)
    {
        //echo 1;exit;
        $leadno = $data['leadno'];
        $status = $data['status'];
        $activity_details = Lead_activity::select('*','l.activity_mode')
                          ->leftjoin('lead_activity_modes as l', 'l.id', '=', 'lead_activities.lead_activity_mode_id');

        if(isset($data['status']) && $data['status']!='')
        {
            $activity_details->where('lead_activities.activity_type', '=', $data['status']);
        }

        if(isset($data['leadno']) && $data['leadno']!='')
        {
            $activity_details->where('lead_activities.lead_id', '=', $data['leadno']);
        }

        

        $activity_details = $activity_details->where('activity_done_by_user_id','=',$id)
                          ->where("lead_activities.activity_time",">=",Carbon::now()->subDays(1)->toDateTimeString())
                          ->orderBy('lead_activities.id', 'DESC')
                          ->get();
        //helper::pre($activity_details->toArray());
        if ($activity_details->count()) {
            $activity_details = $activity_details->toArray();
        }
        else
        {
            $activity_details = array();
        }
        return $activity_details;
    }

    public static function fetchactivity($id)
    {
        $activity_details = Lead_activity::select('*','l.activity_mode')
        				  ->leftjoin('lead_activity_modes as l', 'l.id', '=', 'lead_activities.lead_activity_mode_id')
                          ->where('lead_id', $id)
                          ->orderBy('lead_activities.id','DESC')
                          ->get();
        //helper::pre($activity_details->toArray());
        if ($activity_details->count()) {
            $activity_details = $activity_details->toArray();
        }
        else
        {
        	$activity_details = array();
        }
        return $activity_details;
    }

    public static function searchleadactivity($query)
    {
        //print_r($query);
        $activity_details = Lead_activity::select('*','l.activity_mode')
                          ->leftjoin('lead_activity_modes as l', 'l.id', '=', 'lead_activities.lead_activity_mode_id');
                          

        if(isset($query['act_type']) && $query['act_type']!=''){
            if($query['act_type']!='0')
            {
                //echo $query['act_type'];exit;
                $activity_details->where('lead_activities.activity_type', '=', $query['act_type']);
            }
        }

        if(isset($query['act_mode']) && $query['act_mode']!='0'){
            /*if($query['act_type']!='1')
            {*/
            $activity_details->where('lead_activities.lead_activity_mode_id', '=', $query['act_mode']);
            //}
        }

        if(isset($query['fromdate']) && $query['fromdate']!='' && !isset($query['todate'])){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $activity_details->where('lead_activities.activity_time', '>=', $fromdate);
        }

        else if(isset($query['todate']) && $query['todate']!='' && !isset($query['fromdate'])){
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            $activity_details->where('lead_activities.activity_time', '<=', $todate);
        }

        else if(isset($query['todate']) && $query['todate']!='' && isset($query['fromdate']) && $query['fromdate']!=''){
            $fromdate = str_replace('/', '-',$query['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $todate = str_replace('/', '-',$query['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day' ));
            $activity_details->whereBetween('lead_activities.activity_time', array($fromdate, $todate));
        }

       $result = $activity_details->orderBy('lead_activities.id','DESC')->where('lead_id', $query['id'])->get();

        if ($result->count()) {
            $result = $result->toArray();
            //helper::pre($result);exit;
        }
        else
        {
            $result = array();
        }
        return $result;
    }

    
    public static function fetchleadsbyproducts($perPage)
    {        
        $activity_details = Lead_activity::select('lead_activities.id as actid','lead_activities.lead_id','lead_activities.activity_time','lead_activities.activity_type','lead_activities.lead_activity_mode_id','lead_activities.activity_note','lead_activities.activity_done_by_user_id','ls.id', 'ls.custom_id', 'ls.customer_contact_person_id', 'ls.sales_person_id', 'ls.lead_strength_id', 'ls.updated_at','l.loan_type','la.activity_mode')
                          ->join('leads as ls','ls.id','=','lead_activities.lead_id')  
                          ->leftjoin('lead_activity_modes as la', 'la.id', '=', 'lead_activities.lead_activity_mode_id')
                          ->leftjoin('lead_strengths as l', 'l.id', '=', 'ls.lead_strength_id') 
                          ->orderBy('lead_activities.id','DESC')
                          ->limit($perPage)
                          ->get();
        $activity_details = $activity_details->toArray();
        $all_leads = array();
        //foreach($i=0;$i<count($activity_details);$i++)
        foreach($activity_details as $actval)
        {
            $all_lead = Lead::select(DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'c.company_name','cc.display_name')
                ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
                ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
                ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
            
            $all_lead = $all_lead->where('leads.id','=',$actval['lead_id'])->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get()->first();
            $all_lead=$all_lead->toArray();           
           array_push($all_leads,$all_lead);
        }
        for($i=0;$i<count($activity_details);$i++)
        {
            $activity_details[$i]['totprod'] = $all_leads[$i]['totprod'];
            $activity_details[$i]['valuation'] = $all_leads[$i]['valuation'];
            $activity_details[$i]['company_name'] = $all_leads[$i]['company_name'];
            $activity_details[$i]['display_name'] = $all_leads[$i]['display_name'];
        }        
        //helper::pre($activity_details);exit;
        if (!empty($activity_details)) {
            $activity_details = $activity_details;
        }
        else
        {
            $activity_details = array();
        }
        return $activity_details;
    }
    
    public static function loadAjaxactivityproduct($perPage,$id)
    {  
        $activity_detail = Lead_activity::select('lead_activities.id as actid','lead_activities.lead_id','lead_activities.activity_time','lead_activities.activity_type','lead_activities.lead_activity_mode_id','lead_activities.activity_note','lead_activities.activity_done_by_user_id','ls.id', 'ls.custom_id', 'ls.customer_contact_person_id', 'ls.sales_person_id', 'ls.lead_strength_id', 'ls.updated_at','l.loan_type','la.activity_mode')
                          ->join('leads as ls','ls.id','=','lead_activities.lead_id') 
                          ->leftjoin('lead_activity_modes as la', 'la.id', '=', 'lead_activities.lead_activity_mode_id') 
                          ->leftjoin('lead_strengths as l', 'l.id', '=', 'ls.lead_strength_id') ;
                  
        $activity_detail->where('lead_activities.id', '<', $id);
        $activity_details = $activity_detail->orderBy('lead_activities.id','DESC')->limit($perPage)->get();
        $activity_details = $activity_details->toArray();
        $all_leads = array();
        foreach($activity_details as $actval)
        {
            $all_lead = Lead::select(DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'c.company_name','cc.display_name')
                ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
                ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
                ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
            
            $all_lead = $all_lead->where('leads.id','=',$actval['lead_id'])->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get()->first();
            $all_lead=$all_lead->toArray();           
           array_push($all_leads,$all_lead);
        }
        for($i=0;$i<count($activity_details);$i++)
        {
            $activity_details[$i]['totprod'] = $all_leads[$i]['totprod'];
            $activity_details[$i]['valuation'] = $all_leads[$i]['valuation'];
            $activity_details[$i]['company_name'] = $all_leads[$i]['company_name'];
            $activity_details[$i]['display_name'] = $all_leads[$i]['display_name'];
        }
       

        if (count($activity_details)) {
            $activity_details = $activity_details;
        }
        else
        {
            $activity_details = array();
        }
        return $activity_details;
    }
 
    public static function SearchActivity_By_Product($data)
    {        
        $activity_detail = Lead_activity::select('lead_activities.id as actid','lead_activities.lead_id','lead_activities.activity_time','lead_activities.activity_type','lead_activities.lead_activity_mode_id','lead_activities.activity_note','lead_activities.activity_done_by_user_id','ls.id', 'ls.custom_id', 'ls.customer_contact_person_id', 'ls.sales_person_id', 'ls.lead_strength_id', 'ls.updated_at','l.loan_type','la.activity_mode')
                          ->join('leads as ls','ls.id','=','lead_activities.lead_id')  
                          ->leftjoin('lead_activity_modes as la', 'la.id', '=', 'lead_activities.lead_activity_mode_id')
                          ->leftjoin('lead_strengths as l', 'l.id', '=', 'ls.lead_strength_id') ;

        if(isset($data['product']) && $data['product']!='0'){   

            $leadids = DB::table('lead_products')->select('lead_id')->where('prod_id', '=',$data['product'])->get();
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
            $activity_detail->whereIn('lead_activities.lead_id',$newleads); 
        }

        if(isset($data['activity_type']) && $data['activity_type']!='0'){
            
            $activity_detail->where('lead_activities.activity_type', '=', $data['activity_type']);
        }

        if(isset($data['activity_mode']) && $data['activity_mode']!='0'){
            $activity_detail->where('ls.lead_strength_id', '=', $data['activity_mode']);
        }                   

        if(isset($data['fromdate']) && $data['fromdate']!='' && !isset($data['todate'])){
            $fromdate = str_replace('/', '-',$data['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $activity_detail->where('lead_activities.activity_time', '>=', $fromdate);
        }

        else if(isset($data['todate']) && $data['todate']!='' && !isset($data['fromdate'])){
            $todate = str_replace('/', '-',$data['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            $activity_detail->where('lead_activities.activity_time', '<=', $todate);
        }

        else if(isset($data['todate']) && $data['todate']!='' && isset($data['fromdate']) && $data['fromdate']!=''){
            $fromdate = str_replace('/', '-',$data['fromdate']);
            $fromdate = date('Y-m-d H:i:s',strtotime($fromdate));
            $todate = str_replace('/', '-',$data['todate']);
            $todate = date('Y-m-d H:i:s',strtotime($todate. ' +1 day'));
            $activity_detail->whereBetween('lead_activities.activity_time', array($fromdate, $todate));
        }

        
        $activity_details = $activity_detail->orderBy('lead_activities.id','DESC')
                          ->get();
        $activity_details = $activity_details->toArray();
        $all_leads = array();
        //foreach($i=0;$i<count($activity_details);$i++)
        foreach($activity_details as $actval)
        {
            $all_lead = Lead::select(DB::raw('COUNT(p.prod_id) as totprod'), DB::raw('sum(p.margin_value * p.quantity) as valuation'),'c.company_name','cc.display_name')
                ->leftjoin('lead_products as p', 'p.lead_id', '=', 'leads.id')
                ->leftjoin('customers as c', 'c.id', '=', 'leads.custom_id')
                ->leftjoin('users as cc', 'cc.id', '=', 'leads.sales_person_id');
            
            $all_lead = $all_lead->where('leads.id','=',$actval['lead_id'])->groupBy('leads.id')->orderBy('leads.id', 'DESC')->get()->first();
            $all_lead=$all_lead->toArray();           
           array_push($all_leads,$all_lead);
        }
        for($i=0;$i<count($activity_details);$i++)
        {
            $activity_details[$i]['totprod'] = $all_leads[$i]['totprod'];
            $activity_details[$i]['valuation'] = $all_leads[$i]['valuation'];
            $activity_details[$i]['company_name'] = $all_leads[$i]['company_name'];
            $activity_details[$i]['display_name'] = $all_leads[$i]['display_name'];
        }        
        //helper::pre($activity_details);exit;
        if (!empty($activity_details)) {
            $activity_details = $activity_details;
        }
        else
        {
            $activity_details = array();
        }
        return $activity_details;
    }
    




    
    public static function deleteleadactivity($id)
    {       
        Lead_activity::where('lead_id', '=', $id)->delete();   
    }

    public static function checkActivity($id)
    {       
      $numprod = Lead_activity::where('activity_done_by_user_id', '=', $id)->count();
      return $numprod;
    }
}
