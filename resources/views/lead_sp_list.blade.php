@extends('layouts.layout')
@section('title')
  <title>List Of Leads</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/leaddetails.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">

@endsection

@section('content')
<!-- {{print_r($postArr)}} -->
<!-- {{$lastlead}} -->
<section class="content content-custom">
     @if (session('success_message'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ Session::get('success_message') }}
    </div> 
    @endif
      <div class="manage-user-page lead-list-page">
        
        <div class="view-by">
          <form action="{{route('search-sp-lead')}}" method="POST" class="" id="listlead">
            {{ csrf_field() }}
          <input type="hidden" name="id" id="hideid">
          <input type="hidden" value="{{$salperson}}" name="salperson">
          <input type="hidden" value="{{$lastlead}}" id="lastlead" name="lastlead">
          
            <div class="field-first-grp ">
              <p>Search By</p>
              <div class="input-field">
                <input type="text" name="customer_name" placeholder="Company Name" class="form-control" value="{{$postArr['customer_name']}}">
              </div>
              
              <div class="input-field">
                <input type="text" name="lead_id" placeholder="Lead ID" onKeyUp="$(this).val($(this).val().replace(/[^\d]/ig, ''))" class="form-control" value="{{$postArr['lead_id']}}">
              </div>
          </div>
          <div class="field-second-grp field-second-grplist-p">
            <div class="viewgrp-dropdownblk product-drop">
              <label>Products</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="product">
                    <option value="0">Select</option>
                    @if(count($products)>0)
                    @foreach($products as $product)
                    <option value="{{$product['id']}}" @if($postArr['product']==$product['id']) selected="" @endif>{{$product['prod_name']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
                <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
              </div>
            </div>
            <div class="viewgrp-dropdownblk datepicker-part">
              <label>Activity Date From</label>
              <div class="datepicker-block">
                <input class="form-control datepicker" readonly="" value="{{$postArr['fromdate']}}" name="fromdate" placeholder="From">
              </div>
              <div class="datepicker-block datepicker-block-to">
                <input class="form-control datepicker" readonly="" value="{{$postArr['todate']}}" name="todate" placeholder="To">
              </div>
            </div>  
            <div class="viewgrp-dropdownblk">
              <label>Status</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="status">
                    <option value="1" @if($postArr['status']==1) selected="" @endif>Active</option>
                    <option value="0" @if($postArr['status']==0) selected="" @endif>Inactive</option>
                  </select>
                </div>
                <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
              </div>
            </div> 
            <button type="submit" name="srch_btn" value="sbt" class="btn btn-primary" onclick="resethideid()"><i class="fa fa-search" aria-hidden="true"></i></button>
          </div>
          
            
          </form>
          
        </div>
        
        <div class="lead-detail-activity-table">
          <div class="table-part wid-load-more">
            <table class="table tablesorter" id="loadtable">
              <thead>
                <tr style="cursor: pointer;">
                  <th data-toggle="true" id="leadid">Lead ID</th>
                  <th data-hide="phone,tablet" id="compname">Company Name</th>                  
                  <th data-hide="phone,tablet" id="prodnum">No. Of Products</th>
                  <th data-hide="phone,tablet" id="leadval">Lead Value</th>
                  <th data-hide="phone" id="stat">Status</th>
                  <th data-hide="phone" id="lastupdate" class="{sorter: 'shortDate'}">Last Updated</th>
                  <th class="text-center">Action</th>

                </tr>
              </thead>
              <tbody id="load-data">
                @if(count($lead_details) > 0) 
                @foreach($lead_details as $leads)
                <tr id="{{$leads['id']}}">
                  <td><a class="table-link" href="{{route('lead-details',['id' => Crypt::encrypt($leads['id']) ])}}">L00{{$leads['id']}}</a></td>
                  <td>{{$leads['company_name']}}</td>
                  
                  <td>{{$leads['totprod']}}</td>
                  <td>{{number_format($leads['valuation'],2)}}</td>
                  <td>@if($leads['lead_strength_id']!='0') {{$leads['loan_type']}} @else New @endif</td>
                  <td>@if($leads['updated_at']!=''){{date('d/m/Y',strtotime($leads['updated_at']))}}@endif</td>
                  <td class="text-center viewgrp-dropdown dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu aa">
                      @if($leads['is_completed']=='0')
                      <li><a href="{{route('edit-lead',['id' => Crypt::encrypt($leads['id']) ])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
                      @endif
                      <li><a href="{{route('lead-details',['id' => Crypt::encrypt($leads['id']) ])}}"><i class="fa fa-eye" aria-hidden="true"></i>View</a></li>
                      @if (Auth::user()->user_type == 'SP' && $leads['is_completed']=='0')
                      <li><a href="javascript:void(0)" data-toggle="modal"  id="{{($leads['id'])}}" onClick="openModal({{$leads['id']}})"><i class="fa fa-plus" aria-hidden="true"></i>Add Activity</a></li>
                      @endif
                    </ul>
                  </td>
                </tr>
                @endforeach
                @else
                  <tr class="ndf">
                    <td colspan="7"><span class="text-margin centertext">No record(s) found</span></td>
                  </tr>
                @endif
                
              </tbody>
            </table>
            @if(isset($perPage) && count($lead_details) > 0) 
              <div id="remove-row">
                  <div class="load-more">
                      <button id="btn-more" data-id="{{(isset($leads['id']) ? $leads['id'] : '')}}" > Load More </button>
                      <!-- <a href="#">Load More</a> -->
                  </div>
              </div>
            @endif 
          </div>
            
            <!-- <div  id="myModal" class="modal dynamicmodal activity-details fade activity-details-withfield" role="dialog">
            <div class="modal-dialog">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="close-icon"></i>
                        </button>
                        <h4 class="modal-title">Add New Activity</h4>
                    </div>

                    <div class="modal-body">
                        <form class="row" action="{{route('add_activity')}}" id="add_activity" method="post">
                            {{ csrf_field() }}
                            <div class="abc-supplies text-center" >
                                <span class="supplies-number">L00</span>
                                <span class="supplies-name"></span>
                            </div>
                            <br>
                            <br>
                             
                            <input type="hidden" name="lead_id" id="lead_id" value="">
                            <div class="form-group col-sm-12 text-center">
                                <div class="viewgrp-dropdownblk">
                                    <label>Select Mode</label>
                                    <div class="viewgrp-dropdown">
                                        <div class="magicsearch-wrapper">
                                            <select class="form-control" name="act_mode">

                                                 @if(!empty($all_modes))
                                                @foreach($all_modes as $key => $value)
                                                <option value="{{$all_modes[$key]['id']}}">{{$all_modes[$key]['activity_mode']}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group col-sm-12">
                                <label>Activity Note</label>
                                <textarea rows="6" class="form-control" name="activity_note"></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary add-btn">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div> -->
            
        </div>
      </div>
      <input type="hidden" id="perPage" value="{{(isset($perPage) ? $perPage : '')}}">
    </section>

@endsection

@section('script-section')
  <script src="{{ asset('public/js/leaddetails.js') }}"></script>  
  <script src="{{ asset('public/js/validate.js') }}"></script>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js"></script>
  <script type="text/javascript">
     
  function resethideid()
  {
    $("#hideid").val('');
  }

  
 /*function openModal(id)
      {
          //alert(id);
         var newid = $('.dynamicmodal').attr('id', 'myModal'+id);
         var dynamicid = $(newid).attr('id');
         //alert(newid);
          $('#'+dynamicid).modal('show');
          $('.abc-supplies .supplies-number').text('L00'+id);
          $("#lead_id").val(id);
      }
  
  
 $(document).ready(function() {
    $("#add_activity").validate({
        rules: {
           
           activity_note: {
                required: true,
            },
        },
        messages: {
         
           activity_note: {
                required: "Please enter Activity note",
            },

        }
    });
}); */

</script>
<script type="text/javascript">
  
  $.tablesorter.addParser({ 
    // set a unique id 
    id: 'dateMS', 
    is: function(s) { 
        // return false so this parser is not auto detected 
        return false; 
    }, 
    format: function(s) { 
        var date = s.split('/');
        return new Date(date[2],date[1],date[0]).getTime();
    }, 
    // set type, either numeric or text 
    type: 'numeric' 
}); 

</script>

<script type="text/javascript">
  $(function(){
    $("#loadtable").tablesorter(
      {headers: {6: {sorter: false},5: { 
                sorter:'dateMS' 
            }}}
      );
  });


  $(document).ready(function(){
  $('#hideid').val($('#loadtable tr:last').attr('id'));
     $(document).on('click','#btn-more',function(){
        var id = $(this).data('id');
        //alert(id);
        //$("#hideid").val($(this).data('id'));
        
        var perPage = $("#perPage").val();
         $("#btn-more").html("Loading....");
         $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
         $.ajax({
             url : '{{route("loadsplead")}}',
             method : "POST",
             data : $('#listlead').serialize(),
             //dataType : "text",
             success : function (data)
             {
                if(data != '') 
                {
                    $('#load-data').append(data);
                    id= id-perPage;
                    $('#btn-more').data('id', id);
                    //$("#btn-more").data(id);
                    if($("#lastlead").val()!='')
                    {
                      lastid = $('#loadtable tr:last').attr('id');
                      if(lastid == $("#lastlead").val())
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
                      $('.table').trigger('footable_initialize')
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
@endsection
