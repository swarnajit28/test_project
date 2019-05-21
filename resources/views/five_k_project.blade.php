@extends('layouts.layout')
@section('title')
  <title>Product Category</title>
@endsection
@section('css')
<link href="{{ asset('public/css/managecustomer.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
      <div class="manage-product-form lead_activity_mode_page">
      
      <div class="view-by">
       @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
       @endif
       <div class="weektag">
          <form action="{{route('fiveKproject')}}" method="POST" id="target_value">
           {{ csrf_field() }}
            <div class="field-first-grp">
                <p>Weekly Target:</p>
                  <div class="input-field">
                    <input type="text" name="target_value"  id="target_value"  placeholder="Enter Targer Value" class="form-control" value="<?php echo isset($weekly_project_target['target_value'])?$weekly_project_target['target_value']:'0'; ?>">
                  </div>
               <button type="submit" class="btn btn-primary" id="submit">Modify</button>
            </div>
          </form>
          </div>
          <div class="selectyear">
            <form action="{{route('fiveKproject')}}" method="POST" id="target_value">
              {{ csrf_field() }}
              <div class="field-second-grp"> 
                  <div class="viewgrp-dropdownblk">
                      <label>Year</label>
                      <div class="viewgrp-dropdown">
                          <div class="magicsearch-wrapper">
                              <select class="form-control" name="year" id="role">
                                  <option value="0">Select Year</option>
                                  @foreach($select as $value)
                                  <option value="{{$value}}"> {{ $value }}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                  </div>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>  
              </div>     
          </form>
          </div>
        </div>
          
        <div class="table-part wid-load-more">
          
          <table class="table tablesorter" id="loadtable">
            <thead>
              <tr>
                <th data-toggle="true">Week number</th>
                <th data-hide="phone,tablet">From Date</th>
                <th data-hide="phone,tablet">To Date</th>
                <th data-hide="phone,tablet">Cis_paid</th>
                <th data-hide="phone,tablet">Umbrella_paid</th>
                <th data-hide="phone,tablet">Other_paid</th>
                <th class="text-center" data-sorter="false">Action</th>
              </tr>
            </thead>
            <tbody id="product_category">
            @if(count($weekly_list) > 0)    
            @foreach($weekly_list as $list)
            <input type="hidden" name="id" id="hideid">
              
                <tr id="">
                <td>{{$list['week_number']}}</td>
                <td>{{$list['week_start_date']}}</td>
                <td>{{$list['week_end_date']}}</td>
                <td>{{$list['cis_paid']}}</td>
                <td>{{$list['umbrella_paid']}}</td>
                <td>{{$list['other_paid']}}</td>
              <td class="text-center viewgrp-dropdown dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{ route('addfiveKproject',['id' => Crypt::encrypt($list['id']) ]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
                  </ul>
                </td>
              </tr>
              @endforeach
                @else
                  <tr class="ndf">
                    <td colspan="7" ><span class="text-margin centertext">No record(s) found</span></td>
                  </tr>
                @endif

       
            
            </tbody>
          </table>
           @if(isset($page)) 
          <div id="remove-row">
              <div class="load-more">
                  <button id="btn-more" data-id="{{(isset($data['id'])) ? $data['id'] : '' }}" > @if(count($category) > 0) Load More @else No More Data @endif</button>
                  <!-- <a href="#">Load More</a> -->
              </div>
          </div>
        @endif
        
        </div>
      </div>

    </section>
@endsection

@section('script-section')

<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="{{ asset('public/js/tableSort.js') }}"></script>

<script type="text/javascript">
$(function(){
    $("#loadtable").tablesorter();
  });
</script>




<script src="{{ asset('public/js/managecustomer.js') }}"></script>
@endsection


