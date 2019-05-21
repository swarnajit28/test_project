<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Validator;
use helper;
use Hash;
use Auth;
use App\User;
use App\model\User_email;
use App\model\User_phone;
use Illuminate\Contracts\Encryption\DecryptException;

use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Mail;
use App\Mail\EmailVerification;
use App\Mail\sendUserRegistrationMail;

class UserManageController extends Controller
{
    public function __construct() {
        DB::enableQueryLog();
//       $this->middleware('MA');
        $this->items_per_page = Config::get('formArray.items_per_page');
       // $this->items_per_page = '4'; 
    }

    public function add_User(Request $request) {
        $formfield = helper::getFormFields("adduser");
        $encrypted = helper::encryptForm($formfield);
         //helper::pre($encrypted,1); 
        $p_data = array(
                            'id' =>'',
                            'display_name' => '',
                            'email' =>'',
                            'phone' => '',
                            'role' =>'',
                            'status' => '',
                        );
        return view('adduserprofile', compact('encrypted','p_data')); 
        
    }
 public function store(Request $request) {
        $decrypt_id = '';
        $allRequest = $request->all();
      //  helper::pre($allRequest,0); 
        $white_lists = helper::getFormFields("adduser");
        $ignore_keys = array('_token');
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        //helper::pre($post_data,1); 
        $rule = [
            'name' => 'required|min:3',
            'email.*' => 'required|email|unique:user_emails,user_email,',
            'phone.*' => 'required|numeric|unique:user_phones,user_phone,',
            'role' => 'required',
            'status' => 'required|numeric',
        ];
        $validator = Validator::make($post_data, $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $rand = rand(100000000,999999999);
        $insert_user = array(
            'display_name' => $post_data['name'],
            'username' => $post_data['email'][0],
            'password' => Hash::make($rand),
            'email' => $post_data['email'][0],
            'user_type' => $post_data['role'],
            'is_active' => '0',
        );
       // helper::pre($insert_user,1); 
        $result = User::firstOrCreate($insert_user);
     //   helper::pre($result,1);

        $insert_id = $result['id'];
        if ($insert_id != '') {
            foreach ($post_data['email'] as $key => $value) {
                if ($key == '0') {
                    $is_primary = 1;
                } else {
                    $is_primary = 0;
                }
                $insert_email = array(
                    'user_id' => $insert_id,
                    'user_email' => $value,
                    'is_primary' => $is_primary,
                );
                $return = User_email::addUserEmail($insert_email);
                if($return != '')
                {
                    if($insert_email['is_primary'] == '1')
                    {
                        $this->sendMail($value,$result,$rand,$return['id']);
                    }
                    else
                    {
                        $this->send_verification_mail($value,$return['id']);
                    }

                }
            }
            foreach ($post_data['phone'] as $key => $value) {
                if ($key == '0') {
                    $is_primary = 1;
                } else {
                    $is_primary = 0;
                }
                $insert_phone = array(
                    'user_id' => $insert_id,
                    'user_phone' => $value,
                );
                $return = User_phone::addUserPhone($insert_phone);
            }
        }
        if ($result['id'] != 'null' && !empty($result)) {
            return redirect('/addNewUser')->with('success_message', 'New User Added Successfully!');
        }
    }

    public function checkEmailExist(Request $request) {
        $allRequest = $request->all();
        $email = $request->input('email');
        if($email != ' ')
        {
            $rule = [
                'email' => 'unique:user_emails,user_email,',
            ];
        }
        else
        {
           $rule = [
                'email' => 'required',
            ]; 
        }
        $validator = Validator::make($allRequest, $rule);
        if ($validator->fails()) {
            echo 'Y';
        } else {
            echo 'N';
        }
    }

    public function checkPhoneExist(Request $request) {
        $allRequest = $request->all();
        $phone = $request->input('phone');
        if($phone != ' ')
        {
            $rule = [
                'phone' => 'unique:user_phones,user_phone,',
            ];
        } else {
            $rule = [
                'phone' => 'required',
            ];
        }
        $validator = Validator::make($allRequest, $rule);
        if ($validator->fails()) {
            echo 'Y';
        } else {
            echo 'N';
        }
    }
    
    public function viewUserProfile() {
        $data_array = array();
        $user = Auth::user()->toArray();
        $user_id = $user['id'];
        $user_email = User_email::idWiseAllEmail($user_id);
        $user_phone = User_Phone::idWiseAllPhone($user_id);
        foreach ($user_email as $key => $value) {
            $data_array['email'][] = $value['user_email'];
        }
        foreach ($user_phone as $key => $value) {
            $data_array['phone'][] = $value['user_phone'];
        }
        $data_array['display_name'] = $user['display_name'];
        $data_array['user_id'] = $user_id;
        $data_array['user_type'] = $user['user_type'];
        //helper::pre($user, 0);
        return view('view_user_profile', compact('data_array'));
    }

    public function user_change_password_view()
    {
        $user = Auth::user();
        //dd($user);
        $formfield      = helper::getFormFields("userchange_password");
        $encrypted  = helper::encryptForm($formfield);  
       // dd($encrypted);
        return view('user_change_password',compact('user','encrypted'));
    }

    public function save_user_change_password(Request $request)
    {
        $user = Auth::user();
    //  dd($user);
        $allRequest = $request->all();
        
       //  helper::pre($allRequest,0);
        $white_lists = helper::getFormFields("userchange_password"); 
        // helper::pre($white_lists,0); 
   
        $ignore_keys                     = array('_token');
        $post_data                       = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        // dd($post_data);
        $rule = [
                    'old_password'  => 'required',
                    'password'  => 'required|confirmed',
                    'password_confirmation' => 'required',
        ] ;

        $validator = Validator::make($post_data, $rule); 
        if ($validator->fails()) {
            //echo '1'; die();
            $p_data = [ 'old_password' => $post_data['old_password'], 
                        'password' => $post_data['password'],
                        'password_confirmation' => $post_data['password_confirmation'],
            ];
            return redirect()->back()->withErrors($validator)->with('status',$p_data);
        }
        else 
        {
             if($post_data['old_password'] != $post_data['password'])
             {
                if (Hash::check($post_data['old_password'], $user->password)) {
                        $user_id            = $user->id; //echo $user_id;die();
                        $obj_user           = User::find($user_id);
                        $obj_user->password = Hash::make($post_data['password']);
                        $obj_user->save();
                        return redirect('/user_change_password')->with('success_message', "Password changed successfully please login with new password"); 
                    } else {
                        //echo '3'; die();
                        return redirect('/user_change_password')->with('success_message', "The password you have entered does not match your current one."); 
                    }
            } else {
                return redirect('/user_change_password')->with('success_message', "Old Password and New Password should not be same.");
            } 
        }

    }

    public function view_profile()
    {
        $user = Auth::user();
        $emails = User::get_email_list($user->id);
        $phones = User::get_phone_list($user->id);
        return view('view_profile',compact('user','emails','phones'));
    }

    

    public function set_primary_email()
    {
        $id = Crypt::decrypt($_POST['id']);
        $uid = Crypt::decrypt($_POST['user_id']);
        //echo $id.'///'.$uid ; die();
        $resp = User::set_primary_email($uid,$id) ;
        if($resp > 0)
        {
            return $resp;
        } 
    }

    public function user_list(Request $request)
    {
        $user = Auth::user();
        $postArr['display_name'] = '';
        $postArr['status'] = '';
        $postArr['role'] = '';
        $postArr['username'] = '' ;
        $postArr['perPage']  = $this->items_per_page;

        if($request->has('display_name')){
            $postArr['display_name'] = $request->input('display_name');
        }
        if($request->has('status')){
            $postArr['status'] = $request->input('status');
        }
        if($request->has('role')){
            $postArr['role'] = $request->input('role');
        }
        if($request->has('username')){
            $postArr['username'] = $request->input('username');
        }

        $user_list = User::get_all_user($postArr);

       // helper::pre($data,1);
        return view('userlist',compact('user','user_list','postArr'));
    }

    public function loaduser(Request $request) {
        $output = '';
        $id = $request->input('id');
       // dd($id);
        $postArr['display_name'] = '';
        $postArr['status'] = '';
        $postArr['role'] = '';
        $postArr['username'] = '';


        if($request->has('display_name')){
            $postArr['display_name'] = $request->input('display_name');
        }
        if($request->has('status')){
            $postArr['status'] = $request->input('status');
        }
        if($request->has('role')){
            $postArr['role'] = $request->input('role');
        }
        if($request->has('username')){
            $postArr['username'] = $request->input('username');
        } 
     

        //print_r($posts);
        $posts = User::getajaxuserlist($postArr,$id,$this->items_per_page);
        $output = '';
       // helper::pre($posts,1);
        if (!$posts->isEmpty()) 
        {           
            foreach ($posts as $post) 
            {
                $change_status = 'onclick="change_user_status('.$post->is_active.','.$post->id.');"';
            //    $cnt = $cnt+count($posts);
                  $output .='<tr>
              <td>'.$post->display_name.'</td>
              <td>'.$post->username.'</td>';;
              if($post->user_type == 'IT')
              {
                $utype = 'IT';
              }
              if($post->user_type == 'MA')
              {
                $utype = 'Management';
              }
              if($post->user_type == 'LM')
              {
                $utype = 'Lead Manager';
              }
              if($post->user_type == 'SP')
              {
                $utype = 'Sales Person';
              }
         //     $output .='<td>'.$utype.'</td><td id="stat'.$post["id"].'" class="text-center">';
              $output .='<td>'.$utype.'</td><td class="text-center" style="cursor:pointer;" id="stat'.$post["id"].'">';

              if($post->is_active == '1')
              {
                $output .= '<i class="fa fa-check" aria-hidden="true" title="active" '.$change_status.'></i>' ;
              }
              else
              {
                $output .= '<i class="fa fa-times" aria-hidden="true" title="Inactive" '.$change_status.'></i>' ;
              }
              $message = "'".'Are you sure want to delete this user?'."'";
                $output .= '</td><td class="text-center viewgrp-dropdown dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">
                      <li><a href="'.route('edit_profile',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
                      <li><a href="'.route('user-details',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                      <li><a onclick="return confirm('.$message.')" href="'.route('delete-user',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                    </ul>
                  </td>
                </tr>';           
            }
            echo $output;
        }
    }

    public function edit_profile($uid)
    {
        $user = Auth::user();
        $id = Crypt::decrypt($uid);
        $formfield = helper::getFormFields("edituser");
        $encrypted = helper::encryptForm($formfield);
         //helper::pre($encrypted,1); 
        $p_data = array(
                            'id' =>'',
                            'display_name' => '',
                            'email' =>'',
                            'phone' => '',
                            'role' =>'',
                            'status' => '',
                        );
        $user_details = User::get_user_details($id);
    /*  foreach($user_details as $key => $value)
        {
            echo $value.'<br>';
        } */
        $users = $user_details['user'];
        $emails = $user_details['emails'];
        $phones = $user_details['phones'];
   /*     print_r($users);echo '<br>';
        print_r($emails);echo '<br>';
        print_r($phones);echo '<br>';  */
  //      helper::pre($user_details,1); 
        return view('editprofile',compact('users','user','emails','phones','encrypted','p_data'));
    }

    public function update_user_details(Request $request)
    {
        $user = Auth::user();
        $allRequest = $request->all();
     //   helper::pre($allRequest,0); 
        $white_lists = helper::getFormFields("edituser");
        $ignore_keys = array('_token');
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
         $uid = Crypt::decrypt($post_data['user_id']);
      // helper::pre($post_data,0);
       if($user->id != $uid)
       {
        $rule = [
            'name' => 'required|min:3',
            'email.*' => 'email|unique:user_emails,user_email,',
            'phone.*' => 'numeric|unique:user_phones,user_phone,',
            'role' => 'required',
            'status' => 'required|numeric',
        ];
       }
       else
       {
         $rule = [
            'name' => 'required',
            'email.*' => 'email|unique:user_emails,user_email,',
            'phone.*' => 'numeric|unique:user_phones,user_phone,',
        ];
       }
        $validator = Validator::make($post_data, $rule);
        if ($validator->fails()) {
            //dd($validator);
            return redirect()->back()->withErrors($validator);
        }
        if($user->id != $uid)
        {
            $insert_user = array(
                'display_name' => $post_data['name'],
                'user_type' => $post_data['role'],
                'is_active' => $post_data['status'],
            );
        }
        else
        {
            $insert_user = array(
                'display_name' => $post_data['name'],
            ); 
        }
       
      //  echo $uid; die();
        $result = User::update_user_details($insert_user,$uid);
        $insert_id = $uid;
        if ($insert_id != '') {
            if(isset($post_data['email']))
            {
                foreach ($post_data['email'] as $key => $value) {
                    
                    $is_primary = 0;
                    
                    $insert_email = array(
                        'user_id' => $insert_id,
                        'user_email' => $value,
                        'is_primary' => $is_primary,
                    );
                    $return = User_email::addUserEmail($insert_email);
                    
                }
            }
            if(isset($post_data['phone']))
            {
                foreach ($post_data['phone'] as $key => $value) {
                   
                        $is_primary = 0;
                    
                    $insert_phone = array(
                        'user_id' => $insert_id,
                        'user_phone' => $value,
                    );
                    $return = User_phone::addUserPhone($insert_phone);
                }
            }
        }
        if ($result>0) {
            return redirect("/edit_profile/".$post_data['user_id'])->with('success_message', 'User Details Updated Successfully!');
        }

    }

    public function delete_user_email(Request $request)
    {
        $allRequest = $request->all();
      //  $uid = Crypt::decrypt($request->input('uid'));
        $id = $request->input('id');
       // echo $uid.'///'.$id ; die();
        $resp = User::delete_user_email($id);
        if($resp > 0)
        {
            return $resp;
        } 
    }

    public function delete_user_phone(Request $request)
    {
        $allRequest = $request->all();
        //helper::pre($allRequest,1); 
        $id = $request->input('id');
      //  $resp1 = User::findorfail($id);
       // helper::pre($resp1,0);
         $resp = User::delete_user_phone($id);
            if($resp > 0)
            {
                return $resp;
            } 
            else
            {
                return 0;
            }
     /*   if($resp1 != '')
        {
            $resp = User::delete_user_phone($id);
            if($resp > 0)
            {
                return $resp;
            } 
        }
        else
        {
            return 0;
        } */
    }



    public function sendverificationmail(Request $request)
    {
        $allRequest = $request->all();
     //   helper::pre($allRequest,1); 
        $emailid = $_POST['email_id'];
        $eid = $_POST['eid'];
         $content = [
                        'subject'=> "Email Verification",
                        'eid' => $eid,                  
                    ];
        $mailstatus = Mail::to($emailid)->send(new EmailVerification($content));
      //  dd($mailstatus);
      //  helper::pre($mailstatus,1); 
        return $mailstatus;
    }

    

    public function sendMail($mail,$user,$pass,$eid)
    {
       // helper::pre($user,1);
       
        $content = [
                        'subject' => "User Registration",
                        'user' => $user,
                        'password' => $pass,
                        'eid' => $eid,
                    ];
        $mailstatus = Mail::to($mail)->send(new sendUserRegistrationMail($content));
    }

    public function verify_email($eid)
    {
        $mailid = Crypt::decrypt($eid);
        $resp = User::verify_email($mailid);
        //dd($resp);
        if($resp > 0)
        {
            return view('verificationMail');
        } 
    }

    public function verify_mail_user($eid)
    {
        $mailid = Crypt::decrypt($eid);
        $resp = User::verify_email_user($mailid);
        //dd($resp);
        if($resp > 0)
        {
            return view('verificationMail');
        } 
    }

    public function userstatuschange(Request $request)
    {
      //  $output = '' ;
        $id  = $request->input('id');
        $status = $request->input('status');
        $response = User::user_change_status($id,$status);
      //  helper::pre($response,1);
        $newid = "'".$request->input('id')."'";
        $newstat = "'".$response."'";
        $newrow = "'".$id."'";
        if($response == 1)
        {
            $output = '<i class="fa fa-check" aria-hidden="true" onclick="change_user_status('.$newstat.','.$newid.');" title="inactive"></i>';
            
        }
        elseif($response == 0)
        {
            $output = '<i class="fa fa-times" aria-hidden="true" onclick="change_user_status('.$newstat.','.$newid.');" title="active"></i>';
        }
        //echo $output; die();
        return $output ;

    }


    public function splist(Request $request)
    {
        $user = Auth::user();
        $postArr['display_name'] = '';
        $postArr['status'] = '';
        $postArr['username'] = '' ;
        $postArr['perPage']  = $this->items_per_page;

        if($request->has('display_name')){
            $postArr['display_name'] = $request->input('display_name');
        }
        if($request->has('status')){
            $postArr['status'] = $request->input('status');
        }
        if($request->has('username')){
            $postArr['username'] = $request->input('username');
        }

        $user_list = User::get_all_sp($postArr);

       // helper::pre($data,1);
        $id = User::lastSP();
        if(count($id))
        {              
            $lastsp = $id['id'];
        }
        else
        {
            $lastsp = '';
        }
        //echo $lastsp;exit;
        return view('splist',compact('user','user_list','postArr','lastsp'));
    }

    public function loadsplist(Request $request) {
        ///print_r($request->all());exit;
        $output = '';
        $id = $request->input('id');
        $postArr['display_name'] = '';
        $postArr['status'] = '';
        $postArr['username'] = '';


        if($request->has('display_name')){
            $postArr['display_name'] = $request->input('display_name');
        }
        if($request->has('status')){
            $postArr['status'] = $request->input('status');
        }
        if($request->has('username')){
            $postArr['username'] = $request->input('username');
        } 
     

        //print_r($posts);
        $posts = User::getajaxsplist($postArr,$id,$this->items_per_page);
        $output = '';
       // helper::pre($posts,1);
        if (!$posts->isEmpty()) 
        {           
            foreach ($posts as $post) 
            {
                $isactive = "'".$post->is_active."'";
                $listid = "'".$post->id."'";
                  $output .='<tr id="'.$post->id.'">
              <td>'.$post->display_name.'</td>
              <td>'.$post->username.'</td>';
              
              if($post->is_active == '1')
              {
                $output .= '<td class="text-center" id="stat'.$post->id.'"><button class="tick" onclick="change_user_status('.$isactive.','.$listid.');"><i class="fa fa-check" aria-hidden="true" title="Active"></i></button></td>' ;
              }
              else
              {
                $output .= '<td class="text-center" id="stat'.$post->id.'"><button class="tick" onclick="change_user_status('.$isactive.','.$listid.');"><i class="fa fa-times" aria-hidden="true" title="Inactive"></i></button></td>' ;
              }
                $output .= '</td>
                  <td class="text-center">
                  <a class="table-link" href="'.route('list-sp-lead',['id' => Crypt::encrypt($post['id']) ]).'"><i class="fa fa-eye"  aria-hidden="true"></i> View Leads</a>                    
                  </td>
                </tr>';           
            }
            echo $output;
        }
    }  


    public function spstatuschange(Request $request)
    {
        $id  = $request->input('id');
        $status = $request->input('status');
        $response = User::user_change_status($id,$status);
        /*if($response > 0)
        {
            return $response ;
        }
        else
        {
            return 0;
        }*/
        $newid = "'".$id."'";
        $newstat = "'".$status."'";
        if($status==0)
        {
            $output = '<button class="tick" onclick="change_user_status('.$newstat.','.$newid.');"><i class="fa fa-close" aria-hidden="true" title="Inactive"></i></button>';
        }
        elseif($status==1)
        {
            $output = '<button class="tick" onclick="change_user_status('.$newstat.','.$newid.');"><i class="fa fa-check" aria-hidden="true" title="Active"></i></button>';
        }
        return $output;
    }

    public function send_verification_mail($email,$id)
    {
       $content = [
                        'subject'=> "Email Verification",
                        'eid' => $id,                  
                    ];
        Mail::to($email)->send(new EmailVerification($content));
     
    }

    public function deleteUser($id)
    {
        try 
        {
            $id =  decrypt($id);
        } 
        catch (DecryptException $e) 
        {
            return redirect('/list_lead');
        }
        $resp = User::delete_user($id) ; 
        if($resp==1)
        {
        return redirect('/user_list')->with('success_message', 'User deleted Successfully!');
        }
        else if($resp==0)
        {
        return redirect('/user_list')->with('error_message', 'Before delete user, you need to release this user from all activity.');
        }
    }
    
}
