@extends('layouts.layout')
@section('title')
  <title>Business Expense</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/business-expense.css') }}" rel="stylesheet">

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
        <div class="view-by business-expense-report-list">
        <form id="search_user" action="{{route('business_expense_report')}}">
           <input type="hidden" name="search" value="search">  
           
        {{ csrf_field() }}
          <div class="field-first-grp ">
            <div class="viewgrp-dropdownblk business-expense-cc">
              <label>Customer</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                    <select class="form-control" name="sale_person" id="myselect">
                        <option value="">Select Saleperson</option>
                        @if(count($sp_list)>0)
                        @foreach($sp_list as $sl)
                        <option value="{{$sl['id']}}" @if($sl['id']==$search_array['sp_id']) selected @endif >{{$sl['display_name']}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
              </div>
            </div> 
           
            <div class="viewgrp-dropdownblk business-expense-dt">
              <label>From Date</label>
              <div class="datepicker-block business-expense-dt-fst">
                <input class="form-control datepicker event_start_date"  name="fromdate" placeholder="From" value="{{$search_from_date}}">
              </div>
              <div class="datepicker-block datepicker-block-to">
                <input class="form-control datepicker event_end_date"  name="todate" placeholder="To" value="{{$search_to_date}}">
              </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>

          </div>

        </form>        
      </div>   
        
        <div class="table-part wid-load-more">
        <table class="table business-expense-table-pt" id="loadtable">
          <thead>
            <tr>
              <th data-toggle="true">SALES EXECUTIVE</th>
              <th data-hide="phone,tablet" class="text-center">Sum of cost</th>
<!--              <th data-hide="phone,tablet">Sale Person</th>
              <th data-hide="phone,tablet" class="text-center" data-sorter="false">Status</th>
              <th class="text-center" data-sorter="false">Action</th>-->
            </tr>
          </thead>
          <tbody id="table_body">
              @if(count($expense_array)>0)
              @foreach($expense_array as $ea)
              <tr>
                  <td class="">{{$ea['company_name']}}</td>
                  <td class="text-center">{{$ea['total_expense']}}</td>
              </tr>
                  @endforeach
                  @else
              <tr><td colspan="2" align="center"> No records found </td></tr>
              @endif   
          </tbody>
          <tfoot>
            <tr>
              <td><span class="grand-total-pr">Grand Total</span></td>
              <td class="text-center">{{$grand_total}}</td>
            </tr>
          </tfoot>
        </table>

         
      </div>
        
        
    </section>
    
    <!-- /.content -->

@endsection


@section('script-section')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{ asset('public/js/tableSort.js') }}"></script>
<script src="{{ asset('public/js/business-expense.js') }}"></script>
<script type="text/javascript">
 
 
// 
//$( document ).ready(function() {
//    $( ".datepicker" ).datepicker({
//        changeMonth: true,
//        changeYear: true,
//        dateFormat: 'dd/mm/yy',
//    });
//
//});
 

$(function () {
                $(".event_start_date").datepicker({
                    numberOfMonths: 2,
                    dateFormat: 'yy/mm/dd',
                    onSelect: function (selected) {
                        var dt = new Date(selected);
                        //dt.setDate(dt.getDate() + 1);
                        //dt.setDate(dt.getDate());

                        $(".event_end_date").datepicker("option", "minDate", dt);
                    }
                });
                $(".event_end_date").datepicker({
                    numberOfMonths: 2,
                    dateFormat: 'yy/mm/dd',
                    onSelect: function (selected) {
                        var dt = new Date(selected);
                        //dt.setDate(dt.getDate() - 1);
                        //dt.setDate(dt.getDate());

                        $(".event_start_date").datepicker("option", "maxDate", dt);
                    }
                });
            });
</script>
  
@endsection