<?php

namespace App;
namespace App\model;
use helper;
use Illuminate\Database\Eloquent\Model;
use DB;

class Expense_type extends Model
{

	protected $fillable = [
        'expense_type', 'is_active'
    ];

    public $timestamps  = false;

    public static function addExpenseType($insert_array) {
        $result = Expense_type::firstOrCreate($insert_array)->toArray();
        return $result;
    }

    public static function updateExpenseType($loan_data) {
        // dd($loan_data);
        $new = array(
            'expense_type' => $loan_data['expense_type'],
            'is_active' => $loan_data['is_active']);
        $resp = Expense_type::where('id', $loan_data['id'])->update($new);

        return $resp;
    }

    public static function all_expense_type($items_per_page) {
        if($items_per_page != '')
        {
            $result = Expense_type::orderBy('id', 'DESC')->limit($items_per_page)->get();
        }
        else
        {
            $result = Expense_type::orderBy('id', 'DESC')->get(); 
        }
        return ($result);
    }


    public static function expense_type_status_change($id,$status)
    {
        $resp = Expense_type::where('id','=',$id)->update(['is_active' => $status]);
        return $resp;
    }

    public static function lastexpense()
    {
        $mode_detail = Expense_type::select('id')->orderBy('id','ASC')->limit('1')->get()->first();
        if(!empty($mode_detail))
        {
            if($mode_detail->count()){
                $id = $mode_detail->toArray();
            }
            else
            {
                $id = array();
            }
        }
        else{
            $id = array();
        }
        return $id;
    }

    public static function get_load_expense_type($qty,$id)
    {
        $expense = Expense_type::select('*')->where('id', '<', $id)->orderBy('id', 'DESC')->limit($qty)->get()->toArray(); 
        return $expense;
    }
    
     public static function active_expense_type() {
        $activeExpense = Expense_type::where('is_active', '=', '1')->orderBy('id', 'DESC')->get();

        if ($activeExpense->count()) {
            $activeExpense = $activeExpense->toArray();
        }
        else{
            $activeExpense = array();
        }
        return $activeExpense;
    }
}