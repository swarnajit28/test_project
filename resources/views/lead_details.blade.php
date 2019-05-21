@extends('layouts.layout')
@section('title')
<title>Lead Details</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link href="https://rawgit.com/dragospaulpop/cdnjs/master/ajax/libs/jQuery.custom.content.scroller/3.1.11/jquery.mCustomScrollbar.css" rel="stylesheet"/>
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/leaddetails.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">

    @if (session('success_message'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ Session::get('success_message') }}
    </div> 
    @endif

    <div class="manage-user-page lead-details-page leadactivity-page">
        <div class="lead-person-details">
            <div class="lead-person-details-left">
                <div class="abc-supplies">
                    <span class="supplies-number">L00{{$lead_data['id']}}</span>
                    <span class="supplies-name">{{$customer_details['company_name']}}</span>
                </div>
                <h3><span>{{$contact_person_details['contact_person_name']}}</span> <sub class="person-post">Sales Executive</sub></h3>
                @if($contact_person_details['contact_person_email1']!='')
                <p><a href="#"><i class="fa fa-globe"></i> {{$contact_person_details['contact_person_email1']}}</a></p>
                @endif
                @if($contact_person_details['contact_person_phone1']!='')
                <p><i class="fa fa-phone"></i> {{$contact_person_details['contact_person_phone1']}}</p>
                @endif
            </div>
            <div class="lead-person-details-right">
                @if($lead_data['is_completed']==0)
                @if (Auth::user()->user_type != 'IT'&& Auth::user()->user_type != 'OM')
                    <a href="{{route('edit-lead',['id' => Crypt::encrypt($lead_data['id']) ])}}" class="edit-btn">Edit</a>
                @endif
                 @endif
                <!--<a href="#" class="edit-btn" id="act_details">Full Activity</a>-->
                @if (Auth::user()->user_type == 'SP' && $lead_data['is_completed']==0)
                  <a href="#" data-toggle="modal" data-target=".activity-details" class="activity-btn">Add Activity</a> 
                @endif  
            </div>
        </div>
        @if($lead_data['is_completed']==1)
        <div id="attachdoc-details">
          <div class="document-block" >
           <h4>Supported Documents</h4>
           @if(!empty($supportdoc))
           <ul>
            @foreach ($supportdoc as $key => $value)
            <li>Document{{$key+1}}<a href="{{asset('public/uploads/lead/')}}/{{($supportdoc[$key]['supporting_doc_scan_file_path'])}}" download><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            @endforeach
           </ul>
           @else
           No documents
           @endif
          </div>
        </div>
        @endif
        <div class="activity-notes">
            <span>Additional Information</span>
            <p>{{$lead_data['additional_info']}}</p>
        </div>
        <div class="lead-details-table">
            <div class="table-part">
                <table class="table tablesorter " id="prodtable">
                    <thead>
                        <tr>
                            <th data-toggle="true">Product Name</th>
                            <th data-hide="phone,tablet">Margin Value</th>
                            <th data-hide="phone,tablet">End Margin</th>
                            <th data-hide="phone">Anticipated Volume</th>
                            <th data-hide="phone">Gross Total</th>
                            <th>Net Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($leadproducts))
                        @foreach($leadproducts as $key => $value)
                        <tr>
                            <td>{{$existing_product[$key]['prod_name']}}</td>
                            <td>{{$leadproducts[$key]['margin_value']}}</td>
                            <td>{{$leadproducts[$key]['end_margin']}}</td>
                            <td>{{$leadproducts[$key]['quantity']}}</td>
                            <td>{{number_format($leadproducts[$key]['margin_value']*$leadproducts[$key]['quantity'],2)}}</td>
                            <td>{{number_format($leadproducts[$key]['end_margin']*$leadproducts[$key]['quantity'],2)}}</td>
                            
                        </tr> 
                        @endforeach
                        @else
                        <tr class="ndf"><td colspan="7">No record(s) found</td></tr>
                        @endif              
                    </tbody>
                </table>          
            </div>
        </div>
        <div class="view-by" id="fullactivity">
            <h2>Full Activity</h2>

            <form action="{{route('search-activity')}}" method="POST" class="">
                {{ csrf_field() }}
                <input type="hidden" value="{{Crypt::encrypt($lead_data['id'])}}" name="id">
                <div class="field-first-grp">
                    <p>Search By</p>                
                    <div class="viewgrp-dropdownblk">
                        <label>Activity Type</label>
                        <div class="viewgrp-dropdown">
                            <div class="magicsearch-wrapper">
                                <select class="form-control" name="act_type">
                                    <option value="0" @if($postArr['act_type']=='0') selected="" @endif>All</option>
                                    <option value="1" @if($postArr['act_type']=='1') selected="" @endif>Automatic</option>
                                    <option value="2" @if($postArr['act_type']=='2') selected="" @endif>Manual</option>
                                </select>
                            </div>
                            <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
                        </div>
                    </div>
                    <div class="viewgrp-dropdownblk datepicker-part">
                        <label>Activity Date From</label>
                        <div class="datepicker-block">
                            <input class="form-control datepicker" name="fromdate" value="{{$postArr['fromdate']}}" placeholder="From">
                        </div>
                        <div class="datepicker-block datepicker-block-to">
                            <input class="form-control datepicker" value="{{$postArr['todate']}}" name="todate" placeholder="To">
                        </div>
                    </div>      
                </div>
                <div class="field-second-grp">
                    <div class="viewgrp-dropdownblk">
                        <label>Select Mode</label>
                        <div class="viewgrp-dropdown">
                            <div class="magicsearch-wrapper">
                                <select class="form-control" name="act_mode">
                                    <option value="0">Select Mode</option>
                                    @if(!empty($all_modes))
                                    @foreach($all_modes as $key => $value)
                                    <option value="{{$all_modes[$key]['id']}}" @if($postArr['act_mode']==$all_modes[$key]['id']) selected="" @endif>{{$all_modes[$key]['activity_mode']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>
                    </div>              
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
            </form>

        </div>
        <div class="lead-detail-activity-table">
            <div class="table-part">
                <table class="table tablesorter " id="loadtable">
                    <thead>
                        <tr>
                            <th data-toggle="true" class="sorter-shortDate dateFormat-ddmmyyyy">Date Time</th>
                            <th data-hide="phone,tablet">Activity Type</th>
                            <th data-hide="phone">Mode</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($all_activity))
                        @foreach($all_activity as $key => $value)
                        <tr>
                            <td>{{date('d/m/Y h:i A',strtotime($all_activity[$key]['activity_time']))}}</td>
                            <td>@if($all_activity[$key]['activity_type']=='1') Automatic @else Manual @endif</td>
                            <td>@if($all_activity[$key]['activity_mode']!='') {{$all_activity[$key]['activity_mode']}} @else --- @endif</td>
                            <td>{{$all_activity[$key]['activity_note']}}</td>
                        </tr> 
                        @endforeach
                        @else
                        <tr class="ndf"><td colspan="4">No activities found</td></tr>
                        @endif                      
                    </tbody>
                </table>
                <!-- <div class="load-more">
                  <a href="#">Load More</a>
                </div> -->
            </div>
        </div>

        <div class="modal activity-details fade activity-details-withfield" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="close-icon"></i>
                        </button>
                        <h4 class="modal-title">Add New Activity</h4>
                    </div>

                    <div class="modal-body">
                        <form class="row" action="{{route('add_activity_lead_deatais_page')}}" id="add_activity" method="post">
                            {{ csrf_field() }}
                            <div class="abc-supplies text-center" >
                                <span class="supplies-number">L00{{$lead_data['id']}}</span>
                                <span class="supplies-name">{{$customer_details['company_name']}}</span>
                            </div>
                            <br>
                            <br>
                             
                            <input type="hidden" name="lead_id" value="{{$lead_data['id']}}">
                            <div class="form-group col-sm-12 text-center">
                                <div class="viewgrp-dropdownblk">
                                    <label>Select Mode</label>
                                    <div class="viewgrp-dropdown">
                                        <div class="magicsearch-wrapper">
                                            <select class="form-control" name="act_mode">
<!--                                                <option value="0">Select Mode</option>-->
                                                @if(!empty($all_modes))
                                                @foreach($all_modes as $key => $value)
                                                <option value="{{$all_modes[$key]['id']}}" @if($postArr['act_mode']==$all_modes[$key]['id']) selected="" @endif>{{$all_modes[$key]['activity_mode']}}</option>
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
        </div> 

    </div>

</section>
@endsection

@section('script-section')
<script src="{{ asset('public/js/leaddetails.js') }}"></script>
<script src="{{ asset('public/js/tableSort.js') }}"></script>

<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://rawgit.com/dragospaulpop/cdnjs/master/ajax/libs/jQuery.custom.content.scroller/3.1.11/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
$(function() {
  // call the tablesorter plugin
  $("#loadtable").tablesorter({
    theme : 'blue',

    dateFormat : "mmddyyyy", 
    headers: {
      0: { sorter: "shortDate" }} 
    });
  
  $("#prodtable").tablesorter({
    theme : 'blue',
    headers: {sortList: [[0,1]]} 
    })

  });

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
    $('#loadtable').DataTable({
        "scrollY": 200,
        "scrollX": true,
         "ordering": false,
         "paging": false,
         "info": false,
         "searching": false        
    });
});
$(window).load(function(){

      $(".dataTables_scrollBody").mCustomScrollbar({
        theme:"light-3",
        scrollButtons:{
          enable:false
        },
        mouseWheel:{ preventDefault: true },
        scrollbarPosition: 'inside',
        autoExpandScrollbar:true,
        theme: 'dark'
      });
    });

</script>

@endsection
