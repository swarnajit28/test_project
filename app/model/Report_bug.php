<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
use helper;
use App\model\customer;
use App\model\customer_contact_person;
class Report_bug extends Model
{    
//    protected $fillable = [
//        
//    ];

    public $timestamps  = false;
    protected $guarded = ['id'];

    
   public static function add_bug_report($data_array) {
       $added_by_user_id=Auth::user()->id;
       $result = Report_bug::updateOrCreate($data_array);
        return $result;
   } 
   
    public static function fetch_all_bug_report($items_per_page) {
        $result = Report_bug::select('report_bugs.*','u.display_name')->leftjoin('users as u', 'u.id', '=', 'report_bugs.added_by_user_id')->orderBy('report_bugs.id', 'DESC')->limit($items_per_page)->get();
        return $result;
    }
    
    public static function get_load_bug_report($qty, $id) {
        $report = Report_bug::select('report_bugs.*','u.display_name')->leftjoin('users as u', 'u.id', '=', 'report_bugs.added_by_user_id')->where('report_bugs.id', '<', $id)->orderBy('report_bugs.id', 'DESC')->limit($qty)->get()->toArray();
        return $report;
    }
    
    public static function searchQuerie($query) {
        $all_data = Report_bug::select('report_bugs.*','u.display_name')->leftjoin('users as u', 'u.id', '=', 'report_bugs.added_by_user_id')->orderBy('report_bugs.id', 'DESC');
        if (isset($query['fromdate']) && $query['fromdate'] != '') {
            $fromdate = str_replace('/', '-', $query['fromdate']);
            $fromdate = date('Y-m-d H:i:s', strtotime($fromdate));
            $all_data = $all_data->where('report_bugs.bug_posted_on', '>=', $fromdate);
        }
        if (isset($query['todate']) && $query['todate'] != '') {
            $todate = str_replace('/', '-', $query['todate']);
            $todate = date('Y-m-d H:i:s', strtotime($todate. "+1 days"));
            $all_data = $all_data->where('report_bugs.bug_posted_on', '<=', $todate);
        }
        $result = $all_data->get();
        return $result;
    }


}
