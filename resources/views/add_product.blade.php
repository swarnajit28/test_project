@extends('layouts.layout')
@section('title')
  <title>@if($p_data['id']=='')Add Product @else Edit Product @endif</title>
@endsection
@section('css')
<link href="{{ asset('public/css/addproduct.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
      
      <div class="addcustomer-form addproduct-form">
       
       @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
       @endif

        <form role="form" id="customerForm" method="POST" action="{{route('submit_product')}}" class="row product_select">
         {{ csrf_field() }}
         <input type="hidden" value="@if($p_data['id']!=''){{ Crypt::encrypt($p_data['id']) }} @endif" name="{{$encrypted['id']}}">
         <div class="col-lg-6 col-md-6 col-sm-6">
             <div class="form-group">
                  <label class="blank-label">&nbsp;</label>
                  <div class="viewgrp-dropdownblk">
                    <label for="{{ $encrypted['product_category'] }}">Category</label>
                    <div class="viewgrp-dropdown">
                      <div class="magicsearch-wrapper">
                          <select class="form-control" name="{{ $encrypted['product_category'] }}">
                              <option  value="">select</option>
                              @if(count($category)>0)
                                @foreach($category as $cat)
                                  <option value="{{ $cat->id}}" @if($p_data['product_category_id'] == $cat->id) {{ 'selected' }} @endif>{{ $cat->category_name }}</option>
                                @endforeach
                              @endif
                          </select>
                           @if ($errors->has('product_category'))
                              <span class="error">{{$errors->first('product_category')}}</span>   
                           @endif
                          </div>
                        </div>
                    </div>
                   
             </div>

             <div class="form-group">
                 <label for="{{ $encrypted['product_name'] }}">Product name</label>
                 <input type="text" name="{{ $encrypted['product_name'] }}" id="p_name" onKeyUp="checkNameExist()" value="{{$p_data['prod_name']}}" class="form-control">
                 @if ($errors->has('product_name'))
                 <span class="error">{{$errors->first('product_name')}}</span>   
                 @endif
                 <p class="perr error" id="pNameError"></p>
             </div>
             
             <div class="form-group">
                 <label for="{{ $encrypted['product_description'] }}">Product Description</label>
                 <textarea class="form-control" name="{{ $encrypted['product_description'] }}" rows="12">{{$p_data['prod_desc']}}</textarea>
                 @if ($errors->has('product_description'))
                 <span class="error">{{$errors->first('product_description')}}</span>   
                 @endif
             </div>
         </div>

       

         <div class="col-lg-6 col-md-6 col-sm-6">
             <div class="form-group">
                 <label for="{{ $encrypted['margin_gbp'] }}">Margin (GBP)</label>
                 <input type="text" name="{{ $encrypted['margin_gbp'] }}" class="form-control" id="margin_val" value="{{$p_data['margin_value']}}" onKeyUp="commission_cal()" placeholder="e.g £25">
                 @if ($errors->has('margin_gbp'))
                 <span class="error">{{$errors->first('margin_gbp')}}</span>   
                 @endif
             </div>

             <div class="form-group ">
                 <label for="{{ $encrypted['rebate_gbp'] }}">Rebate (GBP)</label>
                 <input type="text" name="{{ $encrypted['rebate_gbp'] }}" class="form-control" id="rebate_val" value="{{$p_data['rebate']}}" onKeyUp="commission_cal()" placeholder=" e.g £5">
                 @if ($errors->has('rebate_gbp'))
                 <span class="error">{{$errors->first('rebate_gbp')}}</span>   
                 @endif
                 
             </div>

             <div class="form-group ">
                 <label for="{{ $encrypted['end_margin'] }}">End Margin</label>
                 <input type="text" name="{{ $encrypted['end_margin'] }}" class="form-control" id="end_margin" value="{{$p_data['end_margin']}}" readonly="" placeholder=" (margin-rebate)">
                 @if ($errors->has('end_margin'))
                 <span class="error">{{$errors->first('end_margin')}}</span>   
                 @endif
                 <p class="perr error" id="endMarginError"></p>
             </div>

             <div class="form-group ">
                 <label for="{{ $encrypted['commission'] }}">Commission</label>
                 <input type="text" name="{{ $encrypted['commission'] }}" class="form-control" id="commission" value="{{$p_data['commission']}}" readonly="" placeholder="">
                 @if ($errors->has('commission'))
                 <span class="error">{{$errors->first('commission')}}</span>   
                 @endif
             </div>
         </div>

          <div class="clearfix"></div>
          
          <div class="form-group col-lg-12">
           <!--  <input type="hidden" name="save" value="contact"> -->
              <a href="{{route('list_product')}}" class="btn btn-primary reset-btn">Cancel</a>
            
            @if($p_data['id']=='')
            <button type="submit" class="btn btn-primary add-btn">Add Product</button>
            @else
            <button type="submit" class="btn btn-primary add-btn">Edit Product</button>
            @endif
          </div>
        </form>
      </div>      
      
    </section>

