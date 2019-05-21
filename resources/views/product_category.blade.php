@extends('layouts.layout')
@section('title')
  <title>Product Category</title>
@endsection
@section('css')
<link href="{{ asset('public/css/manageproduct.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
      <div class="manage-product-form lead_activity_mode_page">
      
      <div class="view-by">
        <?php

       if(session()->has('status'))
       {
        $data = session('status');
        //print_r($data);
        $product_category_name       = $data['product_category_name'];
        $product_category_status        = $data['product_category_status'];
       
       }else
       {
        $product_category_name        = '';
        $product_category_status        = '';
       }

       ?> 
       @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
       @endif
          <form action="{{route('add_product_category')}}" method="POST" id="addproductCategory">
           {{ csrf_field() }}
            
            <div class="field-first-grp">
            <p>Name:</p>
              <div class="input-field">
                <input type="text" name="{{ $encrypted['product_category_name'] }}"  id="{{ $encrypted['product_category_name'] }}"  placeholder="Name" class="form-control" value="<?php echo isset($category_details->category_name)?$category_details->category_name:''; ?>">
                <input type="hidden" name="{{ $encrypted['product_category_id'] }}" class="form-control" value="<?php echo isset($category_details->id)?$category_details->id:''; ?>"> 
                @if ($errors->has('product_category_name'))
                  <span class="error">{{ $errors->first('product_category_name') }}</span>
                @endif
                
              </div>
               
              <div class="viewgrp-dropdownblk">
                <label>Status</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                    <select class="form-control" name="{{ $encrypted['product_category_status'] }}">
                      <option value="1"  <?php if(isset($category_details->is_active) && ($category_details->is_active == '1')){ echo 'selected' ; }?>>Active</option>
                      <option value="0" <?php if(isset($category_details->is_active) && ($category_details->is_active == '0')){ echo 'selected' ; }?>>Inactive</option>
                    </select>
                  </div>
                  <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
                </div>
              </div>
           
           <button type="submit" class="btn btn-primary" onclick="validateForm()" id="submit">Add</button>
            </div>
          </form>
          
        </div>
        
        <div class="table-part wid-load-more">
          <input type="hidden" value="{{ $lastmode }}" id="lastcategory" name="lastcategory">
          <table class="table tablesorter" id="loadtable">
            <thead>
              <tr>
                <th data-toggle="true">Name</th>
                <th data-hide="phone,tablet" class="text-center" data-sorter="false">Status</th>
                <th class="text-center" data-sorter="false">Action</th>
              </tr>
            </thead>
            <tbody id="product_category">
            <input type="hidden" name="id" id="hideid">
               @if(count($category) >0)
               @foreach($category as $data)
                <tr id="{{$data['id']}}">
                <td data-hide="phone,tablet">{{$data['category_name']}}</td>
                <td data-hide="phone,tablet" class="text-center" id="stat{{$data['id']}}">@if($data['is_active'] == '1')<i class="fa fa-check" aria-hidden="true" title="Active" style="cursor: pointer;" onclick="change_category_status('{{$data['is_active']}}','{{$data['id']}}');"></i>@else <i class="fa fa-times" aria-hidden="true" title="Inactive" style="cursor: pointer;" onclick="change_category_status('{{$data['is_active']}}','{{$data['id']}}');"></i> @endif
                </td>
              <td class="text-center viewgrp-dropdown dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{route('edit_product_category',['id' => Crypt::encrypt($data['id'])])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>

                  </ul>
                </td>
              </tr>
              @endforeach
              @else
              <tr><td colspan="4">No records found</td></tr>
              @endif
       
            
            </tbody>
          </table>
           @if(isset($page)) 
          <div id="remove-row">
              <div class="load-more">
                  <button id="btn-more" data-id="{{(isset($data['id'])) ? $data['id'] : '' }}" > @if(count($category) > 0) Load More @else No More Data @endif</button>
                  <!-- <a href="#">Load More</a> -->
              </div>
          </div>
        @endif
        <input type="hidden" id="perPage" value="{{(isset($page) ? $page : '')}}">
        </div>
      </div>

    </section>
@endsection

@section('script-section')

<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="{{ asset('public/js/tableSort.js') }}"></script>

<script type="text/javascript">
$(function(){
    $("#loadtable").tablesorter({headers: {2: {sorter: false}}});
  });
</script>
<script type="text/javascript">

  $(document).ready(function(){
  $('#hideid').val($('#loadtable tr:last').attr('id'));
   $(document).on('click','#btn-more',function(){
      var id = $(this).data('id');

      var perPage = $("#perPage").val();
    
       $("#btn-more").html("Loading....");
       $.ajax({
           url : '{{route("loadProductCategory")}}',
           method : "POST",
           //data : {id:id, _token:"{{csrf_token()}}"},
           data : {'id':id, '_token': '<?php echo csrf_token();?>'},
           dataType : "text",
           success : function (data)
           {
              if(data != '') 
                {
                    $('#product_category').append(data);
                    id= id-perPage;
                    $('#btn-more').data('id', id);
                    //$("#btn-more").data(id);
                    if($("#lastcategory").val()!='')
                    {
                      lastid = $('#loadtable tr:last').attr('id');
                      //alert(lastid);
                      if(lastid == $("#lastcategory").val())
                      {
                        $('#btn-more').data('id', '0');
                        $('#btn-more').html("No More Data");
                        $('#hideid').val(0);
                      }
                      else{
                        $("#btn-more").html("Load More");
                        $('#hideid').val($('#loadtable tr:last').attr('id'));
                      }
                    }
                    else{
                       $("#btn-more").html("Load More");
                       $('#hideid').val($('#loadtable tr:last').attr('id'));
                    }

                    $(function(){
                      $("#loadtable").tablesorter();
                      $("#loadtable").trigger("update"); 
                      $('.table').trigger('footable_initialize');
                    });
                }
                else
                {
                    $('#btn-more').html("No More Data");
                }
           }
       });
   }); 

});
</script>

<script type="text/javascript">
      $(document).ready(function() {
        $("#addproductCategory").validate({
            rules: {
                {{ $encrypted['product_category_name'] }}: {
                    required: true
                },
              },
              messages: {
                {{ $encrypted['product_category_name'] }}: {
                    required: "Please enter product category"
                },
              }
        });
      });

function change_category_status(status,id)
  {
    if(confirm('Are you sure you want to change category status?'))
      {
        if(status==0)
        {
          status = 1;
        }
        else if(status==1)
        {
          status = 0;
        }
        $.post('<?php echo route('product-category-stat-change')?>', {
          'id': id,
          'status': status,
          '_token': '<?php echo csrf_token();?>',
          }, function(data) {
             $("#stat"+id).html(data);
             $('.table').trigger('footable_initialize');
         })
      }
      return false;
  } 

</script>
<script src="{{ asset('public/js/addcustomer.js') }}"></script>
@endsection


