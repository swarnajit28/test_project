<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\Model;
use App\model\Lead;
use App\model\Lead_activity;
use App\model\Map_customer_salesperson;
use App\model\User_email;
use App\model\User_phone;
use helper;
use Auth;
use DB;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name', 'username', 'password', 'email', 'user_type', 'is_active', 'last_login_timestamp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function update_last_login_time($id) {
        $time = date('Y-m-d H:i:sa');
        $users = User::where('id', $id)->update(['last_login_timestamp' => $time]);
    }

    public static function selectuser($usertype) {
        $users = User::select('id', 'display_name')
                        ->where('user_type', '=', $usertype)->orderBy('display_name', 'ASC')->get();

        if ($users->count()) {
            $users = $users->toArray();
        }
        return $users;
    }

    public static function eachuser($id) {
        //echo $id;exit;
        $users = User::select('display_name', 'user_type')->where('id', '=', $id)->get()->first(); //exit;

        if ($users->count()) {
            $users = $users->toArray();
        }
        return $users;
    }

    public static function get_email_list($id) {
        $email = DB::table('user_emails')->where('user_id', $id)->orderby('is_primary', 'DESC')->get();
        return $email;
        // dd($email);
    }

    public static function set_primary_email($uid, $id) {
        $data = DB::table('user_emails')->where('id', $id)->get();
        //echo $data[0]->user_email; die();
        //  helper::pre($data,1);
        $resp1 = DB::table('user_emails')->where('user_id', $uid)
                        ->where('is_primary', '1')->update(['is_primary' => '0']);
        if ($resp1 > 0) {
            $resp2 = DB::table('user_emails')->where('id', $id)->update(['is_primary' => '1']);
            if ($resp2 > 0) {
                $upd_array = array("username" => $data[0]->user_email, "email" => $data[0]->user_email);
                $resp3 = User::where('id', $uid)->update($upd_array);
                $resp = $resp3;
            } else {
                $resp = $resp2;
            }
        } else {
            $resp = $resp1;
        }
        return $resp;
    }

    public static function get_phone_list($id) {
        $data = DB::table('user_phones')->where('user_id', $id)->get();
        return $data;
    }

    public static function get_all_user($post = array()) {
        $searchuser = User::select('*');
        // dd($post);

        if (isset($post['role']) && ($post['role'] != '')) {
            $searchuser->where('user_type', '=', $post['role']);
        }
        if (isset($post['status']) && ($post['status'] != '')) {
            $searchuser->where('is_active', '=', $post['status']);
        }
        if (isset($post['display_name']) && ($post['display_name'] != '')) {
            $searchuser->where('display_name', 'LIKE', '%' . $post['display_name'] . '%');
        }
        if (isset($post['username']) && ($post['username'] != '')) {
            $searchuser->where('username', 'LIKE', '%' . $post['username'] . '%');
        }
        if (isset($post['perPage']) && ($post['perPage'] != '')) {
            $qty = $post['perPage'];
        }

        $data = $searchuser->orderBy('id', 'DESC')->limit($qty)->get();
        // helper::pre($data,1);
        return $data;
    }

    public static function getajaxuserlist($post, $id, $items_per_page) {
        $searchuser = User::select('*');
        // dd($post);
        $result = '';
        if (isset($post['role']) && ($post['role'] != '')) {
            $searchuser->where('user_type', '=', $post['role']);
        }
        if (isset($post['status']) && ($post['status'] != '')) {
            $searchuser->where('is_active', '=', $post['status']);
        }
        if (isset($post['display_name']) && ($post['display_name'] != '')) {
            $searchuser->where('display_name', 'LIKE', '%' . $post['display_name'] . '%');
        }
        if (isset($post['username']) && ($post['username'] != '')) {
            $searchuser->where('user_name', 'LIKE', '%' . $post['username'] . '%');
        }

        $result = $searchuser->where('id', '<', $id)->orderBy('id', 'DESC')->limit($items_per_page)->get();
        return $result;

        //$data = $searchuser->offset($offset)->limit($qty)->get();
        // helper::pre($data,1);
    }

    public static function get_user_details($id) {
        /*  $data = User::select('users.*','user_emails.*','user_phones.user_phone')
          ->rightjoin('user_emails','users.id','=','user_emails.user_id')
          ->rightjoin('user_phones','users.id','=','user_phones.user_id')
          ->where('users.id','=',$id)->get()->toArray(); */
        $data['user'] = User::select('*')->where('id', '=', $id)->get()->toArray();
        $data['emails'] = DB::table('user_emails')->select('*')->where('user_id', '=', $id)->orderby('is_primary', 'DESC')->get()->toArray();
        $data['phones'] = DB::table('user_phones')->select('*')->where('user_id', '=', $id)->get()->toArray();
        // dd($data);
        return $data;
    }

    public static function update_user_details($data, $id) {
        // dd($data);
        $resp = User::where('id', '=', $id)->update($data);
        return $resp;
    }

    public static function delete_user_email($email_id) {
        $resp = DB::table('user_emails')->where('id', '=', $email_id)->delete();
        return $resp;
    }

    public static function delete_user_phone($phone_id) {
        // echo $phone_id; die();
        $resp = DB::table('user_phones')->where('id', '=', $phone_id)->delete();
        return $resp;
    }

    public static function verify_email($eid) {
        $resp = DB::table('user_emails')->where('id', '=', $eid)->update(['is_verified' => 1]);
        return $resp;
    }

    public static function verify_email_user($eid) {
        $email = DB::table('user_emails')->select('user_id')->where('id', '=', $eid)->get()->toArray();
        // helper::pre($email,1);
        $resp = DB::table('user_emails')->where('id', '=', $eid)->update(['is_verified' => 1]);
        if (count($email) > 0) {
            $user = User::where('id', '=', $email[0]->user_id)->update(['is_active' => 1]);
            return $user;
        } else {
            return $resp;
        }
    }

    public static function activeUser() {
        $activeUser = User::select('id', 'display_name')->where('is_active', 1)->get()->toArray();
//        $activeUser->where('is_active',1);
//        
//        $users = User::where('is_active',1)->get()->toArray();
        return $activeUser;
    }

    public static function user_change_status($id, $status) {
        $resp = User::where('id', '=', $id)->update(['is_active' => $status]);

        if ($status == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /*    public static function login_check($condition)
      {
      $user = User::select('users.*','user_emails.is_verified')->join('user_emails','users.id','=','user_emails.user_id')->where('users.username','Like','%'.$condition['username'].'%')->get()->toArray();
      foreach($condition as $key => $value)
      {
      if($key == 'username')
      {
      $user->where('users.username','=',$value);
      }
      else if($key == 'password')
      {
      $pass = Hash::make($value);
      $user->where('users.password','=',$pass);
      }
      else if($key == 'is_active')
      {
      $user->where('users.is_active','=',$value);
      }
      }
      $data = $user->where('user_emails.is_verified','=','1')->get()->toArray();
      //helper::lastQuery(1);
      helper::pre($user,1);
      //  return $data ;
      } */

    public static function get_all_sp($post = array()) {
        $searchuser = User::select('*');

        if (isset($post['status']) && ($post['status'] != '')) {
            $searchuser->where('is_active', '=', $post['status']);
        }
        if (isset($post['display_name']) && ($post['display_name'] != '')) {
            $searchuser->where('display_name', 'LIKE', '%' . $post['display_name'] . '%');
        }
        if (isset($post['username']) && ($post['username'] != '')) {
            $searchuser->where('username', 'LIKE', '%' . $post['username'] . '%');
        }
        if (isset($post['perPage']) && ($post['perPage'] != '')) {
            $qty = $post['perPage'];
        }

        $data = $searchuser->where('user_type', '=', 'SP')->orderBy('id', 'DESC')->limit($qty)->get();
        // helper::pre($data,1);
        return $data;
    }

    public static function getajaxsplist($post, $id, $items_per_page) {
        $searchuser = User::select('*');
        // dd($post);
        $result = '';
        if (isset($post['status']) && ($post['status'] != '')) {
            $searchuser->where('is_active', '=', $post['status']);
        }
        if (isset($post['display_name']) && ($post['display_name'] != '')) {
            $searchuser->where('display_name', 'LIKE', '%' . $post['display_name'] . '%');
        }
        if (isset($post['username']) && ($post['username'] != '')) {
            $searchuser->where('user_name', 'LIKE', '%' . $post['username'] . '%');
        }

        $result = $searchuser->where('user_type', '=', 'SP')->where('id', '<', $id)->orderBy('id', 'DESC')->limit($items_per_page)->get();
        return $result;

        //$data = $searchuser->offset($offset)->limit($qty)->get();
        // helper::pre($data,1);
    }

    public static function lastSP() {
        //echo "1";exit;
        $sp_detail = User::select('id')->where('user_type', '=', 'SP')->orderBy('id', 'ASC')->limit('1')->get()->first();
        //print_r($sp_detail);exit;
        if (!empty($sp_detail)) {

            if ($sp_detail->count()) {
                $id = $sp_detail->toArray();
            } else {
                $id = array();
            }
        } else {
            $id = array();
        }
        //print_r($id);exit;
        return $id;
    }

    public static function count_active_sales_person() {
        $data = User::select(DB::raw('COUNT(*) as sales_person'))
                        ->where('user_type', '=', 'SP')
                        ->where('is_active', '=', '1')->first()->toArray();
        return $data;
    }

    public static function get_all_sales_person() {
        $data = User::select('*')->where('user_type', '=', 'SP')->where('is_active', '=', '1')->orderby('display_name', 'ASC')->get()->toArray();

        /* $data = User::select('id','display_name')->where('user_type','=','SP')->where('is_active','=','1')->get()->toArray(); */

        return $data;
    }

    public static function get_customer_by_id($id) {
        $data = User::select('id', 'display_name')->where('id', '=', $id)->first()->toArray();
        return $data;
    }

    public static function all_sales_person() {
        $data = User::select('*')->where('user_type', '=', 'SP')->orderby('display_name', 'ASC')->get()->toArray();
        return $data;
    }

    public static function selectactiveuser($usertype) {
        $users = User::select('id', 'display_name')
                        ->where('user_type', '=', $usertype)->where('is_active', '=', '1')->orderBy('display_name', 'ASC')->get();

        if ($users->count()) {
            $users = $users->toArray();
        }
        return $users;
    }

    public static function delete_user($id) {
        $sp = Lead::checkSPUser($id);
        $updateBy = Lead::checkUpdatedBy($id);
        $leadactivity = Lead_activity::checkActivity($id);
        $mapSP = Map_customer_salesperson::checkSalePerson($id);

        if ($sp == 0 && $updateBy == 0 && $leadactivity == 0 && $mapSP == 0) {
            User_phone::where('user_id', '=', $id)->delete();
            User_email::where('user_id', '=', $id)->delete();
            $res = User::where('id', '=', $id)->delete();
            if ($res) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public static function checkactiveuser($email) {
        $arr = User::select('id', 'is_active')
                        ->where('username', $email)
                        ->get()->toArray();
        if (is_array($arr) && count($arr) > 0) {
            //helper::pre($arr);exit;   
            //return "Y";
            if ($arr[0]['is_active'] == 0) {
                return "NY";
            } else {
                return "Y";
            }
        } else {
            return "N";
        }
    }

    public static function getSalepersonEmail($id) {
        $email = User::select('users.email')
                        ->leftjoin('map_customer_salespersons as csp', 'csp.user_id', '=', 'users.id')
                        ->where('csp.customer_id', '=', $id)->first()->toArray();
        //helper::pre($email);exit;  
        return $email;
    }

    public static function get_all_MA_Email() {
        $data = User::select('email')->where('user_type', '=', 'MA')->where('is_active', '=', '1')->orderby('display_name', 'ASC')->get()->toArray();
        return $data;
    }

    public static function get_all_OM() {
        $data = User::select('id', 'display_name', 'email')->where('user_type', '=', 'OM')->where('is_active', '=', '1')->orderby('display_name', 'ASC')->get()->toArray();
        return $data;
    }

    public static function get_all_OM_onboardemail() {
        $data = User::select('users.id', 'users.display_name', 'users.email')
                        ->join('onboarding_operation_managements as opm', 'opm.user_id', '=', 'users.id')
                        ->orderby('users.display_name', 'ASC')->get()->toArray();
        //helper::pre($data);exit;
        return $data;
    }
public static function get_sp_by_id($id) {
        $data = User::select('*')->where('id', '=', $id)->first()->toArray();
        return $data;
    }
    public static function get_sp_by_name($name) {
        $data = User::select('id')->where('display_name', '=', $name)->first();
        if (count($data)) {
            $data = $data->toArray();
            return $data['id'];
        } else {
            //helper::pre($data,1);
            return 0;
        }
    }

}
