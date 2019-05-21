<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Mileage_expense extends Model
{
    public static function insert_mileage_expenses($data) {
             Mileage_expense::insert($data);
    }

    public static function delete_mileage_expenses($id)
    {        
        Mileage_expense::where('business_expense_doc_id', '=', $id)->delete();
    }
    public static function fetch_mileage_expenses($id) {
        $data = Mileage_expense::select('*')->where('business_expense_doc_id', '=', $id)->get();
        if (count($data)) {
            $data = $data->toArray();
            return $data;
        } else {
            //helper::pre($data,1);
            return $data;
        }
    }
}
