<?php

namespace App;
namespace App\model;
use App\model\customer;
use App\model\User;
use helper;
use Illuminate\Database\Eloquent\Model;
use DB;
class Onboarding_document extends Model
{    
//    protected $fillable = [
//        
//    ];

    public $timestamps  = false;
    protected $guarded = ['id'];

    public static function add_onboard_info($insert_array) {
        $result = Onboarding_document::firstOrCreate($insert_array)->toArray();
        return $result;
    }
     public static function lead_customar_regno($id) {
        $result = customer::select('customers.registration_number')
                            ->leftjoin('leads as l', 'l.custom_id', '=', 'customers.id')
                            ->where('l.id', '=', $id)
                            ->first()->toArray();
            //helper::pre($result,1);
            return $result['registration_number'];
    }
    
   

}
