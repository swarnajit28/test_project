@extends('layouts.layout')
@section('title')
  <title>Manage Lead</title>
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/manageproduct.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
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
        $loan_type        = $data['loan_type'];
        $loan_status        = $data['loan_status'];
       
       }else
       {
        $loan_type        = '';
        $loan_status        = '';
       }

       ?> 
       @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
       @endif
          <form action="{{route('add_master_loan_type')}}" method="POST" id="loanForm">
           {{ csrf_field() }}
            
            <div class="field-first-grp">
            <p>Name:</p>
              <div class="input-field">
                <input type="text" name="{{ $encrypted['loan_type'] }}"  id="{{ $encrypted['loan_type'] }}"  placeholder="Name" class="form-control" value="<?php echo isset($loan_details->loan_type)?$loan_details->loan_type:''; ?>">
                @if(isset($encrypted['loan_type_id']))
                <input type="hidden" name="{{ $encrypted['loan_type_id'] }}" class="form-control" value="<?php echo isset($loan_details->id)?$loan_details->id:''; ?>"> 
                @endif
                @if ($errors->has('loan_type'))
                 <span class="error">{{$errors->first('loan_type')}}</span>   
                 @endif
              </div>
              
                <div class="viewgrp-dropdownblk">
                <label>Status</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                    <select class="form-control" name="{{ $encrypted['loan_status'] }}">
                      <option value="1" <?php if(isset($loan_details->is_active) && ($loan_details->is_active == '1')){ echo 'selected' ; }?>>Active</option>
                      <option value="0" <?php if(isset($loan_details->is_active) && ($loan_details->is_active == '0')){ echo 'selected' ; }?>>Inactive</option>
                    </select>
                  </div>
                  <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
                </div>
              </div>
            
            @if ( Request::segment(1)=='edit_master_loan_view')  
              <button type="submit" class="btn btn-primary" onclick="validateForm()" id="submit">Edit</button>
           @else
           <button type="submit" class="btn btn-primary" onclick="validateForm()" id="submit">Add</button>
              @endif
            </div>
           <div class="field-second-grp">
               <div class="masterloan-details-eithcolor">
                   <p>Details:</p>
                   <div class="input-field">
                       <textarea class="form-control" rows="4" cols="30" name="{{ $encrypted['key_details']}}" > <?php echo isset($loan_details->key_details) ? $loan_details->key_details : ''; ?> </textarea>
                   </div>
               </div>
               <div class="masterloan-details-eithcolor">
                   <p>PICK COLOR:</p>
                   <div class="input-field pick-color-field">
                       <input type="color" name="{{ $encrypted['color_code']}}" id="html5colorpicker"  value="<?php echo isset($loan_details->color_code)?$loan_details->color_code:'#ff0000'; ?>">    
<!--                       <input type="color" name="{{ $encrypted['color_code']}}" id="html5colorpicker" onchange="clickColor(0, -1, -1, 5)" value="#ff0000">    -->
                   </div>
               </div>
           </div>
              
          </form>
          
        </div>
        
        <div class="table-part wid-load-more">
          <table class="table tablesorter masterleadtype-table" id="loadtable">
            <thead>
              <tr>
                <th data-toggle="true">Name</th>
                <th data-hide="phone,tablet" class="text-center" data-sorter="false">Color</th>
                <th data-hide="phone,tablet" class="text-center" data-sorter="false">Description</th>
                <th data-hide="phone,tablet" class="text-center" data-sorter="false">Status</th>
                <th class="text-center" data-sorter="false">Action</th>
              </tr>
            </thead>
            <tbody id="load-data">
            <input type="hidden" name="id" id="hideid">
            @if(count($loan)>0)
            @foreach($loan as $data)
           
              <tr id="{{$data['id']}}" class="note-row">
                <td>{{ $data['loan_type'] }}</td>
                
                <td style="text-align: center;"><input type="color" value="{{ $data['color_code'] }}" disabled></td>
                <!-- <td>
                  <span style="text-align: center; background-color: {{ $data['color_code'] }}"></span>
                </td> -->
                <td style="text-align: center;">{{ $data['key_details'] }}</td>
                <td class="text-center" id="stat{{$data['id']}}">@if($data['is_active'] == '1')<i class="fa fa-check" aria-hidden="true" title="active" style="cursor: pointer;" onclick="change_loan_status('{{$data['is_active']}}','{{$data['id']}}');"></i>@else <i class="fa fa-times" aria-hidden="true" title="Inactive" onclick="change_loan_status('{{$data['is_active']}}','{{$data['id']}}');"></i> @endif
                </td>
              <td class="text-center viewgrp-dropdown dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{route('edit_master_loan_view',['id' => Crypt::encrypt($data['id'])])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
               <!--     <li><a href="javascript:void(0)" onclick="delete_loan('{{ $data['id'] }}')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li> -->
