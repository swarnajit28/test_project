<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\User;
use App\model\Product;
use App\model\Lead;
use App\model\customer;
use App\model\Website_setting;
use App\model\Weekly_thermometer_record;
use helper;


class UsertypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->FA_update_days = Config::get('formArray.FA_update_days');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard() {
        $user = Auth::user();
        $total = 0;
        $days = 0;
        $sperson_id = 0;
        $dtime = 0;
        $dashBordData = array();
        $fiveKdata = array();
        $sales_person_list = User::selectuser('SP');
        //$close_lead = Lead::count_close_lead();
        $close_lead_details = Lead::get_leads_by_id('close', $sperson_id, $dtime);
        $no_close_lead = count($close_lead_details);
        if ($no_close_lead != 0) {
            foreach ($close_lead_details as $key => $value) {
                $end = strtotime($value['lead_completed_date']);
                $start = strtotime($value['lead_created_on']);
                $diff = $end - $start;
                $total += $diff;
            }
            $tat = floor($total / $no_close_lead);
            $days = floor($tat / 86400);
        }

        $dead_lead = Lead::count_dead_lead();
        $open_lead = Lead::count_open_lead();
        $new_lead = Lead::count_new_lead();
        $product = Product::count_product();
        $dashBordData['open_lead'] = $open_lead['open_lead_count'];
        $dashBordData['no_close_lead'] = $no_close_lead;
        $dashBordData['dead_lead'] = $dead_lead['dead_lead_count'];
        $dashBordData['new_lead'] = $new_lead['new_lead_count'];
        $dashBordData['new_product_added'] = $product['product_count'];
        $dashBordData['Turn_arround_time'] = $days;

        $dashBoardPieChart = Lead::dashBordPieCalculation();
        // helper::pre($dashBoardPieChart,1); 
        $all_lead = Lead::count_all_leads();
        if (count($all_lead) != 0) {
            foreach ($dashBoardPieChart as $key => $value) {
                $dashBoardPieChart[$key]['percentage'] = (round(($value['LeadStreangthCount'] * 100) / $all_lead['lead_count'], 2));
                if ($value['lead_strength'] == '') {
                    $dashBoardPieChart[$key]['lead_strength'] = 'New';
                    $dashBoardPieChart[$key]['color_code'] = '#808080	';
                    $dashBoardPieChart[$key]['key_details'] = 'This is newly created lead';
                }
            }
        }
        if ($user->last_login_timestamp != '') {
            Session::put('last_login_data', $user->last_login_timestamp);
        }
        User::update_last_login_time($user->id);
        $data = array();
        $data['date_from'] = date('Y-m-d', strtotime('-' . $this->FA_update_days . ' days'));
        $countFA = customer::countFAupdate($data['date_from']);
        Session::put('outside_FA_updated_on', $countFA);

        //  -------------  5K project calculation------- 
        $current_year = date('Y');
        $weekly_5k_data = Weekly_thermometer_record::weekly_5k_data($current_year);
        //helper::pre($weekly_5k_data,1);
        if (count($weekly_5k_data) > 0) {
            $totalPaidValue = $weekly_5k_data[0]['cis_paid'] + $weekly_5k_data[0]['umbrella_paid'] + $weekly_5k_data[0]['other_paid'];
        } else {
            $totalPaidValue = 0;
        }
        $target_setting = Website_setting::find(1)->toArray();
        $weekly_project_target = $target_setting['weekly_project_target'];
        $percentage = round(($totalPaidValue / $weekly_project_target) * 100);
        $project_type = round($weekly_project_target / 1000);
        for ($k = 1; $k < 11; $k++) {
            $thermometer_data[] = round($weekly_project_target / 10) * $k;
        }
        //helper::pre($thermometer_data,1);
        if ($percentage > 100) {
            $percentage = 100;
        }
        $fiveKdata['totalPaidValue'] = $totalPaidValue;
        $fiveKdata['weekly_project_target'] = $weekly_project_target;
        $fiveKdata['percentage'] = $percentage;
        $fiveKdata['project_type'] = $project_type;
        $fiveKdata['thermometer_data'] = $thermometer_data;
        // --------------  End of calculation -----------

