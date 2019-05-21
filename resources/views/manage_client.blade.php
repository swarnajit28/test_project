@extends('layouts.layout')
@section('title')
  <title>Manage Customer</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/managecustomer.css') }}" rel="stylesheet">

@endsection

@section('content')
<!-- {{$postArr['status']}} -->
<section class="content content-custom">
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
        <form id="managecustomer" action="{{route('list-client')}}">
          <input type="hidden" name="id" id="hideid">
          <div class="field-first-grp  @if(Auth::user()->user_type != 'SP') field-first-grplist-p @endif">
            <p>Search By</p>
            <div class="input-field">
              <input type="text" name="company_name" placeholder="Company Name" class="form-control" value="{{$postArr['company_name']}}">
            </div>
            <div class="input-field">
              <input type="text" name="contact_name" placeholder="Contact Name" class="form-control" value="{{$postArr['contact_name']}}">
            </div>
            @if (Auth::user()->user_type != 'SP')
            <div class="viewgrp-dropdownblk select-sales-persondrop">
            <label>Select Sales Person</label>
            <div class="viewgrp-dropdown dropdown">            
                  <div class="magicsearch-wrapper">
                    <select class="form-control" name="sales_person">
                      <option value="">Select</option>
                      @if(count($salesperson)>0)
                      @foreach($salesperson as $saleperson)
                      <option value="{{$saleperson['id']}}" @if($postArr['sales_person']==$saleperson['id']) selected="" @endif>{{$saleperson['display_name']}}</option>
                      @endforeach
                      @endif
                    </select>
                  </div>
              </div>            
            </div>
            @endif
          </div>
          <div class="field-second-grp field-second-grplist-p">
            <div class="input-field">
              <input type="text" name="phone" placeholder="Phone" class="form-control" value="{{$postArr['phone']}}" onKeyUp="$(this).val($(this).val().replace(/[^\d]/ig, ''))">
            </div>
            <div class="input-field">
              <input type="text" name="email" placeholder="Email" class="form-control" value="{{$postArr['email']}}">
            </div>
            <div class="viewgrp-dropdownblk">
              <label>Status</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="status">
                    <option value="2" @if($postArr['status']==2) selected="" @endif>Select</option>
                    <option value="1" @if($postArr['status']==1) selected="" @endif>Active</option>
                    <option value="0" @if($postArr['status']==0) selected="" @endif>Inactive</option>
                  </select>
                </div>
                <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
              </div>
            </div>
            <button type="submit" name="srch_btn" value="sbt" class="btn btn-primary" onclick="resethideid()"><i class="fa fa-search" aria-hidden="true"></i></button>
          </div>
          <div class="field-second-grp field-second-grplist-p">
          <div class="input-field">
                <input type="text" name="registration_number" placeholder="Registration Number" class="form-control" value="{{$postArr['registration_number']}}">
              </div>
        </div> 
        </form>
        
      </div>
      
      <div class="table-part wid-load-more manage_client_table">
        <table class="table" id="loadtable">
          <thead>
            <tr>
              <th data-toggle="true">Company Name</th>
              <th data-hide="phone,tablet" id="compname">Reg.no</th>
              <th data-hide="phone,tablet">Conatact Name</th>
              <th data-hide="phone,tablet">Phone</th>
              <th data-hide="phone,tablet">Email</th>
              <th data-hide="phone">Total Leads</th>
              <th data-hide="phone" class="text-center" data-sorter="false">Status</th>
              <th class="text-center" data-sorter="false">Action</th>
            </tr>
          </thead>
          <tbody id="load-data">
            @if(count($all_data) > 0)

              @foreach($all_data as $customer_data)
              
            <tr>
              <?php  $text = "";?>
                <?php
                if ($customer_data['outside_FA_updated_on'] != '') {
                    // $status="bb";
                    $status = date('Y-m-d', strtotime($customer_data['outside_FA_updated_on']));
                    $new_date = date('Y-m-d', strtotime('-7 days'));
                    if ($status > $new_date) {
                        $text = '! Warning Outside of FA';
                    }
                } else {
                    $text = "";
                }
                ?>
              <td>{{$customer_data['company_name']}} &nbsp; &nbsp; <span class="fa_color">{{$text}} </span></td>
              <td>{{$customer_data['registration_number']}}</td>
              <td>{{$customer_data['contact_person_name']}}</td>
              <td>{{$customer_data['contact_person_phone1']}}</td>
              <td>{{$customer_data['contact_person_email1']}}</td>
              <td>{{$customer_data['totlead']}}</td>
              <td class="text-center" id="stat{{$customer_data['id']}}">                
                  <button class="tick" onclick="statuscustomer('{{Crypt::encrypt($customer_data['id'])}}','{{$customer_data['is_active']}}','{{$customer_data['id']}}');"> <i class="fa @if($customer_data['is_active']==1) fa-check @else fa-close @endif" @if($customer_data['is_active']==1) title="Active" @else title="Inactive" @endif aria-hidden="true"></i></button>
              </td>
              <td class="text-center viewgrp-dropdown dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                <ul class="dropdown-menu">
                  <li><a href="{{ route('edit-customer',['id' => Crypt::encrypt($customer_data['id']) ]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
                  @if(Auth::user()->user_type == 'SP'||Auth::user()->user_type =='MA'||Auth::user()->user_type =='LM'||Auth::user()->user_type =='SM')
                  @if($customer_data['is_active']==1)
                  <li><a href="{{ route('customer-add-lead',['id' => Crypt::encrypt($customer_data['id']),'comp_name'=>$customer_data['company_name'] ]) }}" ><i class="fa fa-user-plus" aria-hidden="true"></i> Add Lead</a></li>
                  @endif
                  
                  @endif
                  @if(Auth::user()->user_type == 'MA')<li><a onclick="return confirm('Are you sure want to delete this client?')" href="{{route('delete-customer',['id' => Crypt::encrypt($customer_data['id']) ])}}"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>@endif
                </ul>
              </td>
            </tr>
              @endforeach
            @else
              <tr class="ndf">
                <td colspan="8"><span class="text-margin centertext">No record(s) found</span></td>
              </tr>
            @endif
        
          </tbody>
        </table>
        @if(isset($perPage)) 
          <div id="remove-row">
              <div class="load-more">
                  <button id="btn-more" data-id="{{(isset($customer_data['id']) ? $customer_data['id'] : '')}}" > @if(count($all_data) > 0) Load More @else No More Data @endif</button>
                  <!-- <a href="#">Load More</a> -->
              </div>
          </div>
        @endif 
      </div>
      <input type="hidden" id="perPage" value="{{(isset($perPage) ? $perPage : '')}}">

