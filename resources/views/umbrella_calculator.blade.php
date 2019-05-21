@extends('layouts.layout')
@section('title')
<title>On Board Email</title>
@endsection
@section('css')
<link href="{{ asset('public/css/addproduct.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/umbrella_calculator.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">

    <div class="addcustomer-form addproduct-form mandetails">

        @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('success_message') }}
        </div> 
        @endif

        <div class="calculator_sec_top">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Use this tool to provide an instant (weekly umbrella) take-home pay illustration.</h4>
                    <p>Please complete all fields marked with an asterix. Additional feilds not marked with an asterix that remain blank will use assumption based calculations (which you can see below).</p>
                </div>
            </div>
        </div>

        <form role="form" id="umbrella_calculator" method="POST" action="{{route('umbrella_calculator')}}" class="row">
             {{ csrf_field() }}
             
             <div class="calculator_sec">
<!--                 <div class="col-sm-6">
                     <div class="form-group">
                        <label for="">Your Name</label>
                        <div class="viewgrp-dropdown">
                          <div class="magicsearch-wrapper">
                              <select class="form-control" name="">
                                  <option  value="">select</option>
                              </select> 
                           </div>
                        </div>
                     </div>
                 </div>-->
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label for="">Your Name</label>
                         <input type="text" name="{{$formfield['your_name']}}" id=""  value="" class="form-control">
                     </div>
                </div>
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label for="">Individuals Name</label>
                         <input type="text" name="{{$formfield['individuals_name']}}" id=""  value="" class="form-control">
                     </div>
                </div>
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label for="">Individuals Email</label>
                         <input type="email" name="{{$formfield['individuals_email']}}" id=""  value="" class="form-control">
                         <small class="green">Required in order to send a confirmation to the individual</small>
                     </div>
                </div>
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label for="">Do they work on an hourly rate, or day rate?</label>
                         <div class="viewgrp-dropdown">
                          <div class="magicsearch-wrapper">
                              <select class="form-control" name="{{$formfield['rate_type']}}">
                                  <option  value="">Please select..</option>
                                  <option value="H">Hour</option>
                                  <option value="D">Day</option>
                              </select> 
                           </div>
                        </div>
                     </div>
                 </div>
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label for="">What will they earn per hour / day?</label>
                         <input type="text" name="{{$formfield['rate_of_pay']}}" id=""  value="" class="form-control">
                     </div>
                </div>
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label for="">How many hours/days, per week will they work?</label>
                         <input type="text" name="{{$formfield['total_hour_day']}}" id="total_hour_day" value="" class="form-control">
                         <small class="red">PLEASE NOTE. You should note enter more than 7 days (or hourly equivalent) per week.</small>
                     </div>
                </div>
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label for="">Would you like to include pensions 'auto-enrolment' deductions?</label>
                         <div class="viewgrp-dropdown">
                          <div class="magicsearch-wrapper">
                              <select class="form-control" name="{{$formfield['include_pension']}}">
                                  <option  value="1" selected>Yes</option>
                                  <option value="0">No</option>
                              </select> 
                           </div>
                        </div>
                        <small class="red">DEFAULT. Default is to state Yes</small>
                     </div>
                </div>
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label for="">Input Margin</label>
                         <ul class="form_margin">
                             <li><span>£</span></li>
                             <li><input type="text" name="{{$formfield['input_margin']}}" id=""  value="22.50" class="form-control"></li>
                             <li><span>PW</span></li>
                         </ul> 
                         <small class="green">Change if required</small> 
                     </div>
                 </div>
                 <div class="col-sm-12">
                    <div class="form-group">
                     <button type="submit" class="btn btn-primary add-btn">Calculate</button></div>
                 </div>
             </div>
         
        </form>
    </div>      

</section>

@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="{{ asset('public/js/addproduct.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {  
      $("#umbrella_calculator").validate({
         rules: {
            {{ $formfield['your_name'] }}: {
                required: true,
            },

            {{ $formfield['individuals_name'] }}: {
                required: true,
            },
           
            {{ $formfield['individuals_email'] }}: {
                required: true,
//                number: true
            },
            {{ $formfield['rate_type'] }}: {
                required: true,
            },
           {{ $formfield['rate_of_pay'] }}: {
                required: true,
                number: true
            },
           {{ $formfield['total_hour_day'] }}: {
                required: true,
                number: true 
            },
            {{ $formfield['input_margin'] }}: {
                required: true,
                number: true 
            },
            
        },
        messages: {
           {{ $formfield['your_name'] }}: {
                required: "Please enter name",
//                minlength: "Your username must consist of at least 3 characters"
            },
            {{ $formfield['individuals_name'] }}: {
                required: "Please enter individuals name",
//                minlength: "Your username must consist of at least 3 characters"
            },
            
            {{ $formfield['individuals_email'] }}: {
                required: "Please enter email",
//                number: "Please input only numeric value"
            },
            {{ $formfield['rate_type'] }}: {
                required: "Please select rate type",
                
            },
            {{ $formfield['rate_of_pay'] }}: {
                required: "Please enter earn per hour/day",
                number: "Please input only numeric value"
            },
            {{ $formfield['total_hour_day'] }}: {
                required: "Please enter vlue",
                number: "Please input only numeric value"
            },
            {{ $formfield['input_margin'] }}: {
                required: "Please enter value",
                number: "Please input only numeric value"
            },
            
        }
      });
  });
</script>
@endsection