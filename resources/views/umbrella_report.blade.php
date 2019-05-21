@extends('layouts.layout')
@section('title')
  <title>Umbrella Report</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/leaddetails.css') }}" rel="stylesheet">
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
        <form id="search_user" action="">
           <input type="hidden" name="search" value="search">  
           
        {{ csrf_field() }}
          <div class="field-first-grp ">
 
            <div class="viewgrp-dropdownblk business-expense-dt">
              <label>From Date</label>
              <div class="datepicker-block business-expense-dt-fst">
                <input class="form-control datepicker event_start_date"  name="fromdate" placeholder="From" value="{{$postArr['fromdate']}}">
              </div>
              <div class="datepicker-block datepicker-block-to">
                <input class="form-control datepicker event_end_date"  name="todate" placeholder="To" value="{{$postArr['todate']}}">
              </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>

          </div>

        </form>        
      </div>     
        
        <div class="table-part wid-load-more umbrella_report_table">
          <table class="table business-expense-table-pt" id="loadtable">
            <thead>
              <tr>
                <th data-toggle="true">Id</th>
                <th>Name</th>
                <th data-hide="phone,tablet">Email</th>
                <th data-hide="phone,tablet">Rate type</th>
                <th data-hide="phone,tablet">Rate of pay</th>
                <th data-hide="phone,tablet">Total hour</th>
              </tr>
            </thead>
            <tbody id="payment_option"">
                @if(count($all_data) >0)
                 @foreach($all_data as $data)
                 <tr id="{{$data['id']}}">
                     <td data-hide="phone,tablet">{{$data['id']}}</td>
                     <td data-hide="phone,tablet">{{$data['your_name']}}</td>
                     <td data-hide="phone,tablet">{{$data['individuals_email']}}</td>
                     <td data-hide="phone,tablet"> @if($data['rate_type'] == 'H') Hour @else Day @endif</td>
                     <td data-hide="phone,tablet">{{$data['rate_of_pay']}}</td>
                     <td data-hide="phone,tablet">{{$data['total_hour_day']}}</td>
                 </tr>
                 @endforeach
                @else
                <tr><td colspan="6">No records found</td></tr>
                @endif
            </tbody>
          </table>
          <div id="remove-row">
            <div class="load-more">
                <button id="btn-more"> @if(count($all_data) > 0) Load More @else No More Data @endif</button>
            </div>
          </div>
        </div>
        
        
    </section>
    
    <!-- /.content -->

@endsection


@section('script-section')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{ asset('public/js/tableSort.js') }}"></script>
<script src="{{ asset('public/js/leaddetails.js') }}"></script>

<script type="text/javascript">

  $(document).ready(function(){
  
   $(document).on('click','#btn-more',function(){
      var id = $('#loadtable tr:last').attr('id');
      //alert(id);
      if (typeof(id) != "undefined")
      {
       $("#btn-more").html("Loading....");
       $.ajax({
           url : '{{route("loadUmbrellaReport")}}',
           method : "POST",
           //data : {id:id, _token:"{{csrf_token()}}"},
           data : {'id':id, '_token': '<?php echo csrf_token(); ?>'},
           dataType : "text",
           success : function (data)
           {
              if(data != '') 
                {
                    $('#payment_option').append(data);
                    $('#btn-more').html("Load More");
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
  
@endsection