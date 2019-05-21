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
        $expense_type_name      = $data['expense_type_name'];
        $expense_type_status        = $data['expense_type_status'];
       
       }else
       {
        $expense_type_name        = '';
        $expense_type_status        = '';
       }

       ?> 
       @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
       @endif
          <form action="{{route('addExpenseType')}}" method="POST" id="addexpenseType">
           {{ csrf_field() }}
            
            <div class="field-first-grp">
            <p>Name:</p>
              <div class="input-field">
                <input type="text" name="{{ $encrypted['expense_type_name'] }}"  id="{{ $encrypted['expense_type_name'] }}"  placeholder="Name" class="form-control" value="<?php echo isset($category_details->expense_type)?$category_details->expense_type:''; ?>">
                <input type="hidden" name="{{ $encrypted['expense_type_id'] }}" class="form-control" value="<?php echo isset($category_details->id)?$category_details->id:''; ?>"> 
                @if ($errors->has('expense_type_name'))
                  <span class="error">{{ $errors->first('expense_type_name') }}</span>
                @endif
                
              </div>
               
              <div class="viewgrp-dropdownblk">
                <label>Status</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                    <select class="form-control" name="{{ $encrypted['expense_type_status'] }}">
                      <option value="1"  <?php if(isset($category_details->is_active) && ($category_details->is_active == '1')){ echo 'selected' ; }?>>Active</option>
                      <option value="0" <?php if(isset($category_details->is_active) && ($category_details->is_active == '0')){ echo 'selected' ; }?>>Inactive</option>
                    </select>
                  </div>
                  <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
                </div>
              </div>
           @if ( Request::segment(1)=='editExpenseType')
              <button type="submit" class="btn btn-primary" onclick="validateForm()" id="submit">Edit</button>
           @else
           <button type="submit" class="btn btn-primary" onclick="validateForm()" id="submit">Add</button>
           @endif
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
            <tbody id="expense_type">
            <input type="hidden" name="id" id="hideid">
               @if(count($category) >0)
               @foreach($category as $data)
                <tr id="{{$data['id']}}">
                <td data-hide="phone,tablet">{{$data['expense_type']}}</td>
                <td data-hide="phone,tablet" class="text-center" id="stat{{$data['id']}}">@if($data['is_active'] == '1')<i class="fa fa-check" aria-hidden="true" title="Active" style="cursor: pointer;" onclick="change_category_status('{{$data['is_active']}}','{{$data['id']}}');"></i>@else <i class="fa fa-times" aria-hidden="true" title="Inactive" style="cursor: pointer;" onclick="change_category_status('{{$data['is_active']}}','{{$data['id']}}');"></i> @endif
                </td>
              <td class="text-center viewgrp-dropdown dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{route('editExpenseType',['id' => Crypt::encrypt($data['id'])])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>

                  </ul>
                </td>
              </tr>
              @endforeach
              @else
              <tr><td colspan="4">No records found</td></tr>
              @endif
       
            
            </tbody>
          </table>
          <div id="remove-row">
              <div class="load-more">
                  <button id="btn-more"> @if(count($category) > 0) Load More @else No More Data @endif</button>
                  <!-- <a href="#">Load More</a> -->
              </div>
          </div>   
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
  
   $(document).on('click','#btn-more',function(){
      var id = $('#loadtable tr:last').attr('id');
     // alert(id);
      if (typeof(id) != "undefined")
      {
       $("#btn-more").html("Loading....");
       $.ajax({
           url : '{{route("loadExpenseType")}}',
           method : "POST",
           //data : {id:id, _token:"{{csrf_token()}}"},
           data : {'id':id, '_token': '<?php echo csrf_token(); ?>'},
           dataType : "text",
           success : function (data)
           {
              if(data != '') 
                {
                    $('#expense_type').append(data);
                    if($("#lastcategory").val()!='')
                    {
                      lastid = $('#loadtable tr:last').attr('id');
                      //alert(lastid);
                      if(lastid == $("#lastcategory").val())
                      {
                        $('#btn-more').html("No More Data");
                      }
                      else{
                        $("#btn-more").html("Load More");
                      }
                    }
                    else{
                       $("#btn-more").html("No More Data");
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
       }
   }); 

});
</script>

<script type="text/javascript">
      $(document).ready(function() {
        $("#addexpenseType").validate({
            rules: {
                {{ $encrypted['expense_type_name'] }}: {
                    required: true
                },
              },
              messages: {
                {{ $encrypted['expense_type_name'] }}: {
                    required: "Please enter expense type"
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
        $.post('<?php echo route('expenseTypeStatusChange')?>', {
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


