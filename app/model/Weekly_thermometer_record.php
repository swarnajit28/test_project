<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use helper;
class Weekly_thermometer_record extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    //protected $table = 'customer_attachments';
     public static function add_weekly_thermometer_record($insert_array) {
        $result = Weekly_thermometer_record::firstOrCreate($insert_array)->toArray();
        return $result;
    }
    
    public static function check_week_exists($insert_array) {
     $count = Weekly_thermometer_record::select('id')
             ->where('week_year', '=', $insert_array['week_year'])
             ->where('week_number', '=', $insert_array['week_number'])
             ->count();
//        if ($count->count()) {
//            $count = $count->toArray();
//        }
        return $count;  
    }
    
    public static function all_record($year){
        return Weekly_thermometer_record::all()->where('week_year','=',$year)->toArray();
    }
    
    public static function weekly_5k_data($year){
        $maxid = Weekly_thermometer_record::where('week_year', '=', $year)->max('week_number');
         $result = Weekly_thermometer_record::select('*')
             ->where('week_year', '=', $year)
             ->where('week_number', '=', $maxid)
             ->get()
             ->toArray();
        
        return $result;  
    }

}
