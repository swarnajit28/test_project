<?php

namespace App;
namespace App\model;
use helper;
use Illuminate\Database\Eloquent\Model;
use DB;

class Product_category extends Model
{

	protected $fillable = [
        'category_name', 'is_active'
    ];

    public $timestamps  = false;

    public static function addProductCategory($insert_array) {
        $result = Product_category::firstOrCreate($insert_array)->toArray();
        return $result;
    }

    public static function updateProductCategory($loan_data) {
        // dd($loan_data);
        $new = array(
            'category_name' => $loan_data['category_name'],
            'is_active' => $loan_data['is_active']);
        $resp = Product_category::where('id', $loan_data['id'])->update($new);

        return $resp;
    }

    public static function all_product_categories($items_per_page) {
        if($items_per_page != '')
        {
            $result = Product_category::orderBy('id', 'DESC')->limit($items_per_page)->get();
        }
        else
        {
            $result = Product_category::orderBy('id', 'DESC')->get(); 
        }
        return ($result);
    }


    public static function productCategorystatchange($id,$status)
    {
        $resp = Product_category::where('id','=',$id)->update(['is_active' => $status]);
        return $resp;
    }

    public static function lastcategory()
    {
        $mode_detail = Product_category::select('id')->orderBy('id','ASC')->limit('1')->get()->first();
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
        $product = Product_category::select('*')->where('id', '<', $id)->orderBy('id', 'DESC')->limit($qty)->get()->toArray(); 
        return $product;
    }
}