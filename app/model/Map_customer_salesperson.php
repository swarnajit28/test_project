<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\model\Lead;
use App\model\Website_setting;
use App\User;
use DB;
use helper;

class Map_customer_salesperson extends Model
{
    protected $guarded = ['id'];
    protected $table = 'map_customer_salespersons';
    public $timestamps = false;

    public static function insertSP($id,$spid) 
    {
        $fetch_data = Map_customer_salesperson::select('*')->where('customer_id', $id)->get()->toArray();
        //helper::pre($fetch_data, 1); 
        Map_customer_salesperson::where('customer_id', '=', $id)->delete();

        $mapSP = new Map_customer_salesperson;
        $mapSP->customer_id = $id;
        $mapSP->user_id = $spid;
        if(count($fetch_data)>0){
        $mapSP->is_lead_on_hold = $fetch_data[0]['is_lead_on_hold'];
        $mapSP->lead_started_on = $fetch_data[0]['lead_started_on'];
        $mapSP->is_executive_for_life = $fetch_data[0]['is_executive_for_life'];
        }
        $mapSP->save();
    }

    public static function fetchSP($id) 
    {
        $sp_details = Map_customer_salesperson::select('user_id')
                          ->where('customer_id', $id)
                          ->get();
        
        if ($sp_details->count()) {
            $sp_details = $sp_details->first()->toArray();
        }
        else
        {
            $sp_details = array('user_id' => '' );
        }
        //print_r($sp_details);
        return $sp_details;
    }
    
    public static function checkSalePerson($id)
    {       
      $numprod = Map_customer_salesperson::where('user_id', '=', $id)->count();
      return $numprod;
    }
    
 
    public static function non_exclusive_sp($id, $sp) {
        $lock_days = Website_setting::exclusive_lock_days();
        $date = date('Y-m-d H:i:s', strtotime('-' . $lock_days . ' days'));
        $sub_query = "(`lead_started_on` >'" . $date . "' or `lead_started_on` is null or `is_executive_for_life`=1)";
        $custom_ids = DB::table('map_customer_salespersons')->select('user_id')
                ->whereRaw($sub_query)
                ->distinct()
                ->orderBy('user_id', 'ASC')
                ->get();
        //helper::pre($custom_ids, 1);         
        $lifetimeids = array();
        if ($custom_ids->count()) {
            $custom_ids = $custom_ids->toArray();

            foreach ($custom_ids as $actval) {
                if ($actval->user_id != $sp['user_id']){
                    array_push($lifetimeids, $actval->user_id);
                }
            }
        }

        $spData = User::select('*')
                        ->where('user_type', '=', 'SP')
                        ->where('is_active', '=', 1)
                        ->whereNotIn('id', $lifetimeids)
                        ->orderby('display_name', 'ASC')
                        ->get()->toArray();
        //helper::pre($spData, 0);
        return $spData;
    }

    public static function non_lock_exclusive_sp($id, $sp,$leaSaleperson) {
        $lock_days = Website_setting::exclusive_lock_days();
        $date = date('Y-m-d H:i:s', strtotime('-' . $lock_days . ' days'));
        $sub_query = "(`lead_started_on` >'" . $date . "' or `lead_started_on` is null or `is_executive_for_life`=1)";
        $custom_ids = DB::table('map_customer_salespersons')->select('user_id')
                ->whereRaw($sub_query)
                ->distinct()
                ->orderBy('user_id', 'ASC')
                ->get();
        //helper::pre($custom_ids, 1);         
        $lifetimeids = array();
        if ($custom_ids->count()) {
            $custom_ids = $custom_ids->toArray();

            foreach ($custom_ids as $actval) {
                if (($actval->user_id != $sp['user_id'])&&($actval->user_id != $leaSaleperson)){
                    array_push($lifetimeids, $actval->user_id);
                }
            }
        }
        //helper::pre($lifetimeids, 1);
        $spData = User::select('*')
                        ->where('user_type', '=', 'SP')
                        ->where('is_active', '=', 1)
                        ->whereNotIn('id', $lifetimeids)
                        ->orderby('display_name', 'ASC')
                        ->get()->toArray();
        //helper::pre($spData, 0);
        return $spData;
    }

   

     public static function check_is_executive_for_life($id){
          $fetch_data = Map_customer_salesperson::select('is_executive_for_life')->where('customer_id', $id)->first()->toArray();
         //helper::pre($fetch_data, 1);
          return $fetch_data['is_executive_for_life'];
     }
    
}
