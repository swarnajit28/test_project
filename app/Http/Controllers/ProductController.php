<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Crypt;
use Validator;
use helper;
use App\model\Product;
use App\model\Product_category;
use Illuminate\Contracts\Encryption\DecryptException;


use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct() {
        DB::enableQueryLog();
//       $this->middleware('MA');
        $this->items_per_page = Config::get('formArray.items_per_page');
    }

    public function add_product(Request $request) {
        $formfield = helper::getFormFields("addproduct");
        $encrypted = helper::encryptForm($formfield);
        // helper::pre($encrypted,1); 
        $p_data = array(
                            'id' =>'',
                            'product_category_id' => '',
                            'prod_name' => '',
                            'prod_desc' =>'',
                            'margin_value' => '',
                            'rebate' =>'',
                            'end_margin' => '',
                            'commission' =>'',
            
                        );
        $category = Product_category::all_product_categories('');
        return view('/add_product', compact('encrypted','p_data','category'));
    }

     public function store(Request $request) {
        $decrypt_id='';
        $allRequest = $request->all();
       // helper::pre($allRequest,1); 
        $white_lists = helper::getFormFields("addproduct");
        $ignore_keys = array('_token');
        $post_data = helper::decryptForm($allRequest, $white_lists, $ignore_keys);
        if ($post_data['id'] != '') {
            $decrypt_id = decrypt($post_data['id']);
            $post_data['id'] = $decrypt_id;
        }
        //$post_data['end_margin']=$post_data['margin_gbp']-$post_data['end_margin'];
        //helper::pre($post_data,1);
        
        $rule = [
            'product_category' => 'required',
            'product_name' => 'required|min:3|unique:products,prod_name,' . $decrypt_id,
            'margin_gbp' => 'required|numeric',
            'rebate_gbp' => 'required|numeric',
            'end_margin' => 'required|numeric',
            'commission' => 'required|numeric',
        ];
        $validator = Validator::make($post_data, $rule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $insert_array = array(
            'product_category_id' => $post_data['product_category'],
            'prod_name' => $post_data['product_name'],
            'prod_desc' => $post_data['product_description'],
            'margin_value' => $post_data['margin_gbp'],
            'rebate' => $post_data['rebate_gbp'],
            'end_margin' => $post_data['end_margin'],
            'commission' => $post_data['commission'],
        );
        //helper::pre($insert_array,1); 
        if ($post_data['id'] == '') {
            $user = Product::addProduct($insert_array);
            if ($user['id'] != 'null' && !empty($user)) {
                return redirect('/add_product')->with('success_message', 'New Product Added Successfully!');
            }
        } else {
            $post = Product::findOrFail($decrypt_id);
            $post->fill($insert_array);
            $post->save();
            return redirect()->back()->with('success_message', 'Updated Successfully!');
        }
    }

    
    public function list_product(Request $request) {
        $products = Product::all_product($this->items_per_page);
        //helper::pre($products,1);
        $perPage  = $this->items_per_page;
        return view('manage_product', compact('perPage', 'products'));    
    }
    
    public function loadDataAjax(Request $request) {
        $output = '';
        $id = $request->id;
        $posts = Product::loadAjaxProduct($this->items_per_page,$id);
        //$posts = Product::where('id', '<', $id)->orderBy('id', 'DESC')->limit(1)->get();
        if (!$posts->isEmpty()) {
            foreach ($posts as $post) {
                $output .= '<tr>
                <td>' . $post['prod_name'] . '</td>
                <td>' . $post['category_name'] . '</td>
                <td>' . $post['margin_value'] . '</td>
                <td>' . $post['end_margin'] . '</td>';
               if ($post['is_active'] == '1') {
                        $id = "'".encrypt($post['id'])."'";
                        $output .= ' <td class="text-center" id="stat'.$post['id'].'"><button class="tick"  onclick="statuscustomer('.$id.','.$post['is_active'].','.$post['id'].');" ><i class="fa fa-check" title="Active" aria-hidden="true"></i></button></td>';
                    } else {
                        $id = "'".encrypt($post['id'])."'";
                        $output .= '<td class="text-center" id="stat'.$post['id'].'"><button class="tick"  onclick="statuscustomer('.$id.','.$post['is_active'].','.$post['id'].');"><i class="fa fa-close" title="Inactive" aria-hidden="true"></i></button></td>';
                    }
                $output .= '<td class="text-center viewgrp-dropdown dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                  <ul class="dropdown-menu">';
                    $output .='<li><a href="'.route('editProduct',['id' =>encrypt($post['id']) ]).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>';
                    $output .=' <li><a href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> Report</a></li>
                    <li><a onclick="return confirm(Are you sure want to delete this product?)" href="'.route('delete-product',['id' => encrypt($post['id']) ]).'"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                   
                  </ul>
                </td>
              </tr>';              
            }
            echo $output;
        }
    }
    
    public function searchProduct(Request $request) {
        $allRequest = $request->all();
        //helper::pre($allRequest,1);
        $postArr['product_name'] = '';
        $postArr['margin'] = '';
        $postArr['end_margin'] = '';
        $postArr['status'] = 99999;

        if ($request->has('product_name')) {
            $postArr['product_name'] = $request->input('product_name');
        }

        if ($request->has('margin')) {
            $postArr['margin'] = $request->input('margin');
        }

        if ($request->has('end_margin')) {
            $postArr['end_margin'] = $request->input('end_margin');
        }

        if ($request->has('status')) {
            $postArr['status'] = $request->input('status');
        }

        $products = Product::search_product($postArr);
//        if (empty($postArr['product_name']) && empty($postArr['margin']) && empty($postArr['end_margin'])) {
//            helper::pre($products,1);
//            return redirect('/ListAllproduct');
//        }
        return view('manage_product', compact('products', 'postArr'));
    }
    
    public function productEdit($id)
    {
        try {
            $id =  decrypt($id);
        } catch (DecryptException $e) {
           return redirect('/ListAllproduct');
        }
        
        $p_data = Product::find($id);
        $p_data= $p_data-> toArray();
       // helper::pre($p_data,1);
        $formfield = helper::getFormFields("addproduct");
        $encrypted = helper::encryptForm($formfield);
        $category = Product_category::all_product_categories('');
        return view('add_product', compact('encrypted','p_data','category'));
 
    }

    public function checkNameExist(Request $request) {
        $allRequest = $request->all();
        $product_name = $request->input('product_name');
        if($product_name != ' ')
        {
            $rule = [
                'product_name' => 'unique:products,prod_name,'
            ];
        }
        else
        {
            $rule = [
                'product_name' => 'required,'
            ];
        }
        $validator = Validator::make($allRequest, $rule);
        if ($validator->fails()) {
            echo 'Y';
        } else {
            echo 'N';
        }
    }
    
    public function productDelete($id)
    { 
      try 
      {
           $id =  decrypt($id);
      } 
      catch (DecryptException $e) 
      {
          return redirect('/ListAllproduct');
      }
      $resp = Product::delete_product($id) ; 
      if($resp==1)
      {
            return redirect('/ListAllproduct')->with('success_message', 'Product deleted Successfully!');
      }
      else if($resp==0)
      {
            return redirect('/ListAllproduct')->with('error_message', 'Product can not be deleted as it is in leads');
      }
    }

   public function productstatuschange(Request $request)
    {
     
        $id  = decrypt($request->input('id'));
        $status = $request->input('status');
        $status_change = Product::changeStatus($id,$status);
        $newid = "'".$request->input('id')."'";
        $newstat = "'".$status_change."'";
        $newrow = "'".$id."'";
        if($status_change==1)
        {
            $output = '<button class="tick" onclick="statuscustomer('.$newid.','.$newstat.','.$newrow.');"><i class="fa fa-check" title="Active" aria-hidden="true" ></i></button>';
        }
        elseif($status_change==0)
        {
            $output = '<button class="tick"  onclick="statuscustomer('.$newid.','.$newstat.','.$newrow.');"><i class="fa fa-close" title="Inactive" aria-hidden="true"></i></button>';
        }
        return $output;
    }
  
  
}
