<?php

namespace App\model;
use helper;
use Illuminate\Database\Eloquent\Model;

class Business_expense_document extends Model
{
    public $timestamps  = false;
    protected $guarded = ['id'];
   public static function add_business_expense($insert_array) {
        $result = Business_expense_document::firstOrCreate($insert_array)->toArray();
        return $result;
    }
    
    public static function find_form($id, $month, $year) {
        $data = Business_expense_document::select('id','is_approved')->where('sales_executive_id', '=', $id)->where('reporting_period_month', '=', $month)->where('reporting_period_year', '=', $year)->first();
        if (count($data)) {
            $data = $data->toArray();
            return $data;
        } else {
            //helper::pre($data,1);
            return $data;
        }
    }
    
    public static function fetch_business_from($id, $month, $year) {
        $data = Business_expense_document::select('*')->where('sales_executive_id', '=', $id)->where('reporting_period_month', '=', $month)->where('reporting_period_year', '=', $year)->first();
        if (count($data)) {
            $data = $data->toArray();
            return $data;
        } else {
            //helper::pre($data,1);
            return $data;
        }
    }
   
    public static function fetch_standard_expense($search_array) {
        //helper::pre($search_array,1);
        $data_array = Business_expense_document::select('business_expense_documents.sales_executive_id', 'se.expense_amount', 'se.id as sale_expense_id', 'po.payment_option', 'po.is_reimbursable', 'u.display_name as saleperson_name', 'se.date_of_expense', 'se.id as standard_expense_id', 'se.client_id', 'c.company_name')
                ->join('standard_expenses as se', 'business_expense_documents.id', '=', 'se.business_expense_doc_id')
                ->join('users as u', 'business_expense_documents.sales_executive_id', '=', 'u.id')
                ->leftjoin('customers as c', 'se.client_id', '=', 'c.id')
                ->leftjoin('payment_options as po', 'po.id', '=', 'se.payment_option_id');
        if (isset($search_array['sp_id']) && $search_array['sp_id'] != '') {
            $data_array->where('business_expense_documents.sales_executive_id', '=', $search_array['sp_id']);
        }
        if (isset($search_array['from_date']) && $search_array['from_date'] != '') {
            $data_array->where('se.date_of_expense', '>=', $search_array['from_date']);
        }

        if (isset($search_array['to_year']) && $search_array['to_year'] != '0') {
            $data_array->where('se.date_of_expense', '<=', $search_array['to_date']);
        }
        if ($search_array['from_date'] == '' && $search_array['to_date'] == '') {
            $data_array->whereRaw("YEAR(se.date_of_expense) = YEAR(CURDATE())");
        }
        $data_array = $data_array->get();
        if (count($data_array)) {
            $data_array = $data_array->toArray();
            return $data_array;
        } else {
            return $data_array = array();
        }
    }

    public static function fetch_mileage_expenses($search_array) {
        $data_array = Business_expense_document::select('business_expense_documents.sales_executive_id', 'me.miles_covered', 'me.mileage_rate', 'me.mileage_total', 'me.date_of_expense', 'me.id as milage_expense_id', 'me.client_id', 'c.company_name')
                ->join('mileage_expenses as me', 'business_expense_documents.id', '=', 'me.business_expense_doc_id')
                ->join('users as u', 'business_expense_documents.sales_executive_id', '=', 'u.id')
                ->leftjoin('customers as c', 'me.client_id', '=', 'c.id');
        if (isset($search_array['sp_id']) && $search_array['sp_id'] != '') {
            $data_array->where('business_expense_documents.sales_executive_id', '=', $search_array['sp_id']);
        }
        if (isset($search_array['from_date']) && $search_array['from_date'] != '') {
            $data_array->where('me.date_of_expense', '>=', $search_array['from_date']);
        }

        if (isset($search_array['to_year']) && $search_array['to_year'] != '0') {
            $data_array->where('me.date_of_expense', '<=', $search_array['to_date']);
        }
        if ($search_array['from_date'] == '' && $search_array['to_date'] == '') {
            $data_array->whereRaw("YEAR(me.date_of_expense) = YEAR(CURDATE())");
        }
        $data_array = $data_array->get();

        if (count($data_array)) {
            $data_array = $data_array->toArray();
            return $data_array;
        } else {
            return $data_array = array();
        }
        // helper::pre($data,1);
    }

