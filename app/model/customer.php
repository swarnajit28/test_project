<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
use helper;
use App\model\Lead;
use App\model\customer_attachment;
use App\model\customer_contact_person;
use App\model\Map_customer_salesperson;
class customer extends Model
{
  	public $timestamps = false;
    protected $guarded = ['id'];
    protected $table = 'customers';

    public static function insertcustomer($data)
    {	
        $results = customer::firstOrCreate($data)->toArray();
        return $results['id'];
    }

    public static function editcustomer($data)
    {  
        //helper::pre($data, 1);  exit;   
        $results = customer::updateOrCreate(
            ['id' => $data['id']], ['company_name' => $data['company_name'],
            'is_active' => $data['is_active'],
            'registration_number' => $data['registration_number'], 
            'is_outside_FA' => $data['is_outside_FA'],
            'address _line_1' => $data['address _line_1'],    
            'address _line_2' => $data['address _line_2'],
            'address _line_3' => $data['address _line_3'], 
            'city' => $data['city'],
            'county' => $data['county'], 
            'country' => $data['country'],
            'postal_code' => $data['postal_code'],    
            'is_outside_FA' => $data['is_outside_FA'],
                ]
        );
    }

    public static function fetchcustomer($id)
    {
        $customer_details = customer::select('*')
                          ->where('id', $id)
                          ->get();
        
        if ($customer_details->count()) {
            $customer_details = $customer_details[0]->toArray();
        }
        else
        {
            $customer_details['id'] = '';
            $customer_details['company_name'] = '';
            $customer_details['is_active'] = '';
        }
        return $customer_details;
    }

    public static function fetchcustomcol($colname,$id)
    {
        $customer_details = customer::select($colname)
                          ->where('id', $id)
                          ->get();
        //helper::pre($customer_details->toArray());
        if ($customer_details->count()) {
            $customer_details = $customer_details[0]->toArray();
        }
        else
        {
            $customer_details['company_name'] = '';
        }
        return $customer_details;
    }

