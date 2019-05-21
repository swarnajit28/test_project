<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use helper;
class Umbrella_querie extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    //protected $table = 'customer_attachments';
     public static function add_umbrella_querie($insert_array) {
        $result = Umbrella_querie::firstOrCreate($insert_array)->toArray();
        return $result;
    }
    
    public static function fetch_all_report($items_per_page) {
        $result = Umbrella_querie::orderBy('id', 'DESC')->limit($items_per_page)->get();
        return $result;
    }

    public static function get_load_umbrellla_report($qty, $id) {
        $report = Umbrella_querie::select('*')->where('id', '<', $id)->orderBy('id', 'DESC')->limit($qty)->get()->toArray();
        return $report;
    }
    public static function searchQuerie($query) {
        $all_data = Umbrella_querie::orderBy('id', 'DESC');
        if (isset($query['fromdate']) && $query['fromdate'] != '') {
            $fromdate = str_replace('/', '-', $query['fromdate']);
            $fromdate = date('Y-m-d H:i:s', strtotime($fromdate));
            $all_data = $all_data->where('created_at', '>=', $fromdate);
        }
        if (isset($query['todate']) && $query['todate'] != '') {
            $todate = str_replace('/', '-', $query['todate']);
            $todate = date('Y-m-d H:i:s', strtotime($todate. "+1 days"));
            $all_data = $all_data->where('created_at', '<=', $todate);
        }
        $result = $all_data->get();
        return $result;
    }

}
