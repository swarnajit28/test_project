@extends('layouts.layout')
@section('title')
  <title>Manage User</title>
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/manageuser.css') }}" rel="stylesheet">
@endsection

@section('content')
<!-- {{print_r($data_array)}} -->
<section class="content content-custom">
      <div class="manage-user-page">
        <div class="lead-person-details">
          <div class="lead-person-details-left">
            <h3><span>{{$data_array['display_name']}}</span> <sub class="person-post">{{$data_array['user_type']}}</sub></h3>
            <p><a href="#"><i class="fa fa-globe"></i> {{$data_array['email']}}</a></p>
            @if($data_array['phone']!='')<p><i class="fa fa-phone"></i> {{$data_array['phone']}}</p>@endif
          </div>
          <div class="lead-person-details-right">
            <a href="{{ route('edit_profile',['id' => Crypt::encrypt($data_array['id']) ]) }}" class="edit-btn">Edit</a>
            <!-- <a href="#" data-toggle="modal" data-target=".activity-details" class="activity-btn">Full Activity</a> -->
          </div>
        </div>
        <div class="view-by">
          <h2>Activity Of The Last 24 Hours</h2>
          <!-- {{print_r($postArr)}} -->
          <form action="{{route('search-user-activity')}}" method="POST" class="">
            {{ csrf_field() }}
            <input type="hidden" value="{{Crypt::encrypt($data_array['id'])}}" name="id">
            <div class="field-first-grp">
              <p>Search By</p>
              <div class="input-field">
                <input type="text" name="leadno" value="" placeholder="Lead No." value="{{$postArr['leadno']}}" class="form-control" onKeyUp="$(this).val($(this).val().replace(/[^\d]/ig, ''))">
              </div>
              <div class="input-field">
                <input type="text" name="customername" placeholder="Customer Name" value="{{$postArr['customername']}}" class="form-control">
              </div>
            </div>
            <div class="field-second-grp">
              <div class="input-field">
                <input type="text" name="valuation" placeholder="Valuation" value="{{$postArr['valuation']}}" class="form-control" onKeyUp="$(this).val($(this).val().replace(/[^\d]/ig, ''))">
              </div>
              
            <div class="viewgrp-dropdownblk">
            <label>Status</label>
            <div class="viewgrp-dropdown">
              <div class="magicsearch-wrapper">
                <select class="form-control" name="strength">
                  <option value="0">Select</option>
                  @if(count($strengths)>0)
                  @foreach($strengths as $strength)
                  <option value="{{$strength['id']}}" @if($postArr['strength']==$strength['id']) selected="" @endif>{{$strength['loan_type']}}</option>
                  @endforeach
                  @endif
                </select>
              </div>
              <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
            </div>
            
            </div>
              <div class="viewgrp-dropdownblk activity-typedrop">
                <label>Activity Type</label>
                <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="status">
                    <option value="1" @if($postArr['status']==1) selected="" @endif>Automatic</option>
                    <option value="2" @if($postArr['status']==2) selected="" @endif>Manual</option>
                  </select>
                </div>
              </div>
              </div>
              <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
          </form>
          
        </div>
        @if(!empty($all_activity))
              @php
              $i=1
              @endphp
        @foreach($all_activity as $key => $value)
        <div class="modal fade activity-details" id="activitydetails{{$i}}" role="dialog">
          @if($all_activity[$key]['activity_type']==2)
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                  <i class="close-icon"></i>
                </button>
                <h4 class="modal-title">View Activity Details</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p>
                      <span>Lead No.</span>
                      L00{{$all_activity[$key]['lead_id']}}
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Customers Name</span>
                      {{$lead_details[$key]['company_name']}}
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Status</span>
                      @if($lead_details[$key]['lead_strength_id']==0) New @else {{$lead_details[$key]['loan_type']}} @endif
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Activity Type</span>
                      Manual
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Activity Mode Type</span>
                      {{$all_activity[$key]['activity_mode']}}
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Date Time</span>
                      {{date('d.m.Y h:i A',strtotime($all_activity[$key]['activity_time']))}}
                    </p>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-sm-12">
                    <p>
                      <span>Activity Notes</span>
                      {{$all_activity[$key]['activity_note']}}
                    </p>
                  </div>
                </div>
              </div>
              
            </div>

          </div>
          @else if($all_activity[$key]['activity_type']==1)
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                  <i class="close-icon"></i>
                </button>
                <h4 class="modal-title">View Activity Details</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-4">
                    <p>
                      <span>Lead No.</span>
                      L00{{$all_activity[$key]['lead_id']}}
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Customers Name</span>
                      {{$lead_details[$key]['company_name']}}
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Status</span>
                      @if($lead_details[$key]['lead_strength_id']==0) New @else {{$lead_details[$key]['loan_type']}} @endif
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Activity Type</span>
                      Automatic
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Activity Mode Type</span>
                      -----
                    </p>
                  </div>
                  <div class="col-sm-4">
                    <p>
                      <span>Date Time</span>
                      {{date('d.m.Y h:i A',strtotime($all_activity[$key]['activity_time']))}}
                    </p>
                  </div>
                  
                  
                </div>
              </div>
              
            </div>

          </div>
          @endif
        </div>
              @php
              $i++
              @endphp
        @endforeach
        @endif
        <div class="export-assign">
          <div class="viewgrp-dropdownblk">
            <label>Export As</label>
            <div class="viewgrp-dropdown dropdown">
              <span class="dropdown-toggle" data-toggle="dropdown" id="downloadcsv"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAASCAIAAAAPCcNlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAANxJREFUeNpi/Pvx499PnxhQAauMzL9Pn5j4+OAijPctLP5hqJOcM+dlUZH06tVADRARJkxFEAAUfxoa+vvJE6g6BtwAWSk+dXClQJIFlwqgE+FsoIcY72ppMRACsjt3otsr0tAA9yMywOI+FllZwurYtbT+PH5MQB3QvX+ePOENCyOgji809HVDw7+PH4GuFEhO5nZ3RzgGOU45LS0hYfbr6lWg2cxI8csCN4lNW/vT6tVfd+7EGjRMcD8CnY9LEcK8d319+IOaCTmRYVcBdigj1nSKDICKgEoBAgwANcZZQx3y1vQAAAAASUVORK5CYII=" width="13" height="18"></span>
              
            </div>
          </div>
          <a href="#" class="assign-btn">Assign</a>
        </div>
        <div class="table-part">
          <table class="table tablesorter">
            <thead>
              <tr>
                <th data-toggle="true">Lead No</th>
                <th data-hide="phone,tablet">Customers Name</th>
                <th data-hide="phone,tablet">Valuation</th>
                <th data-hide="phone,tablet">Status</th>
                <th data-hide="phone">Activity Type</th>
                <th data-hide="phone" class="{sorter: 'shortDate'}">Date Time</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody id="load-data">

              @if(!empty($all_activity))
              @php
              $i=1
              @endphp
              @foreach($all_activity as $key => $value)

              @if($lead_details[$key]['valuation']!='') 
              @php
              $num = str_replace(',', '', number_format($lead_details[$key]['valuation'],2))
              @endphp
              @endif 

              @if($lead_details[$key]['valuation']=='') 
              @php
              $num = '0.00' 
              @endphp
              @endif
              <tr>
                <td>L00{{$all_activity[$key]['lead_id']}}</td>
                <td>{{$lead_details[$key]['company_name']}}</td>
                <td>{{$num}}</td>
                <td>@if($lead_details[$key]['lead_strength_id']==0) New @else {{$lead_details[$key]['loan_type']}} @endif</td>
                <td>@if($all_activity[$key]['activity_type']==2) Manual @else Automatic @endif</td>
                <td>{{date('d/m/Y h:i A',strtotime($all_activity[$key]['activity_time']))}}</td>
                <td class="text-center viewgrp-dropdown dropdown">
                  <a href="#" data-toggle="modal" data-target="#activitydetails{{$i}}" class="table-link"><i class="fa fa-eye" aria-hidden="true"></i>View</a>
                </td>
              </tr>
              @php
              $i++
              @endphp
              @endforeach
              @else
              <tr class="ndf">

                <td colspan="7">No record(s) found</td>
              </tr>
              @endif
              
            </tbody>
          </table>
          <!-- <div class="load-more">
            <a href="#">Load More</a>
          </div> -->
        </div>
      </div>

