
@extends('layouts.layout')
@section('title')
  <title>Manage Product</title>
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/manageproduct.css') }}" rel="stylesheet">

@endsection

@section('content')
<section class="content content-custom">
      <div class="manage-product-form">
        @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
        @endif

        @if (session('error_message'))
        <div class="alert alert-error alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('error_message') }}
        </div> 
        @endif
        <div class="view-by">
            <form action="{{route('searchProduct')}}" id="search_procuct" method="POST"  class="">
                {{ csrf_field() }}
                <div class="field-first-grp">
                    <p>Search By</p>
                    <div class="input-field">
                        <input type="text" name="product_name" placeholder="Product Name" class="form-control" value="{{(isset($postArr['product_name']) ? $postArr['product_name'] : '')}}">
                    </div>
                    <div class="input-field">

                        <input type="text" name="margin" placeholder="margin" class="form-control decnum" value="{{(isset($postArr['margin']) ? $postArr['margin'] : '')}}" >
                    </div>
                </div>
                <div class="field-second-grp">
                    <div class="input-field">
                        <input type="text" name="end_margin" placeholder="End Margin" class="form-control decnum" value="{{(isset($postArr['end_margin']) ? $postArr['end_margin'] : '')}}" >
                    </div>
                    <div class="viewgrp-dropdownblk">
                        <label>Status</label>
                        <div class="viewgrp-dropdown">
                            <div class="magicsearch-wrapper">
                                <select class="form-control" name="status">
                                    <option value="99999">Select</option>
                                    <option value="1" {{isset($postArr['status']) && $postArr['status'] =='1' ? 'selected' : null }}>Active</option>
                                    <option value="0" {{isset($postArr['status']) && $postArr['status'] =='0' ? 'selected' : null }}>Inactive</option>
                                </select>
                            </div>
                            <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
            </form>
          
        </div>
        
        <div class="table-part" >
          <table class="table tablesorter " id="loadtable">
            <thead>
              <tr>
                <th data-toggle="true">Product Name</th>
                <th data-hide="phone,tablet">Product Category</th>
                <th data-hide="phone,tablet">Margin</th>
                <th data-hide="phone,tablet">End Margin</th>
                <th class="text-center " data-sorter="false">Status</th>
                <th class="text-center " data-sorter="false">Action</th>
              </tr>
            </thead>
            <tbody id="load-data">
                @if(count($products) > 0)
            @foreach($products as $product)
              <tr>
                <td>{{$product['prod_name']}}</td>
                <td>@if(isset($product['category_name'])){{$product['category_name']}} @endif</td>
                <td>{{$product['margin_value']}}</td>
                <td>{{$product['end_margin']}}</td>
               
                <td class="text-center" id="stat{{$product['id']}}">                
                    <button class="tick"  onclick="statuscustomer('{{Crypt::encrypt($product['id'])}}','{{$product['is_active']}}','{{$product['id']}}');" ><i class="fa @if($product['is_active']==1) fa-check @else fa-close @endif" @if($product['is_active']==1) title="Active" @else title="Inactive" @endif aria-hidden="true" ></i></button>
              </td>
                <td class="text-center viewgrp-dropdown dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{url('editProduct/'.encrypt($product['id']))}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
                    <li><a href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> Report </a></li>
                    @if (Auth::user()->user_type != 'SP')
                    <li><a onclick="return confirm('Are you sure want to delete this product?')" href="{{route('delete-product',['id' => Crypt::encrypt($product['id']) ])}}"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                 
                   @endif 
                  </ul>
                </td>
              </tr>
              @endforeach

              @else
                  <tr  class="ndf">
                    <td colspan="6"><span class="text-margin centertext">No record(s) found</span></td>
                  </tr>
                @endif
          
             
            </tbody>
          </table>
            @if(isset($perPage)) 
            <div id="remove-row">
                <div class="load-more">
                  <button id="btn-more" data-id="{{(isset($product['id']) ? $product['id'] : '')}}" > Load More </button>
                    <!-- <a href="#">Load More</a> -->
                </div>
            </div>
           @endif 
        </div>
      </div>
      <input type="hidden" id="perPage" value="{{(isset($perPage) ? $perPage : '')}}">

    </section>

@endsection

@section('script-section')
  <script src="{{ asset('public/js/manageproduct.js') }}"></script>
  <script src="{{ asset('public/js/validate.js') }}"></script>
    <script src="{{ asset('public/js/tableSort.js') }}"></script>
  <script>

 $(document).ready(function() 
    { 
        $("#loadtable").tablesorter( {sortList: [[0,0]]}); 
    } 
) 
      
      
$(document).ready(function(){
   $(document).on('click','#btn-more',function(){
      var id = $(this).data('id');
      var perPage = $("#perPage").val();
       $("#btn-more").html("Loading....");
       $.ajax({
           url : '{{route("loadDataAjax")}}',
           method : "POST",
           data : {id:id, _token:"{{csrf_token()}}"},
           dataType : "text",
           success : function (data)
           {
              if(data != '') 
              {
                  //$('#remove-row').remove();
                  $('#load-data').append(data);
                  id= id-perPage;
                  $('#btn-more').data('id', id);
                  //$("#btn-more").data(id);
                  $("#btn-more").html("Load More");
                  $("#loadtable").tablesorter();
                   $("#loadtable").trigger("update"); 
                   $('.table').trigger('footable_initialize');
              }
              else
              {
                  $('#btn-more').html("No More Data");
              }
           }
       });
   });  
}); 

//$(document).ready(function() {
//    $("#search_procuct").validate({
//        rules: {
//            margin: {
//                required: true,
//                number: true
//            },
//           
//           end_margin: {
//                required: true,
//                number: true
//            },  
//        },
//        messages: {
//   
//            margin: {
//                required: "Please enter your MARGIN (GBP)",
//                number: "Please input only numeric value"
//            },
//          
//           end_margin: {
//                required: "Please enter your end margin",
//                number: "Please input only numeric value"
//            },  
//        }
//    });
//});


function statuscustomer(id,status,rowid)
    {
      if(confirm('Are you sure you want to change product status?'))
      {
        if(status==0)
        {
          status = 1;
        }
        else if(status==1)
        {
          status = 0;
        }
         $.post('<?php echo route('productstatuschange')?>', {
          'id': id,
          'status': status,
          '_token': '<?php echo csrf_token();?>',
          }, function(data) {
              $("#stat"+rowid).html(data);
              $('.table').trigger('footable_initialize');
             //document.location="";
         })
      }
      return false;
    }

  $(document).ready(function(){
    $('.decnum').keypress(function(event) {
      if ((event.which != 46 || $(this).val().indexOf('.') != -1) && ((event.which < 48 && event.which != 8) || event.which > 57)) {
        //  alert('Hi');
        event.preventDefault();
      }
    });
  });
</script>
@endsection
