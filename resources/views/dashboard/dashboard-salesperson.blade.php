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
        <input type="hidden" id="sales_person" value="{{$sperson_id =Auth::user()->id}}">
        <div class="viewgrp-dropdownblk margin-leftnone">
          <label>Date</label>
          <div class="viewgrp-dropdown dropdown">
            
            <div class="magicsearch-wrapper">
              <select class="form-control" id="search_time" onchange="ajaxCall()">
                <option value="0">Select Date</option>
                <option value="1">Last 24 Hours</option> 
                <option value="2">Last Week</option>
                <option value="3">Last Month</option>
                <option value="4">Last Year</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <!-- Small boxes (Stat box) -->
      <div class="row smallbox-wrapper">
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="{{route('list_product')}}">
              {{$dashBordData['new_product_added']}}
              <span>Product Variations Available</span>
            </a>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="#">
              <div id="tat">{{$dashBordData['Turn_arround_time']}}</div>
              <span>Turn Around Time  (days)</span>
            </a>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <a href="">
              {{$dashBordData['active_sales_person']}}
              <span>Active Sales Executives</span>
            </a>
          </div>
        </div>
      </div>
      
      <div class="row dashboard-piechart">

        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="dashboard-small-box">
            <div class="dashboard-small-box-inner">
                <a href="#" onclick="leadListDetails('open')">
                <div id="open">{{ $dashBordData['open_lead'] }}</div>
                <span>Open leads</span>
              </a>
              <a href="#" onclick="leadListDetails('close')">
                <div id="close">{{ $dashBordData['no_close_lead'] }}</div>
                <span>Converted Leads / Clients</span>
              </a>
              <a href="#" onclick="leadListDetails('dead')">
                <div id="dead">{{ $dashBordData['dead_lead'] }}</div>
                <span>Dead Leads</span>
              </a>
                 <a href="#" onclick="leadListDetails('new')">
                <div id="new">{{ $dashBordData['new_lead'] }}</div>
                <span>New Leads</span>
              </a>
            </div>
          </div>          
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">          
          <div class="dashboard-big-box">
          <!--  <div class="chat-dash">
              <img src="{{ asset('public/images/chart-img.png') }}" alt="">
            </div> -->
            
          <div id="container" style="min-width: 310px; height: 350px; max-width: 600px; margin: 0 auto"></div>
        <div class="readkey">
              <h5><a href="#" id="faq">Expand Lead Definition (key)</a></h5>
              <div id="hidden">
              <ul>
                  @foreach($dashBoardPieChart as $value)
                <li>
                  <div class="colordiv" style="background: {{$value['color_code']}}">{{$value['lead_strength']}}</div>
                  <div class="colorcont">{{$value['key_details']}}</div>
                </li>
                @endforeach
              </ul>
              </div>     
            </div>
          </div>
      </div>
          
       <div class="col-lg-2 col-md-2 col-sm-2">
        <h2 class="project-5k">Project {{$fiveKdata['project_type']}}K</h2>
        <div class="tg-thermometer" style="height: 350px;">
          <div class="draw-a"></div>
          <div class="draw-b"></div>
          <div class="meter">
            <div class="statistics">
              <div class="percent percent-a">{{$fiveKdata['thermometer_data'][9]}}</div>
              <div class="percent percent-b">{{$fiveKdata['thermometer_data'][8]}}</div>
              <div class="percent percent-c">{{$fiveKdata['thermometer_data'][7]}}</div>
              <div class="percent percent-d">{{$fiveKdata['thermometer_data'][6]}}</div>
              <div class="percent percent-e">{{$fiveKdata['thermometer_data'][5]}}</div>
              <div class="percent percent-f">{{$fiveKdata['thermometer_data'][4]}}</div>
              <div class="percent percent-g">{{$fiveKdata['thermometer_data'][3]}}</div>
              <div class="percent percent-h">{{$fiveKdata['thermometer_data'][2]}}</div>
              <div class="percent percent-i">{{$fiveKdata['thermometer_data'][1]}}</div>
              <div class="percent percent-j">{{$fiveKdata['thermometer_data'][0]}}</div>
              <div class="percent percent-k">100</div>

            </div>
            <div class="mercury" style="height: {{$fiveKdata['percentage']}}%;">
              <div class="percent-current">{{$fiveKdata['percentage']}}%</div>
              <div class="mask">
                <div class="bg-color"></div>
              </div>
            </div>
          </div>
        </div>
      </div>    
      <!-- /.row -->
      <!-- Main row -->
      
      <!-- /.row (main row) -->
      <form action="{{route('list_lead_status')}}" method="POST" class="" id="listlead">
          {{ csrf_field() }}
       <input type="hidden" value="0" id="leadType" name="leadType">   
       <input type="hidden" value="0" id="salePersonId" name="salePersonId"> 
       <input type="hidden" value="0" id="dtime" name="dtime">   
      </form>
      
    </section>
