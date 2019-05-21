<?php

namespace App\Http\Controllers;

use App\Mail\FAupdateMail;
use App\model\customer;
use App\model\customer_attachment;
use App\model\customer_contact_person;
use App\model\Map_customer_salesperson;
use App\model\Umbrella_querie;
use App\User;
use helper;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mail;
use Validator;

class Customercontroller extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();

        $this->items_per_page = Config::get('formArray.items_per_page');
        $this->FA_update_days = Config::get('formArray.FA_update_days');
    }
    public function add_customer(Request $request)
    {
        $model = helper::getFormFields("addcustomer");
        $formfield = helper::encryptForm($model);
        $customer_data = array(
            'id' => '',
            'company_name' => '',
            'is_active' => '',
            'registration_number' => '',
            'is_outside_FA' => '',
            'address _line_1' => '',
            'address _line_2' => '',
            'address _line_3' => '',
            'city' => '',
            'county' => '',
            'country' => '',
            'postal_code' => '',
        );
        $contact_details = array();
        $contact_details[0] = array(
            'id' => '',
            'custom_id' => '',
            'contact_person_name' => '',
            'contact_person_phone1' => '',
            'contact_person_phone2' => '',
            'contact_person_email1' => '',
            'contact_person_email2' => '',
            'contact_person_note' => '',
            'contact_person_job_title' => '',
            'contact_person_job_role' => '',
        );
        $attach_details = array();
        $attach_details[0] = array(
            'id' => '',
            'custom_id' => '',
            'customer_attachment_name' => '',
            'customer_attachment_file_name' => '',
        );
        $salesperson = User::get_all_sales_person();
        $sp = array('user_id' => '');
        return view('/add_customer', compact('formfield', 'customer_data', 'contact_details', 'attach_details', 'salesperson', 'sp'));
    }

    public function edit_customer($id)
    {
        try
        {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect('/manage_customer');
        }

        if (Auth::user()->user_type == 'SP') {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=', Auth::user()->id)->where('customer_id', '=', $id)->get()->toArray();
            if (empty($custom_ids)) {
                return redirect('/manage_customer');
            }
        }

        $model = helper::getFormFields("addcustomer");
        $formfield = helper::encryptForm($model);
        //$id  = Crypt::decrypt($id);
        $customer_data = customer::fetchcustomer($id);
        $is_customer_client = Map_customer_salesperson::check_is_executive_for_life($id);
        //helper::pre($is_customer_client,1);
        //helper::pre($id,1);
        if (count($customer_data) == 0) {
            $customer_data = array(
                'id' => '',
                'company_name' => '',
                'is_active' => '',
                'registration_number' => '',
                'is_outside_FA' => '',
                'address _line_1' => '',
                'address _line_2' => '',
                'address _line_3' => '',
                'city' => '',
                'county' => '',
                'country' => '',
                'postal_code' => '',
            );
        }
        $contact_details = customer_contact_person::contact_details($id);
        if (count($contact_details) == 0) {
            $contact_details[0] = array(
                'id' => '',
                'custom_id' => '',
                'contact_person_name' => '',
                'contact_person_phone1' => '',
                'contact_person_phone2' => '',
                'contact_person_email1' => '',
                'contact_person_email2' => '',
                'contact_person_note' => '',
                'contact_person_job_title' => '',
                'contact_person_job_role' => '',
            );
        }
        $attach_details = customer_attachment::attach_details($id);
        if (count($attach_details) == 0) {
            $attach_details[0] = array(
                'id' => '',
                'custom_id' => '',
                'customer_attachment_name' => '',
                'customer_attachment_file_name' => '',
            );
        }
        $salesperson = User::all_sales_person();
        $sp = Map_customer_salesperson::fetchSP($id);
        $nonExclusiveSp = Map_customer_salesperson::non_exclusive_sp($id, $sp);

        //$idWiseSp = User::get_sp_by_id($sp);
        //helper::pre($idWiseSp,0);
        //helper::pre($sp,1);
        // helper::pre($nonExclusiveSp,1);
        return view('/add_customer', compact('formfield', 'customer_data', 'contact_details', 'attach_details', 'salesperson', 'sp', 'nonExclusiveSp', 'is_customer_client'));

    }

    public function submitcustomer(Request $request)
    {
        $white_lists = helper::getFormFields("addcustomer");
        $ignore_keys = array('_token');
        $allRequest = $request->all();
        //helper::pre($allRequest,1);
        //helper::pre($white_lists);
        //helper::pre($ignore_keys,1);
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        $return_id = 0;
        //helper::pre($post_data,1);
        $file_data = helper::decryptForm($_FILES, $white_lists, $ignore_keys);

        $decrypt_id = 0;
        if ($post_data['id'] != '') {
            $decrypt_id = Crypt::decrypt($post_data['id']);
        }
        //helper::pre($post_data,1);
        if (!$post_data) {
            return "redirect page to 404";
        } else {
            if ($post_data['id'] == '') {
                $validationRules = [
                    'contact_person_email1.*' => 'unique:customer_contact_persons,contact_person_email1',
                    'registration_number' => 'unique:customers,registration_number',
                ];
                $validation = Validator::make($post_data, $validationRules);
                if ($validation->fails()) {
                    return redirect('/add_customer')->with('success_message', 'Duplicate Email Address! or Registration Number');
                }
            } /*else {*/
            //helper::pre($post_data, 1);
            $all_data = array();

            $all_data['company_name'] = isset($post_data['company_name']) ? $post_data['company_name'] : '';
            $all_data['is_active'] = isset($post_data['is_active']) ? $post_data['is_active'] : '1';
            $all_data['registration_number'] = isset($post_data['registration_number']) ? $post_data['registration_number'] : '';
            $all_data['is_outside_FA'] = isset($post_data['is_outside_FA']) ? $post_data['is_outside_FA'] : '2';
            $all_data['address _line_1'] = isset($post_data['address_line_1']) ? $post_data['address_line_1'] : '';
            $all_data['address _line_2'] = isset($post_data['address_line_2']) ? $post_data['address_line_2'] : '';
            $all_data['address _line_3'] = isset($post_data['address_line_3']) ? $post_data['address_line_3'] : '';
            $all_data['city'] = isset($post_data['city']) ? $post_data['city'] : '';
            $all_data['county'] = isset($post_data['county']) ? $post_data['county'] : '';
            $all_data['country'] = isset($post_data['country']) ? $post_data['country'] : '';
            $all_data['postal_code'] = isset($post_data['postal_code']) ? $post_data['postal_code'] : '';
            //helper::pre($all_data, 1);
            if ($decrypt_id == 0) {
                $return_id = customer::insertcustomer($all_data);
                $spID = isset($post_data['sales_person_id']) ? $post_data['sales_person_id'] : '';
                Map_customer_salesperson::insertSP($return_id, $spID);
            } elseif ($decrypt_id != 0) {
                $return_id = $all_data['id'] = $decrypt_id;
                $oldOutside_FA = customer::check_FA($all_data['id']);
                $newOutside_FA = $all_data['is_outside_FA'];
                customer::editcustomer($all_data);
                if ($oldOutside_FA != $newOutside_FA) {
                    $all_data['outside_FA_updated_on'] = time();
                    customer::update_FA($all_data['id'], $all_data['outside_FA_updated_on']);
                    $data = array();
                    //helper::pre($this->FA_update_days,1);
                    $data['date_from'] = date('Y-m-d', strtotime('-' . $this->FA_update_days . ' days'));
                    //helper::pre($data['date_from'],1);
                    $countFA = customer::countFAupdate($data['date_from']);
                    Session::put('outside_FA_updated_on', $countFA);
                    /***** email send *******/
                    //$user = Auth::user()->toArray();
                    $saleEmail = User::getSalepersonEmail($decrypt_id);
                    $mail = array();
                    array_push($mail, $saleEmail['email']);
                    $allMAemail = User::get_all_MA_Email();
                    //helper::pre($saleEmail, 0);
                    // helper::pre($allMAemail, 0);
                    foreach ($allMAemail as $key => $value) {
                        array_push($mail, $value['email']);
                    }
                    // helper::pre($mail, 1);
                    if ($newOutside_FA == 1) {
                        $status = 'Active';
                    }
                    if ($newOutside_FA == 2) {
                        $status = 'Inactive';
                    }
                    $content = [
                        'subject' => "Outside FA status change",
                        'company_name' => $all_data['company_name'],
                        'status' => $status,
                    ];
                    $mailstatus = Mail::to($mail)->send(new FAupdateMail($content));
                    /********           end   *******/
                }
                $spID = isset($post_data['sales_person_id']) ? $post_data['sales_person_id'] : '';
                Map_customer_salesperson::insertSP($return_id, $spID);
                //echo("sd");die();
            }
            //echo $decrypt_id;exit;
            if ($return_id != 0) {
                customer_attachment::deleteattachments($return_id);
                customer_contact_person::deletecontact($return_id);

                if (isset($post_data['contact_person_name'])) {
                    $data = array();
                    $data['custom_id'] = $return_id;
                    if (is_array($post_data['contact_person_name']) && count($post_data['contact_person_name'] > 0)) {
                        foreach ($post_data['contact_person_name'] as $key1 => $value1) {
                            $arr['contact_person_name'] = isset($post_data['contact_person_name'][$key1]) ? $post_data['contact_person_name'][$key1] : '';
                            $arr['contact_person_phone1'] = isset($post_data['contact_person_phone1'][$key1]) ? $post_data['contact_person_phone1'][$key1] : '';
                            $arr['contact_person_phone2'] = isset($post_data['contact_person_phone2'][$key1]) ? $post_data['contact_person_phone2'][$key1] : '';
                            $arr['contact_person_email1'] = isset($post_data['contact_person_email1'][$key1]) ? $post_data['contact_person_email1'][$key1] : '';
                            $arr['contact_person_email2'] = isset($post_data['contact_person_email2'][$key1]) ? $post_data['contact_person_email2'][$key1] : '';
                            $arr['contact_person_note'] = isset($post_data['contact_person_note'][$key1]) ? $post_data['contact_person_note'][$key1] : '';
                            $arr['contact_person_job_title'] = isset($post_data['contact_person_job_title'][$key1]) ? $post_data['contact_person_job_title'][$key1] : '';
                            $arr['contact_person_job_role'] = isset($post_data['contact_person_job_role'][$key1]) ? $post_data['contact_person_job_role'][$key1] : '';
                            array_push($data, $arr);
                        }
                        customer_contact_person::insertcontacts($data);
                    }
                }
                if (isset($post_data['customer_attachment_name'])) {
                    $data = array();
                    $data['custom_id'] = $return_id;
                    if (is_array($post_data['customer_attachment_name']) && count($post_data['customer_attachment_name'] > 0)) {
                        //print_r($post_data);
                        foreach ($post_data['customer_attachment_name'] as $key1 => $value1) {
                            $arr['customer_attachment_name'] = isset($post_data['customer_attachment_name'][$key1]) ? $post_data['customer_attachment_name'][$key1] : '';

                            $file_name = '';
                            if (isset($post_data['hide_attach'][$key1]) && $post_data['hide_attach'][$key1] != '0') {
                                $arr['customer_attachment_file_name'] = $post_data['hide_attach'][$key1];
                            } else if (isset($post_data['customer_attachment_file_name'][$key1]) && $post_data['hide_attach'][$key1] == '0') {
                                //echo 1;
                                $file_ext = pathinfo($file_data['customer_attachment_file_name']['name'][$key1], PATHINFO_EXTENSION);
                                $microsec = explode(".", microtime(true));
                                $file_name[$key1] = Auth::id() . "_" . date('Ymd_His') . $microsec[1] . "." . $file_ext;
                                $destination = "public/uploads/customer/" . $file_name[$key1];
                                move_uploaded_file($post_data['customer_attachment_file_name'][$key1], $destination);
                                $arr['customer_attachment_file_name'] = $file_name[$key1];
                            }

                            array_push($data, $arr);
                        } //exit;
                        //print_r($post_data);exit;
                        customer_attachment::insertattachments($data);
                    }
                }
            }
            //echo("sd");die();
            if ($decrypt_id == 0) {
                return redirect('/add_customer')->with('success_message', 'Customer Added Successfully!');
            } elseif ($decrypt_id != 0) {
                return redirect("/edit_customer/" . $post_data['id'])->with('success_message', 'Customer edited successfully!');
            }

            //}
        }
    }

    public function checkEmailExist(Request $request)
    {
        $email_address = $request->input('email');
        $valcheck = customer_contact_person::checkcustomer($email_address);
        if ($valcheck == 'Y') {
            echo 'Y';
        } else {
            echo 'N';
        }
    }

    public function listcustomer(Request $request)
    {
        $postArr['company_name'] = '';
        $postArr['registration_number'] = '';
        $postArr['contact_name'] = '';
        $postArr['phone'] = '';
        $postArr['email'] = '';
        $postArr['status'] = '2';
        $postArr['perPage'] = $this->items_per_page;
        $postArr['sales_person'] = '';

        if ($request->has('status')) {
            if ($request->input('status') != 2) {
                $postArr['status'] = $request->input('status');
            } else {
                $postArr['status'] = 2;
            }
        }

        if ($request->has('email')) {
            $postArr['email'] = $request->input('email');
        }

        if ($request->has('phone')) {
            $postArr['phone'] = $request->input('phone');
        }

        if ($request->has('contact_name')) {
            $postArr['contact_name'] = $request->input('contact_name');
        }

        if ($request->has('company_name')) {
            $postArr['company_name'] = $request->input('company_name');
        }

        if ($request->has('sales_person')) {
            $postArr['sales_person'] = $request->input('sales_person');
        }
        if ($request->has('registration_number')) {
            $postArr['registration_number'] = $request->input('registration_number');
        }

        $perPage = $this->items_per_page;
        $all_data = customer::customerlisting($postArr, $perPage);
        $salesperson = User::all_sales_person();
        //helper::pre($all_data,1);
        return view('/manage_customer', compact('all_data', 'postArr', 'perPage', 'salesperson'));
    }

    public function loadcustomer(Request $request)
    {
        $output = '';
        $text = "";
        $id = $request->input('id');

        $postArr['company_name'] = '';
        $postArr['registration_number'] = '';
        $postArr['contact_name'] = '';
        $postArr['phone'] = '';
        $postArr['email'] = '';
        $postArr['status'] = '2';
        $postArr['perPage'] = $this->items_per_page;
        $postArr['sales_person'] = '';

        if ($request->has('status')) {
            if ($request->input('status') != 2) {
                $postArr['status'] = $request->input('status');
            } else {
                $postArr['status'] = 2;
            }
        }

        if ($request->has('email')) {
            $postArr['email'] = $request->input('email');
        }

        if ($request->has('phone')) {
            $postArr['phone'] = $request->input('phone');
        }

        if ($request->has('contact_name')) {
            $postArr['contact_name'] = $request->input('contact_name');
        }

        if ($request->has('company_name')) {
            $postArr['company_name'] = $request->input('company_name');
        }

        if ($request->has('sales_person')) {
            $postArr['sales_person'] = $request->input('sales_person');
        }
        if ($request->has('registration_number')) {
            $postArr['registration_number'] = $request->input('registration_number');
        }

        $perPage = $this->items_per_page;
        //$all_data = customer::customerlisting($postArr,$perPage);

        $posts = customer::loadAjaxcustomer($postArr, $perPage, $id);
        //print_r($posts);
        //$posts = Product::where('id', '<', $id)->orderBy('id', 'DESC')->limit(1)->get();
        if (!$posts->isEmpty()) {
            foreach ($posts as $post) {
                if ($post['outside_FA_updated_on'] != '') {
                    // $status="bb";
                    $status = date('Y-m-d', strtotime($post['outside_FA_updated_on']));
                    $new_date = date('Y-m-d', strtotime('-7 days'));
                    if ($status > $new_date) {
                        $text = 'FA updated';
                    }
                } else {
                    $text = "";
                }
                $output .= '<tr>
                  <td>' . $post['company_name'] . '&nbsp; &nbsp; <span style="color:red">' . $text . '</span></td>
                  <td>' . $post['registration_number'] . '</td>
                  <td>' . $post['contact_person_name'] . '</td>
                  <td>' . $post['contact_person_phone1'] . '</td>
                  <td>' . $post['contact_person_email1'] . '</td>
                  <td>' . $post['totlead'] . '</td>';
                if ($post['is_active'] == '1') {
                    $id = "'" . Crypt::encrypt($post['id']) . "'";
                    $output .= ' <td class="text-center" id="stat' . $post['id'] . '"><button class="tick"  onclick="statuscustomer(' . $id . ',' . $post['is_active'] . ',' . $post['id'] . ');"><i class="fa fa-check" title="Active" aria-hidden="true"></i></button></td>';
                } else {
                    $id = "'" . Crypt::encrypt($post['id']) . "'";
                    $output .= '<td class="text-center" id="stat' . $post['id'] . '"><button class="tick" onclick="statuscustomer(' . $id . ',' . $post['is_active'] . ',' . $post['id'] . ');"><i class="fa fa-close" title="Inactive" aria-hidden="true" ></i></button></td>';
                }
                $output .= '
                  <td class="text-center viewgrp-dropdown dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">';
                $output .= '<li><a href="' . route('edit-customer', ['id' => Crypt::encrypt($post['id'])]) . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>';

                if (Auth::user()->user_type == 'SP') {
                    if ($post['is_active'] == '1') {
                        $output .= ' <li><a href="' . route('customer-add-lead', ['id' => Crypt::encrypt($post['id']), 'comp_name' => $post['company_name']]) . '"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Lead</a></li>';
                    }
                } else {
                    $output .= ' <li><a href="' . route('customer-add-lead', ['id' => Crypt::encrypt($post['id']), 'comp_name' => $post['company_name']]) . '"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Lead</a></li>';
                }

                if (Auth::user()->user_type == 'MA') {
                    $message = "'" . 'Are you sure want to delete this customer?' . "'";
                    $output .= '<li><a onclick="return confirm(' . $message . ')" href="' . route('delete-customer', ['id' => Crypt::encrypt($post['id'])]) . '"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>';
                }
                $output .= '</ul>
                  </td>
                </tr>';
            }
            echo $output;
        }
    }

    public function listclient(Request $request)
    {
        $postArr['company_name'] = '';
        $postArr['registration_number'] = '';
        $postArr['contact_name'] = '';
        $postArr['phone'] = '';
        $postArr['email'] = '';
        $postArr['status'] = '2';
        $postArr['perPage'] = $this->items_per_page;
        $postArr['sales_person'] = '';

        if ($request->has('status')) {
            if ($request->input('status') != 2) {
                $postArr['status'] = $request->input('status');
            } else {
                $postArr['status'] = 2;
            }
        }

        if ($request->has('email')) {
            $postArr['email'] = $request->input('email');
        }

        if ($request->has('phone')) {
            $postArr['phone'] = $request->input('phone');
        }

        if ($request->has('contact_name')) {
            $postArr['contact_name'] = $request->input('contact_name');
        }

        if ($request->has('company_name')) {
            $postArr['company_name'] = $request->input('company_name');
        }

        if ($request->has('sales_person')) {
            $postArr['sales_person'] = $request->input('sales_person');
        }
        if ($request->has('registration_number')) {
            $postArr['registration_number'] = $request->input('registration_number');
        }

        $perPage = $this->items_per_page;
        $all_data = customer::clientlisting($postArr, $perPage);
        $salesperson = User::all_sales_person();
        //helper::pre($all_data,1);
        return view('/manage_client', compact('all_data', 'postArr', 'perPage', 'salesperson'));
    }

    public function loadclient(Request $request)
    {
        $output = '';
        $text = "";
        $id = $request->input('id');

        $postArr['company_name'] = '';
        $postArr['registration_number'] = '';
        $postArr['contact_name'] = '';
        $postArr['phone'] = '';
        $postArr['email'] = '';
        $postArr['status'] = '2';
        $postArr['perPage'] = $this->items_per_page;
        $postArr['sales_person'] = '';

        if ($request->has('status')) {
            if ($request->input('status') != 2) {
                $postArr['status'] = $request->input('status');
            } else {
                $postArr['status'] = 2;
            }
        }

        if ($request->has('email')) {
            $postArr['email'] = $request->input('email');
        }

        if ($request->has('phone')) {
            $postArr['phone'] = $request->input('phone');
        }

        if ($request->has('contact_name')) {
            $postArr['contact_name'] = $request->input('contact_name');
        }

        if ($request->has('company_name')) {
            $postArr['company_name'] = $request->input('company_name');
        }

        if ($request->has('sales_person')) {
            $postArr['sales_person'] = $request->input('sales_person');
        }
        if ($request->has('registration_number')) {
            $postArr['registration_number'] = $request->input('registration_number');
        }

        $perPage = $this->items_per_page;
        //$all_data = customer::customerlisting($postArr,$perPage);

        $posts = customer::loadAjaxclient($postArr, $perPage, $id);
        //print_r($posts);
        //$posts = Product::where('id', '<', $id)->orderBy('id', 'DESC')->limit(1)->get();
        if (!$posts->isEmpty()) {
            foreach ($posts as $post) {
                if ($post['outside_FA_updated_on'] != '') {
                    // $status="bb";
                    $status = date('Y-m-d', strtotime($post['outside_FA_updated_on']));
                    $new_date = date('Y-m-d', strtotime('-7 days'));
                    if ($status > $new_date) {
                        $text = 'FA updated';
                    }
                } else {
                    $text = "";
                }
                $output .= '<tr>
                  <td>' . $post['company_name'] . '&nbsp; &nbsp; <span style="color:red">' . $text . '</span></td>
                  <td>' . $post['registration_number'] . '</td>
                  <td>' . $post['contact_person_name'] . '</td>
                  <td>' . $post['contact_person_phone1'] . '</td>
                  <td>' . $post['contact_person_email1'] . '</td>
                  <td>' . $post['totlead'] . '</td>';
                if ($post['is_active'] == '1') {
                    $id = "'" . Crypt::encrypt($post['id']) . "'";
                    $output .= ' <td class="text-center" id="stat' . $post['id'] . '"><button class="tick"  onclick="statuscustomer(' . $id . ',' . $post['is_active'] . ',' . $post['id'] . ');"><i class="fa fa-check" title="Active" aria-hidden="true"></i></button></td>';
                } else {
                    $id = "'" . Crypt::encrypt($post['id']) . "'";
                    $output .= '<td class="text-center" id="stat' . $post['id'] . '"><button class="tick" onclick="statuscustomer(' . $id . ',' . $post['is_active'] . ',' . $post['id'] . ');"><i class="fa fa-close" title="Inactive" aria-hidden="true" ></i></button></td>';
                }
                $output .= '
                  <td class="text-center viewgrp-dropdown dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">';
                $output .= '<li><a href="' . route('edit-customer', ['id' => Crypt::encrypt($post['id'])]) . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>';

                if (Auth::user()->user_type == 'SP') {
                    if ($post['is_active'] == '1') {
                        $output .= ' <li><a href="' . route('customer-add-lead', ['id' => Crypt::encrypt($post['id']), 'comp_name' => $post['company_name']]) . '"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Lead</a></li>';
                    }
                } else {
                    $output .= ' <li><a href="' . route('customer-add-lead', ['id' => Crypt::encrypt($post['id']), 'comp_name' => $post['company_name']]) . '"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Lead</a></li>';
                }

                if (Auth::user()->user_type == 'MA') {
                    $message = "'" . 'Are you sure want to delete this customer?' . "'";
                    $output .= '<li><a onclick="return confirm(' . $message . ')" href="' . route('delete-customer', ['id' => Crypt::encrypt($post['id'])]) . '"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>';
                }
                $output .= '</ul>
                  </td>
                </tr>';
            }
            echo $output;
        }
    }

    public function customerstatuschange(Request $request)
    {
        $id = Crypt::decrypt($request->input('id'));
        $status = $request->input('status');
        $status_change = customer::changeStatus($id, $status);
        $newid = "'" . $request->input('id') . "'";
        $newstat = "'" . $status_change . "'";
        $newrow = "'" . $id . "'";
        if ($status_change == 1) {
            $output = '<button class="tick" onclick="statuscustomer(' . $newid . ',' . $newstat . ',' . $newrow . ');"><i class="fa fa-check" title="Active" aria-hidden="true" ></i></button>';
        } elseif ($status_change == 0) {
            $output = '<button class="tick"  onclick="statuscustomer(' . $newid . ',' . $newstat . ',' . $newrow . ');"><i class="fa fa-close" title="Inactive" aria-hidden="true"></i></button>';
        }
        return $output;
    }

    public function add_lead_customer(Request $request)
    {

        if (Auth::user()->user_type != 'SP') {
            return redirect('/add_lead');
        }

        $model = helper::getFormFields("addleadcustomer");
        $formfield = helper::encryptForm($model);
        $customer_data = array(
            'id' => '',
            'company_name' => '',
            'is_active' => '',
            'registration_number' => '',
            'is_outside_FA' => '',
            'address _line_1' => '',
            'address _line_2' => '',
            'address _line_3' => '',
            'city' => '',
            'county' => '',
            'country' => '',
            'postal_code' => '',
        );
        $contact_details = array();
        $contact_details[0] = array(
            'id' => '',
            'custom_id' => '',
            'contact_person_name' => '',
            'contact_person_phone1' => '',
            'contact_person_phone2' => '',
            'contact_person_email1' => '',
            'contact_person_email2' => '',
            'contact_person_note' => '',
            'contact_person_job_title' => '',
            'contact_person_job_role' => '',
        );
        $attach_details = array();
        $attach_details[0] = array(
            'id' => '',
            'custom_id' => '',
            'customer_attachment_name' => '',
            'customer_attachment_file_name' => '',
        );
        $salesperson = User::get_all_sales_person();
        $sp = array('user_id' => '');
        return view('/add_lead_customer', compact('formfield', 'customer_data', 'contact_details', 'attach_details', 'salesperson', 'sp'));
    }

    public function add_lead_customer_now($id)
    {
        try
        {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect('/add_lead');
        }

        //$id = Crypt::decrypt($id);
        $model = helper::getFormFields("addleadcustomer");
        $formfield = helper::encryptForm($model);
        $customer_data = array(
            'id' => '',
            'company_name' => '',
            'is_active' => '',
            'registration_number' => '',
            'is_outside_FA' => '',
            'address _line_1' => '',
            'address _line_2' => '',
            'address _line_3' => '',
            'city' => '',
            'county' => '',
            'country' => '',
            'postal_code' => '',
        );
        $contact_details = array();
        $contact_details[0] = array(
            'id' => '',
            'custom_id' => '',
            'contact_person_name' => '',
            'contact_person_phone1' => '',
            'contact_person_phone2' => '',
            'contact_person_email1' => '',
            'contact_person_email2' => '',
            'contact_person_note' => '',
            'contact_person_job_title' => '',
            'contact_person_job_role' => '',
        );
        $attach_details = array();
        $attach_details[0] = array(
            'id' => '',
            'custom_id' => '',
            'customer_attachment_name' => '',
            'customer_attachment_file_name' => '',
        );
        $salesperson = User::get_all_sales_person();
        //$sp = array('user_id' => '' );
        return view('/add_lead_customer', compact('formfield', 'customer_data', 'contact_details', 'attach_details', 'salesperson', 'id'));
    }

    public function submitleadcustomer(Request $request)
    {
        $white_lists = helper::getFormFields("addleadcustomer");
        $ignore_keys = array('_token');
        $allRequest = $request->all();
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        $return_id = 0;
        $file_data = helper::decryptForm($_FILES, $white_lists, $ignore_keys);

        $decrypt_id = 0;
        if ($post_data['id'] != '') {
            $decrypt_id = Crypt::decrypt($post_data['id']);
        }
        //helper::pre($post_data);exit;
        if (!$post_data) {
            return "redirect page to 404";
        } else {

            $all_data = array();

            $all_data['company_name'] = isset($post_data['company_name']) ? $post_data['company_name'] : '';
            $all_data['is_active'] = isset($post_data['is_active']) ? $post_data['is_active'] : '1';
            $all_data['registration_number'] = isset($post_data['registration_number']) ? $post_data['registration_number'] : '';
            $all_data['is_outside_FA'] = isset($post_data['is_outside_FA']) ? $post_data['is_outside_FA'] : '';
            $all_data['address _line_1'] = isset($post_data['address_line_1']) ? $post_data['address_line_1'] : '';
            $all_data['address _line_2'] = isset($post_data['address_line_2']) ? $post_data['address_line_2'] : '';
            $all_data['address _line_3'] = isset($post_data['address_line_3']) ? $post_data['address_line_3'] : '';
            $all_data['city'] = isset($post_data['city']) ? $post_data['city'] : '';
            $all_data['county'] = isset($post_data['county']) ? $post_data['county'] : '';
            $all_data['country'] = isset($post_data['country']) ? $post_data['country'] : '';
            $all_data['postal_code'] = isset($post_data['postal_code']) ? $post_data['postal_code'] : '';
            if ($decrypt_id == 0) {
                $return_id = customer::insertcustomer($all_data);
                $spID = isset($post_data['sales_person_id']) ? $post_data['sales_person_id'] : '';
                Map_customer_salesperson::insertSP($return_id, $spID);
            } elseif ($decrypt_id != 0) {
                $return_id = $all_data['id'] = $decrypt_id;
                customer::editcustomer($all_data);
                $spID = isset($post_data['sales_person_id']) ? $post_data['sales_person_id'] : '';
                Map_customer_salesperson::insertSP($return_id, $spID);
            }
            //echo $decrypt_id;exit;
            if ($return_id != 0) {
                customer_attachment::deleteattachments($return_id);
                customer_contact_person::deletecontact($return_id);

                if (isset($post_data['contact_person_name'])) {
                    $data = array();
                    $data['custom_id'] = $return_id;
                    if (is_array($post_data['contact_person_name']) && count($post_data['contact_person_name'] > 0)) {
                        foreach ($post_data['contact_person_name'] as $key1 => $value1) {
                            $arr['contact_person_name'] = isset($post_data['contact_person_name'][$key1]) ? $post_data['contact_person_name'][$key1] : '';
                            $arr['contact_person_phone1'] = isset($post_data['contact_person_phone1'][$key1]) ? $post_data['contact_person_phone1'][$key1] : '';
                            $arr['contact_person_phone2'] = isset($post_data['contact_person_phone2'][$key1]) ? $post_data['contact_person_phone2'][$key1] : '';
                            $arr['contact_person_email1'] = isset($post_data['contact_person_email1'][$key1]) ? $post_data['contact_person_email1'][$key1] : '';
                            $arr['contact_person_email2'] = isset($post_data['contact_person_email2'][$key1]) ? $post_data['contact_person_email2'][$key1] : '';
                            $arr['contact_person_note'] = isset($post_data['contact_person_note'][$key1]) ? $post_data['contact_person_note'][$key1] : '';
                            $arr['contact_person_job_title'] = isset($post_data['contact_person_job_title'][$key1]) ? $post_data['contact_person_job_title'][$key1] : '';
                            $arr['contact_person_job_role'] = isset($post_data['contact_person_job_role'][$key1]) ? $post_data['contact_person_job_role'][$key1] : '';
                            array_push($data, $arr);
                        }
                        customer_contact_person::insertcontacts($data);
                    }
                }
                if (isset($post_data['customer_attachment_name'])) {
                    $data = array();
                    $data['custom_id'] = $return_id;
                    if (is_array($post_data['customer_attachment_name']) && count($post_data['customer_attachment_name'] > 0)) {
                        //print_r($post_data);
                        foreach ($post_data['customer_attachment_name'] as $key1 => $value1) {
                            $arr['customer_attachment_name'] = isset($post_data['customer_attachment_name'][$key1]) ? $post_data['customer_attachment_name'][$key1] : '';

                            $file_name = '';
                            if (isset($post_data['hide_attach'][$key1]) && $post_data['hide_attach'][$key1] != '0') {
                                $arr['customer_attachment_file_name'] = $post_data['hide_attach'][$key1];
                            } else if (isset($post_data['customer_attachment_file_name'][$key1]) && $post_data['hide_attach'][$key1] == '0') {
                                //echo 1;
                                $file_ext = pathinfo($file_data['customer_attachment_file_name']['name'][$key1], PATHINFO_EXTENSION);
                                $microsec = explode(".", microtime(true));
                                $file_name[$key1] = Auth::id() . "_" . date('Ymd_His') . $microsec[1] . "." . $file_ext;
                                $destination = "public/uploads/customer/" . $file_name[$key1];
                                move_uploaded_file($post_data['customer_attachment_file_name'][$key1], $destination);
                                $arr['customer_attachment_file_name'] = $file_name[$key1];
                            }

                            array_push($data, $arr);
                        } //exit;
                        //print_r($post_data);exit;
                        customer_attachment::insertattachments($data);
                    }
                }
            }

            if ($decrypt_id == 0) {

                $attach_details = customer_attachment::attach_details($return_id);
                if (count($attach_details) > 0) {
                    $output = '<div class="document-block" >
                                      <h4>Agreement Documents</h4><ul>';
                    foreach ($attach_details as $details) {
                        $output .= ' <li>' . $details['customer_attachment_name'] . '<a href="' . asset('public/uploads/customer/' . $details['customer_attachment_file_name']) . '" download><i class="fa fa-eye" aria-hidden="true"></i></a></li>';
                    }

                    $output .= '</ul></div>';
                } else {
                    $output = '<div class="document-block" >
                                      <h4>Agreement Documents</h4>No attached documents</div>';
                }

                $customerlist = customer::spcustomers($spID);
                $contact_person = customer_contact_person::allcontact_person($return_id);

                $request->session()->flash('customerlist', $customerlist);
                $request->session()->flash('customer_attachments', $output);
                $request->session()->flash('contact_person', $contact_person);
                $request->session()->flash('customer_id', $return_id);
                $request->session()->flash('customer', $post_data['company_name']);
                $request->session()->flash('sp_id', $spID);
                return redirect('/add_lead');
            }

            //}
        }
    }

    public function checkCustomerExist(Request $request)
    {
        $allRequest = $request->all();
        // helper::pre($allRequest,1);
        $company = $request->input('company');
        if ($company != ' ') {
            $rule = [
                'company_name' => 'unique:customers,company_name,',
            ];
        } else {
            $rule = [
                'company_name' => 'required',
            ];
        }
        $validator = Validator::make($allRequest, $rule);
        if ($validator->fails()) {
            echo 'Y';
        } else {
            echo 'N';
        }
    }

    public function checkRegistratonExist(Request $request)
    {
        $allRequest = $request->all();
        // helper::pre($allRequest,1);
        $registration_number = $request->input('registration_number');
        if ($registration_number != ' ') {
            $rule = [
                'registration_number' => 'unique:customers,registration_number,',
            ];
        } else {
            $rule = [
                'registration_number' => 'required',
            ];
        }
        $validator = Validator::make($allRequest, $rule);
        if ($validator->fails()) {
            $data['customar_information'] = customer::all()->where('registration_number', $registration_number)->first()->toArray();
            $data['status'] = 'Y';
            echo json_encode($data);
            //echo ($data);
        } else {
            $data['customar_information'] = '';
            $data['status'] = 'N';
            echo json_encode($data);
            //echo 'N';
        }
    }

    public function crypt_id(Request $request)
    {
        return route('add-lead-customer-now', ['id' => Crypt::encrypt($request->input('id'))]);
    }

    public function deleteCustomer($id)
    {
        try
        {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect('/manage_customer');
        }
        $resp = customer::delete_customer($id);
        if ($resp == 1) {
            return redirect('/manage_customer')->with('success_message', 'Customer deleted Successfully!');
        } else if ($resp == 0) {
            return redirect('/manage_customer')->with('error_message', 'Customer can not be deleted as it has leads');
        }
    }

    public function umbrella_calculator(Request $request)
    {
        //helper::pre($request->all(),1);
        $user = Auth::user()->toArray();
        $user_id = $user['id'];
        $white_lists = helper::getFormFields("umbrellaCalculator");
        $formfield = helper::encryptForm($white_lists);
        $ignore_keys = array('_token');
        $allRequest = $request->all();
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        $umbrella_array = '';
        if (count($post_data) > 0) {

            //helper::pre($post_data, 1);
            $umbrella_array['Your Tax Code'] = '1185L Week 1 / Month 1';
            if ($post_data['rate_type'] == 'H') {
                $umbrella_array['Time Worked'] = $post_data['total_hour_day'] . ' Hours, Per Week';
            }
            if ($post_data['rate_type'] == 'D') {
                $umbrella_array['Time Worked'] = $post_data['total_hour_day'] . ' Days, Per Week';
            }
            $umbrella_array['Rate of Pay'] = '£' . $post_data['rate_of_pay'];
            $umbrella_array['Gross Pay'] = '£' . $post_data['rate_of_pay'] * $post_data['total_hour_day'];
            $umbrella_array['Less Employer National Insurance'] = '- £32.2';
            $umbrella_array['Less Margin'] = '- £' . $post_data['input_margin'];
            $umbrella_array['Less Tax'] = '- £32.2';
            $umbrella_array['Less Employee National Insurance'] = '- £27.33';
            $umbrella_array['Less Combined Pensions Deduction'] = '- £12.16';
            $gross_pay = $post_data['rate_of_pay'] * $post_data['total_hour_day'];
            $employer_less = 32.2;
            $less_margin = $post_data['input_margin'];
            $less_tax = 32.2;
            $employee_less = 27.33;
            $pension_less = 12.16;
            $individuals_name = $post_data['individuals_name'];
            if ($post_data['include_pension'] == '0') {
                $pension_less = 0;
            }
            $total = $gross_pay - $employer_less - $less_margin - $less_tax - $employee_less - $pension_less;
            //helper::pre($umbrella_array);

            $insert_array = array(
                'user_id' => $user_id,
                'your_name' => $post_data['your_name'],
                'individuals_name' => $post_data['individuals_name'],
                'individuals_email' => $post_data['individuals_email'],
                'rate_type' => $post_data['rate_type'],
                'rate_of_pay' => $post_data['rate_of_pay'],
                'total_hour_day' => $post_data['total_hour_day'],
                'include_pension' => $post_data['include_pension'],
                'input_margin' => $post_data['input_margin'],
            );
            $insert = Umbrella_querie::add_umbrella_querie($insert_array);
            return view('umbrella_calculator_view', compact('umbrella_array', 'total', 'individuals_name'));
        }
        return view('umbrella_calculator', compact('formfield'));
    }

    public function umbrella_report(Request $request)
    {
        $postArr['fromdate'] = '';
        $postArr['todate'] = '';
        //helper::pre($request->all(),0);
        if ($request->has('_token')) {
            if ($request->input('fromdate') != '') {
                $postArr['fromdate'] = $request->input('fromdate');
            }
            if ($request->input('todate') != '') {
                $postArr['todate'] = $request->input('todate');
            }
            $all_data = Umbrella_querie::searchQuerie($postArr);
            //helper::pre($all_data,0);
        } else {
            $per_page = $this->items_per_page;
            $all_data = Umbrella_querie::fetch_all_report($per_page);
        }
        //helper:: pre($all_data,1);
        return view('umbrella_report', compact('all_data', 'postArr'));
    }

    public function load_umbrella_report(Request $request)
    {
        $output = '';
        $id = $request->input('id');
        $page = $this->items_per_page;
        $report = Umbrella_querie::get_load_umbrellla_report($page, $id);
        if (count($report) > 0) {
            foreach ($report as $data) {
                $output .= '<tr id="' . $data['id'] . '"><td>' . $data['id'] . '</td>'
                    . '<td>' . $data['your_name'] . '</td>'
                    . '<td>' . $data['individuals_email'] . '</td>';
                if ($data['rate_type'] == 'H') {
                    $output .= '<td>Hour</td>';
                } else {
                    $output .= '<td>Day</td>';
                }
                $output .= '<td>' . $data['rate_of_pay'] . '</td>'
                    . '<td>' . $data['total_hour_day'] . '</td>'
                    . '</tr>';
            }
        }
        echo $output;
    }

    public function import_add_customer(Request $request)
    {
        $allRequest = $request->all();
        //helper::pre($allRequest,1);
        if (count($allRequest)) {
            $expfile = $_FILES["uploadBtn"]["name"];
            $expfilename = explode('.', $expfile);
            $fileext = end($expfilename);
            if ($fileext != 'xlsx') {
                return redirect('uploadExcel')->with('error_message', 'Please upload .xlsx file only!');
            }
            $file = $request->uploadBtn;
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rowcount = 1;
            $arrayValIndexes = array();
            $upload_data = array();
            $error_array = array();
            $error_message = '';
            $error_message1 = '';
            $column_header_array = array('Company Name', 'Company Registration Number', 'Address Line 1'
                , 'Address Line 2', 'Address Line 3', 'City', 'County', 'Country', 'Postal Code', 'Company Contact Name', 'Company Phone Number 1',
                'Company Phone Number 2', 'Company Email Address 1', 'Company Email Address 2', 'Contact note', 'Contact Job Title/Role',
                'Assigned To Sale Person');
            //helper::pre($column_header_array, 0);
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                //helper::pre($cellIterator, 1);
                $cellIterator->setIterateOnlyExistingCells(false);
                $arrayVal = array();
                $innercount = 1;
                $column_count = 1;
                foreach ($cellIterator as $cell) {
                    //helper::pre($cell->getValue(), 1);
                    if ($rowcount == 1 && $column_count < 18) {
                        array_push($arrayValIndexes, ltrim(rtrim($cell->getValue())));

                    }
                    if ($rowcount > 1 && $column_count < 18) {
                        $arrayVal[] = $cell->getValue();
                    }
                    $column_count++;
                }
                if ($rowcount > 1) {
                    array_push($upload_data, $arrayVal);
                }
                $rowcount++;
            }

            $array_equi = self::array_equal($arrayValIndexes, $column_header_array);
            if ($array_equi) {
                return redirect('/manage_customer')->with('error_message', 'Please Dont edit xlsx header field');
            }

            //  Validation

            foreach ($upload_data as $key => $value) {
                // validation for duplicate data
                $duplicate_data = array();
                $duplicate_data['company_name'] = isset($value['0']) ? $value['0'] : '';
                $duplicate_data['registration_number'] = isset($value['1']) ? $value['1'] : '';
                $duplicate_data['contact_person_email1'] = isset($value['12']) ? $value['12'] : '';
                $validationRules = [
                    'contact_person_email1' => 'unique:customer_contact_persons,contact_person_email1',
                    'registration_number' => 'unique:customers,registration_number',
                    'company_name' => 'unique:customers,company_name',
                ];
                $validation = Validator::make($duplicate_data, $validationRules);
                if ($validation->fails()) {
                    $error_array = $validation->errors()->keys();
                    foreach ($error_array as $error_value) {
                        $error_message = $error_message . $error_value . ', ';
                    }
                    //helper::pre($error_message,0);
                    //helper::pre($validation->errors()->keys(),1);

                    return redirect('/manage_customer')->with('error_message', 'Duplicate ' . $error_message . ' for "' . $duplicate_data['company_name'] . '" company');
                }

                //validation for blank data
                $validate_data = array();
                $validate_data['contact_person_name'] = isset($value['9']) ? $value['9'] : '';
                $validate_data['contact_person_phone1'] = isset($value['10']) ? $value['10'] : '';
                $validate_data['contact_person_email1'] = isset($value['12']) ? $value['12'] : '';
                $validate_data['contact_person_note'] = isset($value['14']) ? $value['14'] : '';
                $validate_data['contact_person_job_title'] = isset($value['15']) ? $value['15'] : '';
                $validationRules1 = [
                    'contact_person_name' => 'required',
                    'contact_person_phone1' => 'required',
                    'contact_person_email1' => 'required',
                    'contact_person_note' => 'required',
                    'contact_person_job_title' => 'required',
                ];

                $validation1 = Validator::make($validate_data, $validationRules1);
                if ($validation1->fails()) {
                    $error_array1 = $validation1->errors()->keys();
                    foreach ($error_array1 as $error_value1) {
                        $error_message1 = $error_message1 . $error_value1 . ', ';
                    }
                    return redirect('/manage_customer')->with('error_message', 'Please fill field ' . $error_message1 . ' for "' . $duplicate_data['company_name'] . '" company');
                }

            }

            // End of validation

            foreach ($upload_data as $key => $value) {
                $all_data = array();
                $all_data['company_name'] = isset($value['0']) ? $value['0'] : '';
                $all_data['is_active'] = 1;
                $all_data['registration_number'] = isset($value['1']) ? $value['1'] : '';
                $all_data['is_outside_FA'] = 2;
                $all_data['address _line_1'] = isset($value['2']) ? $value['2'] : '';
                $all_data['address _line_2'] = isset($value['3']) ? $value['3'] : '';
                $all_data['address _line_3'] = isset($value['4']) ? $value['4'] : '';
                $all_data['city'] = isset($value['5']) ? $value['5'] : '';
                $all_data['county'] = isset($value['6']) ? $value['6'] : '';
                $all_data['country'] = isset($value['7']) ? $value['7'] : '';
                $all_data['postal_code'] = isset($value['8']) ? $value['8'] : '';

                $spID = User::get_sp_by_name($value['16']);
                if ($spID == 0) {
                    return redirect('/manage_customer')->with('error_message', 'Please input proper saleperson name for company "' . $all_data['company_name'] . ' "');
                }
                //helper::pre($spID, 0);
                $return_id = customer::insertcustomer($all_data);
                Map_customer_salesperson::insertSP($return_id, $spID);
                $arr['custom_id'] = $return_id;
                $arr['contact_person_name'] = isset($value['9']) ? $value['9'] : '';
                $arr['contact_person_phone1'] = isset($value['10']) ? $value['10'] : '';
                $arr['contact_person_phone2'] = isset($value['11']) ? $value['11'] : '';
                $arr['contact_person_email1'] = isset($value['12']) ? $value['12'] : '';
                $arr['contact_person_email2'] = isset($value['13']) ? $value['13'] : '';
                $arr['contact_person_note'] = isset($value['14']) ? $value['14'] : '';
                $arr['contact_person_job_title'] = isset($value['15']) ? $value['15'] : '';
                $arr['contact_person_job_role'] = 'test_val';
                customer_contact_person::firstOrCreate($arr);
            }
            //helper::pre($array_equi, 0);
            //helper::pre($upload_data, 0);
            //helper::pre($arrayValIndexes, 1);
            return redirect('/manage_customer')->with('success_message', 'All Customer Added Successfully!');
        }
    }

    public function export() {
        $new_arrray = array();
        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        /*         * *********for bold heading  ************* */
        $sheet->getStyle('A1')->applyFromArray($styleArray);
        $sheet->getStyle('B1')->applyFromArray($styleArray);
        $sheet->getStyle('C1')->applyFromArray($styleArray);
        $sheet->getStyle('D1')->applyFromArray($styleArray);
        $sheet->getStyle('E1')->applyFromArray($styleArray);
        $sheet->getStyle('F1')->applyFromArray($styleArray);
        $sheet->getStyle('G1')->applyFromArray($styleArray);
        $sheet->getStyle('H1')->applyFromArray($styleArray);
        $sheet->getStyle('I1')->applyFromArray($styleArray);
        $sheet->getStyle('J1')->applyFromArray($styleArray);
        $sheet->getStyle('K1')->applyFromArray($styleArray);
        $sheet->getStyle('L1')->applyFromArray($styleArray);
        $sheet->getStyle('M1')->applyFromArray($styleArray);
        $sheet->getStyle('N1')->applyFromArray($styleArray);
        $sheet->getStyle('O1')->applyFromArray($styleArray);
        $sheet->getStyle('P1')->applyFromArray($styleArray);
        $sheet->getStyle('Q1')->applyFromArray($styleArray);
        /*         * *********for set column width  ************* */
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(25);
        $sheet->getColumnDimension('L')->setWidth(25);
        $sheet->getColumnDimension('M')->setWidth(25);
        $sheet->getColumnDimension('N')->setWidth(25);
        $sheet->getColumnDimension('O')->setWidth(25);
        $sheet->getColumnDimension('P')->setWidth(25);
        $sheet->getColumnDimension('Q')->setWidth(25);
        $sheet->setCellValue('A1', 'Company Name');
        $sheet->setCellValue('B1', 'Company Registration Number');
        $sheet->setCellValue('C1', 'Address Line 1');
        $sheet->setCellValue('D1', 'Address Line 2');
        $sheet->setCellValue('E1', 'Address Line 3');
        $sheet->setCellValue('F1', 'City');
        $sheet->setCellValue('G1', 'County');
        $sheet->setCellValue('H1', 'Country');
        $sheet->setCellValue('I1', 'Postal Code');
        $sheet->setCellValue('J1', 'Company Contact Name');
        $sheet->setCellValue('K1', 'Company Phone Number 1');
        $sheet->setCellValue('L1', 'Company Phone Number 2');
        $sheet->setCellValue('M1', 'Company Email Address 1');
        $sheet->setCellValue('N1', 'Company Email Address 2');
        $sheet->setCellValue('O1', 'Contact note');
        $sheet->setCellValue('P1', 'Contact Job Title/Role');
        $sheet->setCellValue('Q1', 'Assigned To Sale Person');
        
        //$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
         //header("Content-Type: application/vnd.ms-excel");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="upload_client.xlsx"');
        $writer->save("php://output");
        die();
    }

    public function array_equal($a, $b)
    {
        // return (
        //      is_array($a)
        //      && is_array($b)
        //      && count($a) == count($b)
        //      && array_diff($a, $b) === array_diff($b, $a)
        // );
        foreach ($a as $key => $value) {
            if ($value != $b[$key]) {
                return 1;
                //break;
            }
        }
        return 0;
    }
    public function test()
    {
        return view('umbrella_calculator_view');
    }

}
