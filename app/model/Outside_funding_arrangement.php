<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
use helper;
use App\model\customer;
use App\model\customer_contact_person;
use App\model\Map_customer_salesperson;
class Outside_funding_arrangement extends Model
{    
//    protected $fillable = [
//        
//    ];

    public $timestamps  = false;
    protected $guarded = ['id'];

    
     public static function client_details() {
        $result = customer::select('customers.id as customer_id','customers.company_name','u.display_name as sale_executive','u.id as sale_id')
                            ->leftjoin('map_customer_salespersons as mcs', 'mcs.customer_id', '=', 'customers.id')
                            ->leftjoin('users as u', 'u.id', '=', 'mcs.user_id')
                            ->where('mcs.is_executive_for_life', '=', 1)
                            ->get()->toArray();
            //helper::pre($result,1);
            return $result;
    }
    
    public static function funding_details(){
         $result = Outside_funding_arrangement::all()->toArray();
         return $result;
    }
    public static function funding_update($insert_array) {
        $result = Outside_funding_arrangement::updateOrCreate(
                        ['client_id' => $insert_array['client_id'],
                        'agreed_funding_terms' => $insert_array['agreed_funding_terms'],
                        'current_funding_position' => $insert_array['current_funding_position'],
                        'exposure_to_business' => $insert_array['exposure_to_the_business'],
                        'sales_executive_id' => $insert_array['sale_id']]);
        return $result;
    }
    
    public static function truncate_table(){
        DB::table('outside_funding_arrangements')->truncate();
    }
    public static function funding_list(){
         $result = Outside_funding_arrangement::select('outside_funding_arrangements.*','c.company_name as client_name','u.display_name as sale_executive')
                 ->leftjoin('customers as c', 'c.id', '=', 'outside_funding_arrangements.client_id')
                 ->leftjoin('users as u', 'u.id', '=', 'outside_funding_arrangements.sales_executive_id')
                 ->get()->toArray();
         //helper::pre($result,1);
         return $result;
    }

}