<!--                    <li><a href="javascript:void(0)" onclick="delete_loan('{{ $data['id'] }}')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>-->
                  </ul>
               <!--   <a href="{{route('edit_master_loan_view',['id' => Crypt::encrypt($data['id'])])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           
                  <a href="javascript:void(0)" onclick="delete_loan('{{ $data['id'] }}')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>  -->
                </td>
              </tr>
              @endforeach
             @else
             <tr class="ndf"><td colspan="3" align="center">No records found</td></tr>
             @endif
            </tbody>
          </table>
        @if(isset($page)) 
          <div id="remove-row">
              <div class="load-more">
                  <button id="btn-more"> @if(count($loan) > 0) Load More @else No More Data @endif</button>
                  <!-- <a href="#">Load More</a> -->
              </div>
          </div>
        @endif
<!--        <input type="hidden" id="perPage" value="{{(isset($page) ? $page : '')}}">-->
        </div>
      </div>

    </section>
 
@endsection

@section('script-section')

<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="{{ asset('public/js/tableSort.js') }}"></script>

<script type="text/javascript">
    
     $(document).ready(function() 
    { 
        $("#loadtable").tablesorter(); 
    } 
) 
    
      $(document).ready(function() {
        $("#loanForm").validate({
            rules: {
                {{ $encrypted['loan_type'] }}: {
                    required: true
                },
              },
              messages: {
                {{ $encrypted['loan_type'] }}: {
                required: "Please enter Lead Type"
                },
              }
        });
      });

      function delete_loan(id)
      {
       // alert(id);
        if (confirm('Are you sure you want to delete this?')) 
        {
          $.ajax({
              url: "{{ route('delete_master_loan') }}",
              type: "POST",
              data: {'id':id,'_token': '<?php echo csrf_token();?>'},  
              success: function (response) {
                  if(response>0)
                  {
                   document.location.href= "master_loan";
                   // redirect('/master_loan');
                  }
              }
          }); 
         }
      } 
      

      function change_loan_status(status,id)
      {
        if(confirm('Are you sure you want to change lead status?'))
          {
            if(status==0)
            {
              status = 1;
            }
            else if(status==1)
            {
              status = 0;
            }
            $.post('<?php echo route('loanstatuschange')?>', {
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

  <script type="text/javascript">

  $(document).ready(function(){
   $(document).on('click','#btn-more',function(){
   var max = 99999999;
        $('.note-row').each(function() {
        max = Math.min(this.id, max);
        });
        var id=max;
    
       $("#btn-more").html("Loading....");
       $.ajax({
           url : '{{route("loadloan")}}',
           method : "POST",
           //data : {id:id, _token:"{{csrf_token()}}"},
           data : {'id':id, '_token': '<?php echo csrf_token();?>'},
           dataType : "text",
           success : function (response)
           {
              if(response != '') 
              {
                  
                  $('#load-data').append(response); 

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

//function clickColor(hex, seltop, selleft, html5) {
//    var c, cObj, colormap, areas, i, areacolor, cc;
//    if (html5 && html5 == 5)  {
//        c = document.getElementById("html5colorpicker").value;
//    }
//    //alert(c);
//    var color = $("#html5colorpicker").val();
//    $("#color_value").val(color);
//    //alert(color);
//}  
  </script>

  <script src="{{ asset('public/js/manageproduct.js') }}"></script>
@endsection
