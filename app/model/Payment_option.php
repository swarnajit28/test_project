<?php

namespace App;
namespace App\model;
use helper;
use Illuminate\Database\Eloquent\Model;
use DB;

class Payment_option extends Model
{

	protected $fillable = [
        'payment_option', 'is_active'
    ];

    public $timestamps  = false;

    public static function addPaymentOption($insert_array) {
        $result = Payment_option::firstOrCreate($insert_array)->toArray();
        return $result;
    }

    public static function updatePaymentOption($loan_data) {
        // dd($loan_data);
        $new = array(
            'payment_option' => $loan_data['payment_option'],
            'is_active' => $loan_data['is_active'],
            'is_reimbursable' => $loan_data['is_reimbursable']);
        $resp = Payment_option::where('id', $loan_data['id'])->update($new);

        return $resp;
    }

    public static function all_payment_option($items_per_page) {
        if($items_per_page != '')
        {
            $result = Payment_option::orderBy('id', 'DESC')->limit($items_per_page)->get();
        }
        else
        {
            $result = Payment_option::orderBy('id', 'DESC')->get(); 
        }
        return ($result);
    }


    public static function payment_option_status_change($id,$status)
    {
        $resp = Payment_option::where('id','=',$id)->update(['is_active' => $status]);
        return $resp;
    }

    public static function lastpayment()
    {
        $mode_detail = Payment_option::select('id')->orderBy('id','ASC')->limit('1')->get()->first();
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

    public static function get_load_product_category($qty,$id)
    {
        $product = Payment_option::select('*')->where('id', '<', $id)->orderBy('id', 'DESC')->limit($qty)->get()->toArray(); 
        return $product;
    }
    
     public static function active_payment_method() {
        $activeproduct = Payment_option::where('is_active', '=', '1')->orderBy('id', 'DESC')->get();

        if ($activeproduct->count()) {
            $activeproduct = $activeproduct->toArray();
        }
        else{
            $activeproduct = array();
        }
        return $activeproduct;
    }
}