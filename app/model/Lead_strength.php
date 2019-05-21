<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

use helper;
use DB;

class Lead_strength extends Model
{
  public $timestamps = false;
  protected $table = 'lead_strengths' ;
  protected $fillable = ['loan_type','is_active','color_code','key_details'];

  public static function insert_data($loan_data) {
        $result = Lead_strength::firstOrCreate($loan_data)->toArray();
        return $result;
    }

    public static function update_data($loan_data) {

        $new = array(
            'loan_type' => $loan_data['loan_type'],
            'is_active' => $loan_data['is_active'],
            'color_code' => $loan_data['color_code'],
            'key_details' => $loan_data['key_details'],);
        $resp = Lead_strength::where('id', $loan_data['id'])->update($new);
        return $resp;
    }

    public static function delete_data($id)
  {
        $new = array(
            'is_active' => '0');
  	 $resp = Lead_strength::where('id', '=', $id)->update($new);
  	 return $resp;
  }
  public static function strengthlist()
  {
      $users = Lead_strength::select('*')
                 ->where('is_active', '=', '1')->orderBy('loan_type', 'ASC')->get();
      if ($users->count()) {
          $users = $users->toArray();
      }
      else
      {
        $users = array();
      }
      return $users;
  }

  public static function loanstatuschange($id,$status)
    {
        $resp = Lead_strength::where('id','=',$id)->update(['is_active' => $status]);
        return $resp;
    }

  public static function allstrengthlist($qty,$id='')
  {
    if($id == '')
    {
      $users = Lead_strength::select('*')->whereNotIn('id',[0,1])->orderBy('id', 'DESC')->limit($qty)->get()->toArray();
    }
    else
    {
      $users = Lead_strength::select('*')->where('id', '<', $id)->whereNotIn('id',[0,1])->orderBy('id', 'DESC')->limit($qty)->get()->toArray(); 
     // helper::pre($users,1);        
    }
      
      return $users;
  }

  
}
