<?php

namespace App;
namespace App\model;
use Illuminate\Database\Eloquent\Model;
class User_email extends Model
{    
    public static function addUserEmail($insert_array) {
        $result = user_email::firstOrCreate($insert_array)->toArray();
        return $result;
    }
    
     public static function idWiseAllEmail($id) {
        //$result = User_email::where('user_id', $id)->get()->toArray();
        $result = User_email::whereUser_id($id)->get()->toArray(); 
        return $result;
    }

    public static function primaryemail($id) {
        $result = User_email::select('user_email')->where('user_id','=',$id)->where('is_primary','=','1')->get()->first(); 
        if ($result->count()) {
            $result = $result->toArray();
        }
        else{
            $result = array();
        }
        return $result;
    }

    protected $fillable = [
        'user_id', 'user_email', 'is_primary'
    ];

   public $timestamps  = false;
   protected $guarded = ['id'];
}
