@extends('layouts.layout')
@section('title')
  <title>Lead Report</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/leaddetails.css') }}" rel="stylesheet">

@endsection

@section('content')
<section class="content content-custom">
     
      <div class="manage-user-page lead-list-page salereport-page">
        
        <div class="view-by">
          @if (Auth::user()->user_type != 'SP')
          <form action="{{route('search-lead-report')}}" method="POST" class="" id="listlead">
            {{ csrf_field() }}  
          @endif
          @if (Auth::user()->user_type == 'SP')
          <form action="{{route('search-sp-lead-report')}}" method="POST" class="" id="listlead">
            {{ csrf_field() }}  
          @endif        
          
            <div class="field-first-grp field-first-grplist-p">
              <p>Search By</p>
              <div class="input-field">
                <input type="text" name="customer_name" placeholder="Company Name" class="form-control" value="{{$postArr['customer_name']}}">
              </div>
              <div class="input-field lead-id">
                <input type="text" name="lead_id" placeholder="Lead ID" onKeyUp="$(this).val($(this).val().replace(/[^\d]/ig, ''))" class="form-control" value="{{$postArr['lead_id']}}">
              </div>
<!--              {{Auth::user()}}-->
               @if (Auth::user()->user_type == 'SP')
               <input type="hidden" name="sale_person" value="0">
               @else
              <div class="viewgrp-dropdownblk product-drop">
                <label>Sales Person</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                    <select class="form-control" name="sale_person">
                      <option value="0">Select</option>
                      @if(count($salesperson)>0)
                      @foreach($salesperson as $person)
                      <option value="{{$person['id']}}" @if($postArr['sale_person']==$person['id']) selected="" @endif>{{$person['display_name']}}</option>
                      @endforeach
                      @endif
                    </select>
                  </div>
                  <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
                </div>
              </div>
               @endif
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
            
          </div>
          <div class="field-second-grp field-second-grplist-p">
            
             
            <div class="viewgrp-dropdownblk">
              <label>Lead Type</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="status">
                  <option value="0">Select</option>                     
                  <option value="close" @if($postArr['status']=='close') selected="" @endif>Close</option>                 
                  <option value="dead" @if($postArr['status']=='dead') selected="" @endif>Dead</option>                 
                  <option value="new" @if($postArr['status']=='new') selected="" @endif>New</option>                
                  <option value="open" @if($postArr['status']=='open') selected="" @endif>Open</option> 
                  </select>
                </div>
                <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
              </div>
            </div> 
            <button type="submit" name="srch_btn" value="sbt" class="btn btn-primary" onclick="resethideid()"><i class="fa fa-search" aria-hidden="true"></i></button>
          </div>
          
            
          </form>
          
        </div>
        
       @if(Auth::user()->user_type !='SP')   
          
        <div class="export-assign">
          <div class="viewgrp-dropdownblk">
            <label>Export As</label>
            <div class="viewgrp-dropdown dropdown">
              <span class="dropdown-toggle pdf-icon-custom" data-toggle="dropdown" id="downloadcsv"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span>
              
            </div>
          </div>
        </div>
       @endif
       
        <div class="lead-detail-activity-table">
          <div class="table-part wid-load-more">
            <table class="table tablesorter" id="loadtable">

              <thead id="csvtr">
                <tr style="cursor: pointer;" id="acttr">
                  <th data-toggle="true">Lead ID</th>
                  <th data-hide="phone,tablet">Company Name</th>
                  <th data-hide="phone,tablet">Sales Person</th>
                  <th data-hide="phone,tablet">No. Of Products</th>
                  <th data-hide="phone">Lead Value</th>
                  <th data-hide="phone">Status</th>
                  <th id="lastupdate" class="{sorter: 'shortDate'}">Last Updated</th>

                </tr>
                <!-- <tr id="csvtr" style="display: none;;"></tr> -->
              </thead>
              <tbody id="load-data">
                @if(count($lead_details) > 0) 
                @foreach($lead_details as $leads)
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
                <tr id="{{$leads['id']}}">
                  <td><a class="table-link" href="{{route('lead-details',['id' => Crypt::encrypt($leads['id']) ])}}">L00{{$leads['id']}}</a></td>
                  <td>{{$leads['company_name']}}</td>
                  <td>{{$leads['display_name']}}</td>
                  <td>{{$leads['totprod']}}</td>
                  <td>{{$num}}</td>
                  <!-- <td>{{$leads['loan_type']}}</td> -->                  
                  <td>@if($leads['lead_strength_id']!='0') {{$leads['loan_type']}} @else New @endif</td>
                  <td>@if($leads['updated_at']!=''){{date('d/m/Y',strtotime($leads['updated_at']))}}@endif</td>
                </tr>
                @endforeach
                @else
                  <tr class="ndf">
                    <td colspan="7"><span class="text-margin centertext">No record(s) found</span></td>
                  </tr>
                @endif
                
              </tbody>
            </table>
            
          </div>            
            
            
        </div>
      </div>
      <input type="hidden" id="perPage" value="{{(isset($perPage) ? $perPage : '')}}">
    </section>

@endsection

@section('script-section')
  <script src="{{ asset('public/js/leaddetails.js') }}"></script>  
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js"></script>
  
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
      {headers: {6: { 
                sorter:'dateMS' 
            }},sortList: [[0,1]]}
      );
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
        
            for (var j = 0; j < cols.length; j++) 
                row.push(cols[j].innerText);
        csv.push(row.join(","));    
      }

        // Download CSV
        download_csv(csv.join("\n"), filename);
    }

    document.querySelector("#downloadcsv").addEventListener("click", function () {
     
      $("#acttr").hide();
      var deltr = "deltr";
      $("#csvtr").append('<tr id='+deltr+'><th>Lead ID</th><th>Company Name</th><th>Sales Person</th><th>No. Of Products</th><th>Lead Value</th><th>Status</th><th>Last Updated</th></tr>');
        var html = document.querySelector(".table-part table").outerHTML;
      export_table_to_csv(html, {{date('Ymdhis')}}+"sale_report.csv");
      $("#deltr").remove();
      $("#acttr").show();
    });
  
</script>

@endsection
