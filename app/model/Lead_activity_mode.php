<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use helper;
use DB;

class Lead_activity_mode extends Model {

    public $timestamps = false;
    protected $table = 'lead_activity_modes';
    protected $fillable = ['activity_mode', 'is_active'];

    public static function insert_data($loan_data) {
        // dd($loan_data);
        $result = Lead_activity_mode::firstOrCreate($loan_data)->toArray();
        //dd($result);
        return $result;
    }

    public static function update_data($loan_data) {
        // dd($loan_data);
        $new = array(
            'activity_mode' => $loan_data['activity_mode'],
            'is_active' => $loan_data['is_active']);
        $resp = Lead_activity_mode::where('id', $loan_data['id'])->update($new);

        return $resp;
    }

    public static function delete_data($id) {
        $resp = Lead_activity_mode::where('id', '=', $id)->delete();
        //dd($resp);
        return $resp;
    }

    public static function list_lead() {
        $result = Lead_activity_mode::whereIs_active(1)->get()->toArray();
        return $result;
    }

    public static function fetchmodes() {
        $resp = Lead_activity_mode::select('id', 'activity_mode')->where('is_active', '=', '1')->orderBy('activity_mode')->get();
        if ($resp->count()) {
            $resp = $resp->toArray();
        } else {
            $resp = array();
        }
        return $resp;
    }

    public static function get_all_lead_activity($qty)
    {
          $users = Lead_activity_mode::select('*')->orderBy('id', 'DESC')->limit($qty)->get()->toArray();
      return $users;
    }

    public static function lastmode()
    {
        $mode_detail = Lead_activity_mode::select('id')->orderBy('id','ASC')->limit('1')->get()->first();
        if(!empty($mode_detail))
        {
            if($mode_detail->count()){
                $id = $mode_detail->toArray();
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
    

    public static function get_load_lead_activity_mode($qty,$id)
    {
        $users = Lead_activity_mode::select('*')->where('id', '<', $id)->orderBy('id', 'DESC')->limit($qty)->get()->toArray(); 
        return $users;
    }

    public static function activitymodestatchange($id,$status)
    {
        $resp = Lead_activity_mode::where('id','=',$id)->update(['is_active' => $status]);
        return $resp;
    }
}

?>