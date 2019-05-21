<?php

namespace App;
namespace App\model;
use Illuminate\Database\Eloquent\Model;
class User_phone extends Model
{    
    public static function addUserphone($insert_array) {
        $result = User_phone::firstOrCreate($insert_array)->toArray();
        return $result;
    }
    
    public static function idWiseAllPhone($id) {
//        $result = User_Phone::where('user_id', $id)->get()->toArray();
        $result = User_Phone::whereUser_id($id)->get()->toArray(); 
        return $result;
    }

    public static function primaryphone($id) {
        $result = User_Phone::select('user_phone')->where('user_id','=',$id)->get()->first(); 
        if(!empty($result))
        {
            if ($result->count()) {
                $result = $result->toArray();
            }
            else
            {
                $result = array();
            }
        }
        else{
            $result = array();
        }
        return $result;
    }

    protected $fillable = [
        'user_id', 'user_phone'
    ];

   public $timestamps  = false;
   protected $guarded = ['id'];
}