</section>
@endsection

@section('script-section')
  <script src="{{ asset('public/js/manageuser.js') }}"></script>
  <script src="{{ asset('public/js/tableSort.js') }}"></script>
   <script type="text/javascript">
  
  
  $(function() {
      var ts = $.tablesorter,
      dateReplace = /(\S)([AP]M)$/i,
      // match 24 hour time & 12 hours time + am/pm - see http://regexr.com/3c3tk
      timeTest = /^([1-9]|1[0-2]):([0-5]\d)(\s[AP]M)$|^((?:[01]\d|[2][0-4]):[0-5]\d)$/i,
      timeMatch = /([1-9]|1[0-2]):([0-5]\d)(\s[AP]M)|((?:[01]\d|[2][0-4]):[0-5]\d)/i;

    ts.addParser({
      id: 'time2',
      is: function(str) {
        return timeTest.test(str);
      },
      format: function(str, table) {
        // isolate time... ignore month, day and year
        var temp,
          timePart = (str || '').match(timeMatch),
          orig = new Date(str),
          // no time component? default to 00:00 by leaving it out, but only if
          // str is defined
          time = str && (timePart !== null ? timePart[0] : '00:00 AM'),
          date = time ? new Date('2000/01/01 ' + time.replace(dateReplace, '$1 $2')) : time;
        if (date instanceof Date && isFinite(date)) {
          temp = orig instanceof Date && isFinite(orig) ? orig.getTime() : 0;
          // if original string was a valid date, add it to the decimal so the column
          // sorts in some kind of order; luckily new Date() ignores the decimals
         return temp ? parseFloat(date.getTime() + '.' + orig.getTime()) : date.getTime();
        }
        return str;
      },
      type: 'numeric'
    });
  });
  </script>
  <script type="text/javascript">  

    $(document).ready(function() 
    { 
        $(".table").tablesorter(
      {headers: {6: {sorter: false},5: { 
                sorter:'time2' 
            }},sortList: [[0,1]]}
      ); 
    } 
    )  
    $('.table').trigger('footable_initialize');
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
      
        for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
            for (var j = 0; j < cols.length-1; j++) 
                row.push(cols[j].innerText);
            
        csv.push(row.join(","));    
      }

        // Download CSV
        download_csv(csv.join("\n"), filename);
    }

    document.querySelector("#downloadcsv").addEventListener("click", function () {
        var html = document.querySelector(".table-part table").outerHTML;
      export_table_to_csv(html, "{{$data_array['display_name']}}_activities_of_last24hour.csv");
    });

  </script>
@endsection