        if (Auth::user()->user_type == 'IT') {
            $active_sales_person = User::count_active_sales_person();
            $dashBordData['active_sales_person'] = $active_sales_person['sales_person'];
            return view('dashboard.dashboard-itmanager', compact('user', 'dashBordData', 'dashBoardPieChart', 'sales_person_list', 'fiveKdata'));
        }
        if (Auth::user()->user_type == 'MA') {
            $active_sales_person = User::count_active_sales_person();
            $dashBordData['active_sales_person'] = $active_sales_person['sales_person'];
            return view('dashboard.dashboard-management', compact('user', 'dashBordData', 'dashBoardPieChart', 'sales_person_list', 'fiveKdata'));
        }
        if (Auth::user()->user_type == 'LM') {

            $active_sales_person = User::count_active_sales_person();
            $dashBordData['active_sales_person'] = $active_sales_person['sales_person'];
            return view('dashboard.dashboard-leadmanager', compact('user', 'dashBordData', 'dashBoardPieChart', 'sales_person_list', 'fiveKdata'));
        }
        if (Auth::user()->user_type == 'SP') {

            $sperson_id = Auth::user()->id;

            //helper::pre($sperson_id,1); 
            $active_sales_person = User::count_active_sales_person();
            $dashBordData['active_sales_person'] = $active_sales_person['sales_person'];
            return view('dashboard.dashboard-salesperson', compact('user', 'dashBordData', 'dashBoardPieChart', 'sales_person_list', 'fiveKdata'));
        }
        if (Auth::user()->user_type == 'SM') {
            $active_sales_person = User::count_active_sales_person();
            $dashBordData['active_sales_person'] = $active_sales_person['sales_person'];
            return view('dashboard.dashboard-seniorManagement', compact('user', 'dashBordData', 'dashBoardPieChart', 'sales_person_list', 'fiveKdata'));
        }
        if (Auth::user()->user_type == 'OM') {
            $active_sales_person = User::count_active_sales_person();
            $dashBordData['active_sales_person'] = $active_sales_person['sales_person'];
            return view('dashboard.dashboard-operationsManagement', compact('user', 'dashBordData', 'dashBoardPieChart', 'sales_person_list', 'fiveKdata'));
        }
    }

    public function loadAjaxDashboard(Request $request) {
        //helper::pre($request->all(),1);
        $user = Auth::user();
        $details = array();
        $total = 0;
        $days = 0;
        $sperson_id = $request->input('sales_person');
        $dtime = $request->input('dtime');
        $close_lead = Lead::count_close_lead();
        $close_lead_details = Lead::get_leads_by_id('close', $sperson_id,$dtime);
        //helper::pre($close_lead_details,1);
        $no_close_lead = count($close_lead_details);
        if ($no_close_lead != 0) {
            foreach ($close_lead_details as $key => $value) {
                $end = strtotime($value['lead_completed_date']);
                $start = strtotime($value['lead_created_on']);
                $diff = $end - $start;
                $total += $diff;
            }
            $tat = floor($total / $no_close_lead);
            $days = floor($tat / 86400);
        }
        $data['open_lead'] = Lead::search_leads_from_dashboard('open', $sperson_id, $dtime)['open_lead_count'];
        $data['no_close_lead'] = Lead::search_leads_from_dashboard('close', $sperson_id, $dtime)['close_lead_count'];
        $data['dead_lead'] = Lead::search_leads_from_dashboard('dead', $sperson_id, $dtime)['dead_lead_count'];
        $data['new_lead'] = Lead::search_leads_from_dashboard('new', $sperson_id, $dtime)['new_lead_count'];
        $data['Turn_arround_time'] = $days;
        
        $all_lead = Lead::count_all_leads($sperson_id,$dtime);
        $dashBoardPieChart = Lead::dashBordPieCalculation($sperson_id,$dtime);
         if (count($all_lead) != 0) {
            foreach ($dashBoardPieChart as $key => $value) {
                $dashBoardPieChart[$key]['percentage'] = (round(($value['LeadStreangthCount'] * 100) / $all_lead['lead_count'], 2));
                if ($value['lead_strength'] == '') {
                    $dashBoardPieChart[$key]['lead_strength'] = 'New';
                    $dashBoardPieChart[$key]['color_code']   = '#808080';
                }
            }
        }
        $data['dashBoardPieChart']=$dashBoardPieChart;
        //helper::pre($dashBoardPieChart,1); 
        return $data;
    }

}