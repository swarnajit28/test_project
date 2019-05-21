@extends('layouts.layout')
@section('title')
  <title>Dashboard</title>

@endsection
@section('css')
<link href="{{ asset('public/css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
      <div class="view-by">
        <p>View By</p>
        <div class="viewgrp-dropdownblk">
          <label>Sales Person</label>
          <div class="viewgrp-dropdown dropdown">
            <!-- All
            <a class="dropdown-toggle dropdowndot-icon" data-toggle="dropdown"></a>
            <ul class="dropdown-menu">
              <li><a href="#">All</a></li>
              <li><a href="#">a</a></li>
              <li><a href="#">b</a></li>
            </ul> -->
            <input class="magicsearch" id="sales-person" placeholder="All">
          </div>

        </div>
        <div class="viewgrp-dropdownblk">
          <label>Date</label>
          <div class="viewgrp-dropdown dropdown">
            <!-- Last Week
            <a class="dropdown-toggle dropdowndot-icon" data-toggle="dropdown"></a>
            <ul class="dropdown-menu">
              <li><a href="#">Last month</a></li>
              <li><a href="#">One Year</a></li>
              <li><a href="#">Two Year</a></li>
            </ul> -->
            <div class="magicsearch-wrapper">
              <select class="form-control">
                <option>Last Week</option>
                <option>Last Month</option>
                <option>Last Year</option>
              </select>
            </div>
            <!-- <input class="magicsearch" id="date-week" placeholder="Last Week"> -->
          </div>
        </div>
      </div>
      <!-- Small boxes (Stat box) -->
      <div class="row smallbox-wrapper">
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="">
              24
              <span>New Products Added</span>
            </a>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="">
              14
              <span>Turn Around Time  (days)</span>
            </a>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="">
              24
              <span>New Products Added</span>
            </a>
          </div>
        </div>
      </div>
      <div class="row dashboard-piechart">

        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <div class="dashboard-small-box-inner">
              <a href="">
                37
                <span>Open leads</span>
              </a>
              <a href="">
                04
                <span>Closed Leads</span>
              </a>
              <a href="">
                00
                <span>Dead Leads</span>
              </a>
            </div>
          </div>          
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8">          
          <div class="dashboard-big-box">
            <div class="chat-dash">
              <img src="{{ asset('public/images/chart-img.png') }}" alt="">
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
      <!-- Main row -->
      
      <!-- /.row (main row) -->

    </section>
@endsection

@section('script-section')
  <script src="{{ asset('public/js/dashboard.js') }}"></script>
@endsection