    /* public static function all_standard_expense($year) {
        $data_array = Business_expense_document::select('business_expense_documents.sales_executive_id', 'se.expense_amount', 'se.id as sale_expense_id', 'po.payment_option', 'po.is_reimbursable','u.display_name','business_expense_documents.reporting_period_month','business_expense_documents.reporting_period_year')
                ->join('standard_expenses as se', 'business_expense_documents.id', '=', 'se.business_expense_doc_id')
                ->join('users as u', 'business_expense_documents.sales_executive_id', '=', 'u.id')
                ->leftjoin('payment_options as po', 'po.id', '=', 'se.payment_option_id')
                ->where('business_expense_documents.reporting_period_year','=',$year)
                ->get();
        if (count($data_array)) {
            $data_array = $data_array->toArray();
            return $data_array;
        } else {
            return $data_array = array();
        }
        //helper::pre($data,1);
    }

    public static function all_mileage_expenses($year) {
        $data_array = Business_expense_document::select('business_expense_documents.sales_executive_id', 'me.miles_covered', 'me.mileage_rate', 'me.mileage_total','business_expense_documents.reporting_period_month','business_expense_documents.reporting_period_year','u.display_name')
                ->join('mileage_expenses as me', 'business_expense_documents.id', '=', 'me.business_expense_doc_id')
                ->join('users as u', 'business_expense_documents.sales_executive_id', '=', 'u.id')
                ->where('business_expense_documents.reporting_period_year','=',$year)
                ->get();
        if (count($data_array)) {
            $data_array = $data_array->toArray();
            return $data_array;
        } else {
            return $data_array = array();
        }
        // helper::pre($data,1);
    }
    */
     public static function search_standard_expense($search_array) {
        $data_array = Business_expense_document::select('business_expense_documents.sales_executive_id', 'se.expense_amount', 'se.id as sale_expense_id', 'po.payment_option', 'po.is_reimbursable','u.display_name','business_expense_documents.reporting_period_month','business_expense_documents.reporting_period_year')
                ->join('standard_expenses as se', 'business_expense_documents.id', '=', 'se.business_expense_doc_id')
                ->join('users as u', 'business_expense_documents.sales_executive_id', '=', 'u.id')
                ->leftjoin('payment_options as po', 'po.id', '=', 'se.payment_option_id');
        if(isset($search_array['from_year']) && $search_array['from_year']!='')
        {
            $data_array->where('business_expense_documents.reporting_period_year','>=',$search_array['from_year']);
        }

        if(isset($search_array['to_year']) && $search_array['to_year']!='0')
        {
            $data_array->where('business_expense_documents.reporting_period_year','<=',$search_array['to_year']);
        }
                
        $data_array=$data_array->get();
        if (count($data_array)) {
            $data_array = $data_array->toArray();
            helper::pre($data_array,1);
            return $data_array;
        } else {
            return $data_array = array();
        }
       
    }

    public static function search_mileage_expenses($search_array) {
        $data_array = Business_expense_document::select('business_expense_documents.sales_executive_id', 'me.miles_covered', 'me.mileage_rate', 'me.mileage_total','business_expense_documents.reporting_period_month','business_expense_documents.reporting_period_year','u.display_name')
                ->join('mileage_expenses as me', 'business_expense_documents.id', '=', 'me.business_expense_doc_id')
                ->join('users as u', 'business_expense_documents.sales_executive_id', '=', 'u.id')
                ->where('business_expense_documents.reporting_period_year','=',$year)
                ->get();
        if (count($data_array)) {
            $data_array = $data_array->toArray();
            return $data_array;
        } else {
            return $data_array = array();
        }
        // helper::pre($data,1);
    } 
    
    
    public static function id_wise_document($id,$year) {
        $data_array = Business_expense_document::select('business_expense_documents.id','business_expense_documents.sales_executive_id','business_expense_documents.reporting_period_month','business_expense_documents.reporting_period_year','business_expense_documents.is_approved')
                  ->where('business_expense_documents.reporting_period_year','=',$year)
                  ->where('business_expense_documents.sales_executive_id','=',$id)
                  ->get();
         if (count($data_array)) {
            $data_array = $data_array->toArray();
            return $data_array;
        } else {
            return $data_array = array();
        }
        
    }

}
