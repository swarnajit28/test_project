@extends('layouts.layout')
@section('title')
  <title>Business Expense</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/managecustomer.css') }}" rel="stylesheet">

@endsection

@section('content')
    <!-- Main content -->
    <section class="content content-custom">
           @if (session('success_message'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ Session::get('success_message') }}
    </div> 
    @endif
        <div class="view-by">
        <form id="search_user" action="{{route('list_business_expense')}}">
            <input type="hidden" name="search" value="yes">
        {{ csrf_field() }}
          <div class="field-first-grp ">
            <p>Search By</p>            

          </div>
          <div class="field-second-grp">
              @if ((Auth::user()->user_type=='MA'))
            <div class="viewgrp-dropdownblk">
              <label>Sale Persons</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                    <select class="form-control" name="sale_person" id="myselect">
                        <option value="">Select Saleperson</option>
                        @if(count($sp_list)>0)
                        @foreach($sp_list as $sl)
                        <option value="{{$sl['id']}}" @if($sl['id']==$search_sale_person) selected @endif>{{$sl['display_name']}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
              </div>
            </div>
              @else
              <input type="hidden" name="sale_person" value="{{(Auth::user()->id)}}">
              @endif
              
              <div class="viewgrp-dropdownblk">
              <label>Year</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                    <select class="form-control" name="year" >
                        <option value="">Select Year</option>
                        @if(count($year)>0)
                        @foreach($year as $y)
                        <option value="{{$y}}" @if($y==$search_year) selected @endif >{{$y}}</option>
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
        
        <div class="table-part wid-load-more">
        <table class="table" id="loadtable">
          <thead>
            <tr>
              <th data-toggle="true">Year</th>
              <th data-hide="phone,tablet">Month</th>
              <th data-hide="phone,tablet">Sale Person</th>
              <th data-hide="phone,tablet" class="text-center" data-sorter="false">Is Submitted</th>
              <th data-hide="phone,tablet" class="text-center" data-sorter="false">Is Approved</th>
              <th class="text-center" data-sorter="false">Action</th>
            </tr>
          </thead>
          <tbody id="table_body">
              @if($search=='yes')
              @if(count($result_array)>0)
              @foreach($result_array as $m)
              <tr>
                  <td class="date">{{$m['year']}}</td>
                  <td>{{$m['month']}}</td>
                  @if(Auth::user()->user_type=='SP')
                  <td>{{Auth::user()->display_name}}</td>
                  @else
                   <td class="sale_person">{{$m['saleperson']}}</td>
                  @endif 
                  <td class="text-center">@if((isset($m['is_approved']))) Yes @else No @endif</td>
                  <td class="text-center">@if((isset($m['is_approved']))&&($m['is_approved']==1)) Yes @else No @endif</td>
                  
                  <td class="text-center viewgrp-dropdown dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                      <ul class="dropdown-menu">
                          <li><a href="#" onclick="showDiv(this)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Manage</a></li>

                      </ul>
                  </td>
              </tr>

              @endforeach
              @endif
              @else
              <tr>  <td colspan="6" align="center"> No records found </td></tr>
              @endif
          </tbody> 
        </table>
         
      </div>
        <form id="hidd_form" action="{{route('business_expense')}}">
              {{ csrf_field() }}
            <input type="hidden" name="year" id="year">
            <input type="hidden" name="month" id="month">
            <input type="hidden" name="sale_person" id="sale_person">
        </form>
        
    </section>
    
    <!-- /.content -->

@endsection


@section('script-section')
<script src="{{ asset('public/js/tableSort.js') }}"></script>
<script src="{{ asset('public/js/validate.js') }}"></script>
<script type="text/javascript">
 $(document).ready(function() {
      $("#search_user").validate({
          ignore: '',
          rules: {
              sale_person : "required",      
              year : "required",
              
          },
         
      });
  });
         
    
// function year_change(year)
// {
//     $("#table_body").hide("fast", function(){
//      $('#table_body').show("100");
//            $(".date").html(year); 
//    }); 
// }
// 
// function saleperson_change(event)
// {
//     var saleperson_val= $("#myselect :selected").text();
//    //alert(saleperson_val);
//     $("#table_body").hide("fast", function(){
//     $('#table_body').show("100");
//     $(".sale_person").html(saleperson_val); 
//    }); 
// }
// 
 function showDiv(elem)
 {
     //console.log(elem);
    
     //$(elem).closest('tr').addClass("tdfassss");
     var year= $(elem).closest('tr').children('td:eq(0)').text();
     var month= $(elem).closest('tr').children('td:eq(1)').text();
     var sale_person= $(elem).closest('tr').children('td:eq(2)').text();
     
     $('#sale_person').val(sale_person);
     $('#year').val(year);
     $('#month').val(month);
     $( "#hidd_form" ).submit();
     
 }
  
</script>
  <script src="{{ asset('public/js/managecustomer.js') }}"></script>
@endsection