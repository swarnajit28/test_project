@extends('layouts.layout')
@section('title')
  <title>Activity Manager - By Product</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/leaddetails.css') }}" rel="stylesheet">

@endsection

@section('content')


<section class="content content-custom">
      <div class="manage-user-page lead-list-page">
        
        <div class="view-by">
          <form action="{{route('activityManagerByLead')}}" method="POST" class="" id="listlead">
            {{ csrf_field() }}
<!--          <input type="hidden" name="id" id="hideid">-->
            <div class="field-first-grp field-first-grplist-p">
              <p>Search By</p>
              <div class="viewgrp-dropdownblk product-drop">
              <label>Select Lead</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="lead">
                    <option value="0">Select</option>
                    @if(count($active_lead)>0)
                    @foreach($active_lead as $lead1)
                    <option value="{{$lead1['lead_id']}}" @if($postArr['lead']== $lead1['lead_id']) selected="" @endif>L00{{$lead1['lead_id']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
               
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
          </div>
          <div class="field-second-grp field-second-grplist-p">
            
              
          <div class="viewgrp-dropdownblk">
            <label>Activity Type</label>
            <div class="viewgrp-dropdown">
              <div class="magicsearch-wrapper">
                <select class="form-control" name="activity_type">
                  <option value="0">All Activity</option>
                  <option value="1" @if($postArr['activity_type']==1) selected="" @endif>Automatic</option>
                  <option value="2" @if($postArr['activity_type']==2) selected="" @endif>Manual</option>
                </select>
              </div>
              <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
            </div>
          </div> 

          <div class="viewgrp-dropdownblk product-drop">
              <label>Status</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="activity_modes">
                    <option value="99999">All Status</option>
                    <option value="0" @if($postArr['activity_modes']== 0) selected="" @endif>New</option>
                    @if(count($activity_modes)>0)
                    @foreach($activity_modes as $activity_mode)
                    <option value="{{$activity_mode['id']}}" @if($postArr['activity_modes']== $activity_mode['id']) selected="" @endif>{{$activity_mode['loan_type']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
               
              </div>
            </div>
          <button type="submit" name="srch_btn" value="sbt" class="btn btn-primary" onclick="resethideid()"><i class="fa fa-search" aria-hidden="true"></i></button>
          </div>
          
            
          </form>
          
        </div>
        <div class="modal fade activity-details" role="dialog">
          
        </div>

        <div class="export-assign">
          <div class="viewgrp-dropdownblk">
            <label>Export As</label>
            <div class="viewgrp-dropdown dropdown">
              
<span class="dropdown-toggle pdf-icon-custom" data-toggle="dropdown" id="downloadcsv"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span>
              
            </div>
          </div>
<!--          <a href="#" class="assign-btn">Assign</a>-->
        </div>
        <div class="lead-detail-activity-table">
          <div class="table-part wid-load-more">
            <table class="table tablesorter " id="loadtable">

              <thead id="csvtr">
                <tr id="acttr">

                  <th data-toggle="true">Lead ID</th>
                  <th data-hide="phone,tablet">Sales Person</th>
                  <th data-hide="phone,tablet">Customer Name</th>
                  <th data-hide="phone,tablet">Valuation</th>
                  <th data-hide="phone,tablet">Status</th>
                  <th data-hide="phone">Activity Type</th>
                  <th data-hide="phone" class="sorter-shortDate dateFormat-ddmmyyyy">Date Time</th>
                  <th class="text-center" data-sorter="false">Action</th>
                </tr>
              </thead>
              <tbody id="load-data">
                @if(count($allLead) > 0) 
                @foreach($allLead as $leads)
                @if($leads['valuation']!='') 
                  @php
                  $num = str_replace(',', '', number_format($leads['valuation'],2))
                  @endphp
                @endif 

                @if($leads['valuation']=='') 
                  @php
                  $num = '0.00' 
                  @endphp
                @endif
                 <tr id="{{$leads['lead_id']}}" class="note-row">
                  <td>L00{{$leads['lead_id']}}</td>
                  <td>{{$leads['display_name']}}</td>
                  <td class="widthset">{{$leads['company_name']}}</td>
                  <td>{{$num}}</td>
                  <td>@if($leads['status']=='')New @else{{$leads['status']}}@endif</td>
                  <td>@if($leads['last_activity_type']==1) Automatic @else Manual @endif</td>
                  <td>@if($leads['last_activity_time']!=''){{date('d/m/Y h:i A',strtotime($leads['last_activity_time']))}}@endif</td>
                  <td class="text-center viewgrp-dropdown">
<!--                    <a class="table-link" href="#"  data-toggle="modal" data-target=".activity-details" onclick="gotomodal('{{$leads['lead_id']}}','{{$leads['last_activity_type']}}','{{$leads['company_name']}}','{{$leads['status']}}','{{$leads['last_activity_id']}}','{{date('d.m.Y h:i A',strtotime($leads['last_activity_time']))}}','{{$leads['last_activity_note']}}')"><i class="fa fa-eye" aria-hidden="true"></i> View</a>-->
                    <a class="table-link" href="{{url('lead_details/'.encrypt($leads['lead_id']))}}"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                    
                  </td>
                </tr>
                @endforeach
                                
                @else
                  <tr  class="ndf">
                    <td colspan="8"><span class="text-margin centertext">No record(s) found</span></td>
                  </tr>
                @endif
                
              </tbody>
            </table>
            @if(isset($perPage)) 
              <div id="remove-row">
                  <div class="load-more">
                      <button id="btn-more"> Load More </button>
                      <!-- <a href="#">Load More</a> -->
                  </div>
              </div>
            @endif 
          </div>
        </div>
      </div>
    </section>

@endsection

@section('script-section')
  <script src="{{ asset('public/js/leaddetails.js') }}"></script>  
  <script src="{{ asset('public/js/tableSort.js') }}"></script>
     
  <script type="text/javascript">
  function resethideid()
  {
    $("#hideid").val('');
  }
  
      
$(function() {
  // call the tablesorter plugin
  $("#loadtable").tablesorter({
    theme : 'blue',

    dateFormat : "mmddyyyy", 
    headers: {
      6: { sorter: "shortDate" },sortList: [[0,1]]} 
    })

  });
  
  $(document).ready(function(){
     $(document).on('click','#btn-more',function(){
//       var tdata = $('#load-data tr:last-child td#last_id').addClass('hi');
       var max = 99999999;
        $('.note-row').each(function() {
        max = Math.min(this.id, max);
        });
        var last_id=max;
         $("#btn-more").html("Loading....");
         $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
         $.ajax({
             url : '{{route("loadAudiTrailByLead")}}',
             method : "POST",
              data : $('#listlead').serialize() + "&last_id="+last_id,
//             data : $('#listlead').serialize() + "&kk=1",
             success : function (data)
             {
                if(data != '') 
                {
                   // $('#last_id').remove();
                    $('#load-data').append(data);
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


    function download_csv(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV FILE
        csvFile = new Blob([csv], {type: "text/csv"});

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // We have to create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Make sure that the link is not displayed
        downloadLink.style.display = "none";

        // Add the link to your DOM
        document.body.appendChild(downloadLink);

        // Lanzamos
        downloadLink.click();
    }

    function export_table_to_csv(html, filename) {
      var csv = [];
      var rows = document.querySelectorAll(".table-part table tr");
      
        for (var i = 1; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
            for (var j = 0; j < cols.length-1; j++) 
                row.push(cols[j].innerText);
            
        csv.push(row.join(","));    
      }

        // Download CSV
        download_csv(csv.join("\n"), filename);
    }

    document.querySelector("#downloadcsv").addEventListener("click", function () {
       
      $("#acttr").hide();
      var deltr = "deltr";
      $("#csvtr").append('<tr id='+deltr+'><th>Lead ID</th><th>Sales Person</th><th>Customer Name</th><th>Valuation</th><th>Status</th><th>Activity Type</th><th>Date Time</th><th></th></tr>');
        var html = document.querySelector(".table-part table").outerHTML;
      export_table_to_csv(html, {{date('Ymdhis')}}+"activity_manager_by_leads.csv");
      $("#deltr").remove();
      $("#acttr").show();

    });

  </script>
@endsection