@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#customerForm").validate({
        rules: {
           // {{ $encrypted['product_name'] }}: "required",
            {{ $encrypted['product_category'] }}: {
                required: true,
            },

            {{ $encrypted['product_name'] }}: {
                required: true,
            },
           
            {{ $encrypted['margin_gbp'] }}: {
                required: true,
                number: true
            },
            {{ $encrypted['rebate_gbp'] }}: {
                required: true,
                number: true
            },
           {{ $encrypted['end_margin'] }}: {
                required: true,
                number: true
            },
           {{ $encrypted['commission'] }}: {
                required: true,
                number: true 
            },
            
        },
        messages: {
           // {{ $encrypted['product_name'] }}: "Please enter product name",
          // email: "Please enter a valid email address",
           {{ $encrypted['product_category'] }}: {
                required: "Please select a product category",
//                minlength: "Your username must consist of at least 3 characters"
            },
            {{ $encrypted['product_name'] }}: {
                required: "Please enter your product name",
//                minlength: "Your username must consist of at least 3 characters"
            },
            
            {{ $encrypted['margin_gbp'] }}: {
                required: "Please enter your MARGIN (GBP)",
                number: "Please input only numeric value"
            },
            {{ $encrypted['rebate_gbp'] }}: {
                required: "Please enter your REBATE (GBP)",
                number: "Please input only numeric value"
            },
            {{ $encrypted['end_margin'] }}: {
                required: "Please enter your end margin",
                number: "Please input only numeric value"
            },
            {{ $encrypted['commission'] }}: {
                required: "Please enter your commission",
                number: "Please input only numeric value"
            },
            
        }
    });
});

function checkNameExist()
  { 
    var product_name = $("#p_name").val();
    $.post('{{route('duplicateNameCheck')}}', {
          'product_name': product_name,
          '_token': '{{csrf_token()}}',
          }, function(response) {
             if(response == "Y"){  
                  $("#pNameError").text("Name already exist");
                  $(".add-btn").attr("disabled", "disabled");  
             }else{
                   $("#pNameError").text("");
                   $(".add-btn").removeAttr("disabled"); 
             }  
         })
  }
  
  function commission_cal()
  { 
     // var margin_val =($("#margin_val").val());  
     // alert(margin_val);
     var margin_val = parseFloat($("#margin_val").val());  
  //  var end_margin = parseInt($("#end_margin").val()); 
    var rebate_val = parseFloat($("#rebate_val").val()); 
    if(isNaN(rebate_val)) {
    var rebate_val = 0;
    }
   
   
   //alert(rebate_val);
   
    var end_margin= (margin_val-rebate_val).toFixed(2);
     if(rebate_val>=margin_val)
     { 
         $("#end_margin").val("") ;
         $("#rebate_val").val("") ;
         $("#endMarginError").text("Rebate should be less than Magin value");
     }  
      if(rebate_val< margin_val){
         $("#endMarginError").text("");
          $("#end_margin").val(end_margin) ;
     }
     
     if(end_margin<=12)
         commission=1;
     else
         commission=2;
     $("#commission").val(commission) ;

  }
</script>

  <script src="{{ asset('public/js/addproduct.js') }}"></script>
@endsection
