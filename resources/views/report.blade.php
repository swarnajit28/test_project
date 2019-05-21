@extends('layouts.layout')
@section('title')
  <title>Dashboard</title>

@endsection
@section('css')
<link href="{{ asset('public/css/dashboard.css') }}" rel="stylesheet">

@endsection

@section('content')
<section class="content content-custom">
     
      <!-- Small boxes (Stat box) -->
      <div class="row smallbox-wrapper">
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="{{route('lead-report')}}">
                 <img src="{{ asset('public/images/lead_report.png') }}" >
              <span>Lead Report</span>
            </a>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="{{route('business_expense_report')}}">
                <img src="{{ asset('public/images/expense_report.png') }}" >   
              <div id="tat"></div>
              <span>Business Report</span>
            </a>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="{{route('umbrella-report')}}">
                <img src="{{ asset('public/images/umbrella-queries-report.svg') }}" >   
              <div id="tat"></div>
              <span>Umbrella Report</span>
            </a>
          </div>
        </div>  
      </div>
       <div class="row smallbox-wrapper">
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="{{route('bug_report_list')}}">
                <img src="{{ asset('public/images/bug_report.svg') }}" >   
              <div id="tat"></div>
              <span>Bug Report</span>
            </a>
          </div>
        </div>
       </div>
      
      <div class="row dashboard-piechart">

       
          
        
          
      <!-- /.row -->
      <!-- Main row -->
      
      <!-- /.row (main row) -->

      
    </section>
@endsection

@section('script-section')
  <script src="{{ asset('public/js/dashboard.js') }}"></script>

 <script src="{{ asset('public/js/jquery-3.1.1.min.js') }}"></script>
   <script src="{{ asset('public/js/highcharts.js') }}"></script>
   <script src="{{ asset('public/js/exporting.js') }}"></script>
   <script src="{{ asset('public/js/export-data.js') }}"></script>


@endsection
