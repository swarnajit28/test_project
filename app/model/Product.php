<?php

namespace App;
namespace App\model;
use helper;
use Illuminate\Database\Eloquent\Model;
use App\model\Lead_product;
use DB;
class Product extends Model
{    
    protected $fillable = [
        'prod_name','product_category_id','prod_desc', 'margin_value', 'rebate', 'end_margin','commission'
    ];

    public $timestamps  = false;
    protected $guarded = ['id'];

    public static function addProduct($insert_array) {
        $result = Product::firstOrCreate($insert_array)->toArray();
        return $result;
    }

    public static function all_product($items_per_page) {
        $result = Product::select('products.*','product_categories.category_name')->leftJoin('product_categories','products.product_category_id','=','product_categories.id')->orderBy('products.id', 'DESC')->limit($items_per_page)->get();
        return ($result);
    }
    
    public static function loadAjaxProduct($items_per_page,$id) {
        $result = Product::select('products.*','product_categories.category_name')->leftJoin('product_categories','products.product_category_id','=','product_categories.id')->where('products.id', '<', $id)->orderBy('products.id', 'DESC')->limit($items_per_page)->get();
      /*  $result = Product::where('id', '<', $id)->orderBy('id', 'DESC')->limit($items_per_page)->get(); */
        return ($result);
    }
    
    public static function search_product($query)
    {  
        //helper::pre($query,1);
        // $searchProduct = Product::select('*');
        $searchProduct = Product::select('products.*','product_categories.category_name')->leftJoin('product_categories','products.product_category_id','=','product_categories.id');

        if(isset($query['status']) && $query['status']!=99999){           
            $searchProduct->where('products.is_active', '=', $query['status']);
        }

        if(isset($query['product_name']) && $query['product_name']!=''){
            $searchProduct->where('products.prod_name', 'LIKE', '%' . $query['product_name'] . '%');
        }

        if(isset($query['margin']) && $query['margin']!=''){
            $searchProduct->where('products.margin_value', 'LIKE', '%' . $query['margin'] . '%');
        }

        if(isset($query['end_margin']) && $query['end_margin']!=''){
            $searchProduct->where('products.end_margin', 'LIKE', '%' . $query['end_margin'] . '%');
        }
        
        $searchProduct = $searchProduct->orderBy('products.id', 'DESC')->get();

//        if ($searchProduct->count()) {
//            $allsearchcustomer = $allsearchcustomer->toArray();
//        }
        return $searchProduct;
    }

    public static function activeproduct() {
        $activeproduct = Product::where('is_active', '=', '1')->orderBy('id', 'DESC')->get();

        if ($activeproduct->count()) {
            $activeproduct = $activeproduct->toArray();
        }
        else{
            $activeproduct = array();
        }
        return $activeproduct;
    }
    public static function product_details($id) {
        $activeproduct = Product::where('is_active', '=', '1')->where('id', '=', $id)->get()->first();

        if ($activeproduct->count()) {
            $activeproduct = $activeproduct->toArray();
        }
        return $activeproduct;
    }
    
    public static function leadproduct($leadproducts) {
        $activeproduct = Product::whereIn('id',$leadproducts)->get();

        if ($activeproduct->count()) {
            $activeproduct = $activeproduct->toArray();
        }
        return $activeproduct;
    }
    
    public static function changeStatus($id,$stat)
    {     
        //echo $stat;exit;    
        $cust_data = Product::find($id);
        $cust_data->is_active = $stat;
        $cust_data->update();

        if ($stat==1) {
           return 1;

        } 
        else if ($stat==0){
            return 0;
        }
    }

    public static function count_product()
    {
        $data = Product::select(DB::raw('COUNT(*) as product_count'))->where('is_active','=','1')->first()->toArray();
       // dd($data);
        return $data ;
    }

  /*  public static function count_active_product()
    {
         $data = Product::select(DB::raw('COUNT(*) as active_product_count'))->where('is_active','=','1')->get()->toArray();
       // dd($data);
        return $data ;
    }

    public static function count_inactive_product()
    {
         $data = Product::select(DB::raw('COUNT(*) as inactive_product_count'))->where('is_active','=','0')->get()->toArray();
       // dd($data);
        return $data ;
    } */

    public static function delete_product($id)
    {
        $numprod = Lead_product::checkProduct($id);
        if($numprod==0)
        {
            $res = Product::where('id', '=', $id)->delete();
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


}