    public static function customerlisting($query,$qty)
    {  
        if(Auth::user()->user_type!='SP')
        {
        $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('is_executive_for_life', '=','0')->get();
            $lifetimeids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($lifetimeids, $actval->customer_id);
                }
            }
        }    
        $searchcustomer = customer::select('customers.id', 'customers.company_name', 'customers.is_active','c.contact_person_name','c.contact_person_phone1','c.contact_person_email1',DB::raw('COUNT(l.id) as totlead'),'outside_FA_updated_on','customers.registration_number')
                   ->leftjoin('customer_contact_persons as c', 'c.custom_id', '=', 'customers.id')
                   ->leftjoin('leads as l', 'l.customer_contact_person_id', '=', 'c.id') ;
        if (Auth::user()->user_type != 'SP') {
            if ( $query['sales_person'] == '') {
                $searchcustomer->whereIn('customers.id', $lifetimeids);
            }
        }
        if(isset($query['status']) && $query['status']!='2'){           
            $searchcustomer->where('customers.is_active', '=', $query['status']);
        }

        if(isset($query['email']) && $query['email']!=''){
            $searchcustomer->where('c.contact_person_email1', 'LIKE', '%' . $query['email'] . '%');
        }

        if(isset($query['phone']) && $query['phone']!=''){
            $searchcustomer->where('c.contact_person_phone1', 'LIKE', '%' . $query['phone'] . '%');
        }

        if(isset($query['contact_name']) && $query['contact_name']!=''){
            $searchcustomer->where('c.contact_person_name', 'LIKE', '%' . $query['contact_name'] . '%');
        }

        if(isset($query['company_name']) && $query['company_name']!=''){
            $searchcustomer->where('customers.company_name', 'LIKE', '%' . $query['company_name'] . '%');
        }
        if(isset($query['registration_number']) && $query['registration_number']!=''){
            $searchcustomer->where('customers.registration_number', 'LIKE', '%' . $query['registration_number'] . '%');
        }

        if(isset($query['sales_person']) && $query['sales_person']!='')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',$query['sales_person'])->where('is_executive_for_life', '=','0')->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $searchcustomer->whereIn('customers.id',$newids);
        }

        if(Auth::user()->user_type=='SP')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('is_executive_for_life', '=','0')->where('user_id', '=',Auth::user()->id)->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $searchcustomer->whereIn('customers.id',$newids);
        }

        $allsearchcustomer = $searchcustomer->groupBy('customers.id')->orderBy('customers.id', 'DESC')->limit($qty)->get();
        //echo $allsearchcustomer = $searchcustomer->groupBy('customers.id')->orderBy('customers.id', 'DESC')->toSql();exit;
        if ($allsearchcustomer->count()) {
            $allsearchcustomer = $allsearchcustomer->toArray();
        }
        return $allsearchcustomer;
    }
    
    public static function clientlisting($query,$qty)
    {  
        if(Auth::user()->user_type!='SP')
        {
         $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('is_executive_for_life', '=','1')->get();
            $lifetimeids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($lifetimeids, $actval->customer_id);
                }
            }
        }   
        //helper::pre($lifetimeids ,1);
        
        $searchcustomer = customer::select('customers.id', 'customers.company_name', 'customers.is_active','c.contact_person_name','c.contact_person_phone1','c.contact_person_email1',DB::raw('COUNT(l.id) as totlead'),'outside_FA_updated_on','customers.registration_number')
                   ->leftjoin('customer_contact_persons as c', 'c.custom_id', '=', 'customers.id')
                   ->leftjoin('leads as l', 'l.customer_contact_person_id', '=', 'c.id');                                   
                    if (Auth::user()->user_type != 'SP') {
            if ($query['sales_person'] == '') {
                $searchcustomer->whereIn('customers.id', $lifetimeids);
            }
        }
        if(isset($query['status']) && $query['status']!='2'){           
            $searchcustomer->where('customers.is_active', '=', $query['status']);
        }

        if(isset($query['email']) && $query['email']!=''){
            $searchcustomer->where('c.contact_person_email1', 'LIKE', '%' . $query['email'] . '%');
        }

        if(isset($query['phone']) && $query['phone']!=''){
            $searchcustomer->where('c.contact_person_phone1', 'LIKE', '%' . $query['phone'] . '%');
        }

        if(isset($query['contact_name']) && $query['contact_name']!=''){
            $searchcustomer->where('c.contact_person_name', 'LIKE', '%' . $query['contact_name'] . '%');
        }

        if(isset($query['company_name']) && $query['company_name']!=''){
            $searchcustomer->where('customers.company_name', 'LIKE', '%' . $query['company_name'] . '%');
        }
        if(isset($query['registration_number']) && $query['registration_number']!=''){
            $searchcustomer->where('customers.registration_number', 'LIKE', '%' . $query['registration_number'] . '%');
        }

        if(isset($query['sales_person']) && $query['sales_person']!='')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',$query['sales_person'])->where('is_executive_for_life', '=','1')->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $searchcustomer->whereIn('customers.id',$newids);
        }

        if(Auth::user()->user_type=='SP')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',Auth::user()->id)->where('is_executive_for_life', '=','1')->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $searchcustomer->whereIn('customers.id',$newids);
        }

        $allsearchcustomer = $searchcustomer->groupBy('customers.id')->orderBy('customers.id', 'DESC')->limit($qty)->get();
        //echo $allsearchcustomer = $searchcustomer->groupBy('customers.id')->orderBy('customers.id', 'DESC')->toSql();exit;
        if ($allsearchcustomer->count()) {
            $allsearchcustomer = $allsearchcustomer->toArray();
        }
        return $allsearchcustomer;
    }

    
    public static function loadAjaxcustomer($query,$qty,$id) 
    {
         if(Auth::user()->user_type!='SP')
        {
        $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('is_executive_for_life', '=','0')->get();
            $lifetimeids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($lifetimeids, $actval->customer_id);
                }
            }
        }   
        $searchcustomer = customer::select('customers.id', 'customers.company_name', 'customers.is_active','customers.registration_number','c.contact_person_name','c.contact_person_phone1','c.contact_person_email1',DB::raw('COUNT(l.id) as totlead'),'outside_FA_updated_on')
                   ->leftjoin('customer_contact_persons as c', 'c.custom_id', '=', 'customers.id')
                   ->leftjoin('leads as l', 'l.customer_contact_person_id', '=', 'c.id');
          if (Auth::user()->user_type != 'SP') {
            if ($query['sales_person'] == '') {
                $searchcustomer->whereIn('customers.id', $lifetimeids);
            }
        }

        if(isset($query['status']) && $query['status']!='2'){           
            $searchcustomer->where('customers.is_active', '=', $query['status']);
        }

        if(isset($query['email']) && $query['email']!=''){
            $searchcustomer->where('c.contact_person_email1', 'LIKE', '%' . $query['email'] . '%');
        }

        if(isset($query['phone']) && $query['phone']!=''){
            $searchcustomer->where('c.contact_person_phone1', 'LIKE', '%' . $query['phone'] . '%');
        }

        if(isset($query['contact_name']) && $query['contact_name']!=''){
            $searchcustomer->where('c.contact_person_name', 'LIKE', '%' . $query['contact_name'] . '%');
        }
        
        if(isset($query['registration_number']) && $query['registration_number']!=''){
            $searchcustomer->where('customers.registration_number', 'LIKE', '%' . $query['registration_number'] . '%');
        }

        if(isset($query['company_name']) && $query['company_name']!=''){
            $searchcustomer->where('customers.company_name', 'LIKE', '%' . $query['company_name'] . '%');
        }

        if(isset($query['sales_person']) && $query['sales_person']!='')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',$query['sales_person'])->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $searchcustomer->whereIn('customers.id',$newids);
        }



        if(Auth::user()->user_type=='SP')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',Auth::user()->id)->where('is_executive_for_life', '=','0')->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $searchcustomer->whereIn('customers.id',$newids);
        }

        if(isset($id) && $id!=''){           
            $searchcustomer->where('customers.id', '<', $id);
        }

        $allsearchcustomer = $searchcustomer->groupBy('customers.id')->orderBy('customers.id', 'DESC')->limit($qty)->get();

        
        return $allsearchcustomer;

        
    }

    
    public static function loadAjaxclient($query,$qty,$id) 
    {
         if(Auth::user()->user_type!='SP')
        {
         $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('is_executive_for_life', '=','1')->get();
            $lifetimeids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($lifetimeids, $actval->customer_id);
                }
            }
        }   
        $searchcustomer = customer::select('customers.id', 'customers.company_name', 'customers.is_active','customers.registration_number','c.contact_person_name','c.contact_person_phone1','c.contact_person_email1',DB::raw('COUNT(l.id) as totlead'),'outside_FA_updated_on')
                   ->leftjoin('customer_contact_persons as c', 'c.custom_id', '=', 'customers.id')
                   ->leftjoin('leads as l', 'l.customer_contact_person_id', '=', 'c.id');
                   if (Auth::user()->user_type != 'SP') {
            if ($query['sales_person'] == '') {
                $searchcustomer->whereIn('customers.id', $lifetimeids);
            }
        }

        if(isset($query['status']) && $query['status']!='2'){           
            $searchcustomer->where('customers.is_active', '=', $query['status']);
        }

        if(isset($query['email']) && $query['email']!=''){
            $searchcustomer->where('c.contact_person_email1', 'LIKE', '%' . $query['email'] . '%');
        }

        if(isset($query['phone']) && $query['phone']!=''){
            $searchcustomer->where('c.contact_person_phone1', 'LIKE', '%' . $query['phone'] . '%');
        }

        if(isset($query['contact_name']) && $query['contact_name']!=''){
            $searchcustomer->where('c.contact_person_name', 'LIKE', '%' . $query['contact_name'] . '%');
        }
        
        if(isset($query['registration_number']) && $query['registration_number']!=''){
            $searchcustomer->where('customers.registration_number', 'LIKE', '%' . $query['registration_number'] . '%');
        }

        if(isset($query['company_name']) && $query['company_name']!=''){
            $searchcustomer->where('customers.company_name', 'LIKE', '%' . $query['company_name'] . '%');
        }

        if(isset($query['sales_person']) && $query['sales_person']!='')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',$query['sales_person'])->where('is_executive_for_life', '=','1')->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $searchcustomer->whereIn('customers.id',$newids);
        }



        if(Auth::user()->user_type=='SP')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',Auth::user()->id)->where('is_executive_for_life', '=','1')->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $searchcustomer->whereIn('customers.id',$newids);
        }

        if(isset($id) && $id!=''){           
            $searchcustomer->where('customers.id', '<', $id);
        }

        $allsearchcustomer = $searchcustomer->groupBy('customers.id')->orderBy('customers.id', 'DESC')->limit($qty)->get();

        
        return $allsearchcustomer;

        
    }

    public static function changeStatus($id,$stat)
    {     
        //echo $stat;exit;    
        $cust_data = customer::find($id);
        $cust_data->is_active = $stat;
        $cust_data->update();

        if ($stat==1) {
           return 1;

        } 
        else if ($stat==0){
            return 0;
        }
    }

    public static function activecustomers()
    {
        $activecustomer = customer::select('id', 'company_name')
                   ->where('is_active', '=', '1');

        if(Auth::user()->user_type=='SP')
        {
            $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',Auth::user()->id)->get();
            $newids  = array();
            if ($custom_ids->count()) 
            {
                $custom_ids = $custom_ids->toArray();
                
                foreach($custom_ids as $actval)
                {
                    array_push($newids, $actval->customer_id);
                }
            }
            else
            {
                $custom_ids = array();
            }
            $activecustomer->whereIn('id',$newids);
        }
        $activecustomers = $activecustomer->orderBy('company_name', 'ASC')->get();

        if ($activecustomers->count()) {
            $activecustomers = $activecustomers->toArray();
        }
        return $activecustomers;
    }

    public static function spcustomers($id)
    {
        $activecustomer = customer::select('id', 'company_name')
                   ->where('is_active', '=', '1');

        $custom_ids = DB::table('map_customer_salespersons')->select('customer_id')->where('user_id', '=',$id)->get();
        $newids  = array();
        if ($custom_ids->count()) 
        {
            $custom_ids = $custom_ids->toArray();
            
            foreach($custom_ids as $actval)
            {
                array_push($newids, $actval->customer_id);
            }
        }
        else
        {
            $custom_ids = array();
        }
        $activecustomer->whereIn('id',$newids);
        
        $activecustomers = $activecustomer->orderBy('company_name', 'ASC')->get();

        if ($activecustomers->count()) {
            $activecustomers = $activecustomers->toArray();
        }
        return $activecustomers;
    }


    public static function delete_customer($id)
    {
        $numprod = Lead::checkCustomer($id);
        if($numprod==0)
        {
            $res = customer_attachment::where('custom_id', '=', $id)->delete();
            $res = customer_contact_person::where('custom_id', '=', $id)->delete();
            $res = Map_customer_salesperson::where('customer_id', '=', $id)->delete();
            $res = customer::where('id', '=', $id)->delete();
            if($res)
            {
                return 1;
            }
            else{
                return 0;
            }
        }
        else{
            return 0;
        }
    }
    
    public static function check_FA($id)
    {
       $customer_FAdetails = customer::select('is_outside_FA')
                          ->where('id', $id)
                          ->first()->toArray();
       return $customer_FAdetails['is_outside_FA'];
    }

      public static function update_FA($id,$time)
    {
       $cust_data = customer::find($id);
       // $cust_data->outside_FA_updated_on = 'NOW()';
       // $cust_data->update();
       $cust_data->update(['outside_FA_updated_on' => DB::raw('NOW()')]);
    }
    
    public static function countFAupdate($date) {
        if (Auth::user()->user_type != 'SP') {
            return customer::select('id')
                            ->where('outside_FA_updated_on', '>', $date)->count();
        }
        if (Auth::user()->user_type == 'SP') {
            $id = Auth::user()->id;
            $count = customer::select('customers.company_name')
                            ->leftjoin('map_customer_salespersons as csp', 'csp.customer_id', '=', 'customers.id')
                            ->where('csp.user_id', '=', $id)
                            ->where('outside_FA_updated_on', '>', $date)
                            ->count();
            //helper::pre($count,1);
            return $count;
        }
    }

}