@endsection

@section('script-section')
   <script src="{{ asset('public/js/dashboard.js') }}"></script>
   <script src="{{ asset('public/js/jquery-3.1.1.min.js') }}"></script>
   <script src="{{ asset('public/js/highcharts.js') }}"></script>
   <script src="{{ asset('public/js/exporting.js') }}"></script>
   <script src="{{ asset('public/js/export-data.js') }}"></script>
  
  
<!--  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>-->
<script type="text/javascript"> 
  $(function() {
     $("#hidden").hide();
         $('a#faq').click(function() {
            $('div#hidden').toggle('slow');
            return false;
         });
      });   
    
window.onload = function() {    
// Build the chart
Highcharts.setOptions({
    colors:[
    <?php foreach($dashBoardPieChart as $key => $value){ ?>
        "{{$value['color_code']}}",
        <?php } ?>
        ]
});
Highcharts.chart('container', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Active Leads by Status'
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  },
  series: [{
    name: 'Percentage',
    colorByPoint: true,
    data: [
      <?php foreach($dashBoardPieChart as $key => $value){ ?>
      { name: "{{$value['lead_strength']}}", y:{{ $value['percentage']}}},
        <?php } ?>
    ]
  }]
});
} 



</script>
<script type="text/javascript">
    
  $(document).ready(function(){
     //ajaxCall();
  }); 
    
function ajaxCall(){ 
    var person = $('#sales_person').val();
    var stime = $('#search_time').val();
        $.ajax({
          url: '<?php echo route('loadAjaxDashboard'); ?>',
           method : "POST",
           data : {sales_person:person,dtime:stime,_token:"{{csrf_token()}}"},
           success : function (dashborddata)
           {
             //console.log(dashborddata);
             $('#open').html(dashborddata['open_lead']);
             $('#close').html(dashborddata['no_close_lead']);
             $('#dead').html(dashborddata['dead_lead']);
             $('#new').html(dashborddata['new_lead']);
             $('#tat').html(dashborddata['Turn_arround_time']);
             
             var jsondata=new Array();
             var colordata=new Array();
             $.each(dashborddata.dashBoardPieChart, function(i, item){
                 var strength = item.lead_strength;
                 var percentage = item.percentage; 
                 var color_code = item.color_code;
                jsondata.push([strength,percentage]);
                colordata.push(color_code);
             });
              Highcharts.setOptions({
            colors:colordata,
                });
           //console.log(jsondata);
           Highcharts.chart('container', {
                chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
                },
                title: {
                  text: 'Active Leads by Status'
                },
                tooltip: {
                  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                  pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                      enabled: false
                    },
                    showInLegend: true
                  }
                },
                series: [{
                  name: 'Percentage',
                  colorByPoint: true,
                  data: jsondata
                }]
              });
 
           }
        })

  };

function leadListDetails(status)
{
    var leadStatus= status;
    var person = $('#sales_person').val();
    var stime = $('#search_time').val();
    $('#leadType').val(leadStatus);
    $('#salePersonId').val(person);
    $('#dtime').val(stime);
    $( "#listlead" ).submit();
}

  

</script> 
@endsection
