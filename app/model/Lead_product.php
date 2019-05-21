<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use helper;
class Lead_product extends Model
{
  	public $timestamps = false;
    protected $guarded = ['id'];

    public static function insertlead($data)
    {       
      	Lead_product::where('lead_id', '=', $data['lead_id'])->delete();
	    for($i=0;$i<count($data)-1;$i++) 
  		{
  			$leadprod  = new Lead_product;
		    $leadprod->lead_id  = $data['lead_id'];
	        $leadprod->margin_value = $data[$i]['margin_value'];
          $leadprod->end_margin = $data[$i]['end_margin'];
	        $leadprod->quantity = $data[$i]['quantity'];
	        $leadprod->prod_id = $data[$i]['prod_id'];
	        $leadprod->save();
		}
	} 

  public static function fetchprodid($id)
  {       
      $all_prod = Lead_product::select('prod_id')->where('lead_id', '=', $id)->get();
      $arr = array();
      if ($all_prod->count()) {
            $all_prod = $all_prod->toArray();
            foreach($all_prod as $key => $value)
            {
              //echo $all_prod[$key]['prod_id'];exit;
              array_push($arr,$all_prod[$key]['prod_id']);
            }
            $all_prod = $arr;
        }
        else
        {
          $all_prod = array();
        }
        return $all_prod;
  } 

	public static function fetchprod($id)
    {       
      	$all_prod = Lead_product::select('*')->where('lead_id', '=', $id)->get();
	    if ($all_prod->count()) {
            $all_prod = $all_prod->toArray();
        }
        else
        {
        	$all_prod = array();
        }
        return $all_prod;
	} 

  public static function deleteleadproduct($id)
  {       
       Lead_product::where('lead_id', '=', $id)->delete();
  }

  public static function checkProduct($id)
  {       
      $numprod = Lead_product::where('prod_id', '=', $id)->count();
      return $numprod;
  }

}
