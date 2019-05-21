<?php

namespace App\model;
use helper;

use Illuminate\Database\Eloquent\Model;

class Standard_expense extends Model
{
    public static function insert_standard_expenses($data) {
             Standard_expense::insert($data);
    }

    public static function delete_standard_expenses($id)
    {        
        Standard_expense::where('business_expense_doc_id', '=', $id)->delete();
    }
    public static function fetch_standard_expenses($id) {
        $data = Standard_expense::select('*')->where('business_expense_doc_id', '=', $id)->get();
        if (count($data)) {
            $data = $data->toArray();
            return $data;
        } else {
            //helper::pre($data,1);
            return $data;
        }
    }
// public static function standard_expense_report($id)
//    {
//      $data_array = Standard_expense::select('business_expense_doc_id','expense_amount','po.payment_option','po.is_reimbursable')
//              ->leftjoin('payment_options as po', 'po.id', '=', 'standard_expenses.payment_option_id')
//              ->where('standard_expenses.business_expense_doc_id','=',$id)
//              ->get();
//      if ($data_array->count()) {
//            $data_array = $data_array->toArray();            
//        } else {
//            $data_array = array();
//        }
//        //helper::pre($data_array,1);     
//        return $data_array;
//       
//    }   
    
}