</section>

@endsection

@section('script-section')  
  <script src="{{ asset('public/js/managecustomer.js') }}"></script>
  <script src="{{ asset('public/js/tableSort.js') }}"></script>
  <script>
    $(document).ready(function(){
      $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
    });
    function resethideid()
    {
      $("#hideid").val('');
    }
    
    $(document).ready(function() 
    { 
        $('.tick a').click(function() { return false; }); 
        $("#loadtable").tablesorter( {sortList: [[0,0]]}); 
    } 
    )  
    
    
    function statuscustomer(id,status,rowid)
    {
     
      if(confirm('Are you sure you want to change client status?'))
      {
        if(status==0)
        {
          status = 1;
        }
        else if(status==1)
        {
          status = 0;
        }

         $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          })
         $.ajax({
             url : '{{route("customerstatuschange")}}',
             method : "POST",
             data : {'id':id,'status': status,'_token': '<?php echo csrf_token();?>'},
             //dataType : "text",
             success : function (data)
             {
              /*console.log(data);
                if(data != '') 
                {*/
                  $("#stat"+rowid).html(data);
                  $('.table').trigger('footable_initialize');
                /*}*/
             }
         });
      }
      return false;
    }
  $(document).ready(function(){
     $(document).on('click','#btn-more',function(){
        var id = $(this).data('id');
        $("#hideid").val($(this).data('id'));
        var perPage = $("#perPage").val();
         $("#btn-more").html("Loading....");
         $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
         $.ajax({
             url : '{{route("loadclient")}}',
             method : "POST",
             data : $('#managecustomer').serialize(),
             //dataType : "text",
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
  
  </script>
@endsection
