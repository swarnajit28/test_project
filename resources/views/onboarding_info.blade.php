@extends('layouts.layout')
@section('title')
<title>On Board Email</title>
@endsection
@section('css')
<link href="{{ asset('public/css/addproduct.css') }}" rel="stylesheet">
<!--<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">-->
<link href="{{ asset('public/css/leaddetails.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">

    <div class="addcustomer-form addproduct-form mandetails">

        @if ($p_data['success_message'])
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ $p_data['success_message'] }}
        </div> 
        @endif

        <form role="form" id="customerForm" method="POST" action="{{route('submit_onboarding_info')}}">
            {{ csrf_field() }}
            <div class="addcustomer-form">
                <div class="onboardformsec">
                    <input type="hidden" name="{{ $encrypted['id'] }}" id="{{ $encrypted['id'] }}" value="{{$p_data['id']}}" class="form-control"> 
                <div class="form-group col-sm-6">
                    <label for="">Agency Name <em class="text-danger">*</em></label>
                    <input type="text" name="{{ $encrypted['agency_name'] }}" id="{{ $encrypted['agency_name'] }}" value="" class="form-control">
                            @if ($errors->has('agency_name'))
                            <span class="error">{{$errors->first('agency_name')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Company Registration Number <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['reg_no'] }}" id="{{ $encrypted['reg_no'] }}" value=" {{ $p_data['reg_no'] }}" class="form-control" readonly="">
                            @if ($errors->has('reg_no'))
                            <span class="error">{{$errors->first('reg_no')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Agency or End Client <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['agency_client'] }}" id="{{ $encrypted['agency_client'] }}" value="" class="form-control">
                            @if ($errors->has('agency_client'))
                            <span class="error">{{$errors->first('agency_client')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Business Sector</label>
                            <input type="text" name="{{ $encrypted['business_sector'] }}" id="{{ $encrypted['business_sector'] }}" value="" class="form-control">
                        </div>
                    </div>
                    <div class="onboardformsec">
                        <div class="form-group col-sm-12">
                            <h5>Points of contact for:</h5>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">New Business</label>
                            <input type="text" name="{{ $encrypted['new_business'] }}" id="{{ $encrypted['new_business'] }}" value="" class="form-control">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Accounts/Payroll</label>
                            <input type="text" name="{{ $encrypted['payraoll'] }}" id="{{ $encrypted['payraoll'] }}" value="" class="form-control">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Contracts/Compliance <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['compliance'] }}" id="{{ $encrypted['compliance'] }}" value="" class="form-control">
                            @if ($errors->has('compliance'))
                            <span class="error">{{$errors->first('compliance')}}</span>   
                            @endif
                        </div>
                    </div>
                    <div class="onboardformsec">
                        <div class="form-group col-sm-12">
                            <h5>Proposed Volume:</h5>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Initially <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['initially'] }}" id="{{ $encrypted['initially'] }}" value="" class="form-control">
                            @if ($errors->has('initially'))
                            <span class="error">{{$errors->first('initially')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">After 1 Month <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['one_month'] }}" id="{{ $encrypted['one_month'] }}" value="" class="form-control">
                            @if ($errors->has('one_month'))
                            <span class="error">{{$errors->first('one_month')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">After 6 Months <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['six_month'] }}" id="{{ $encrypted['six_month'] }}" value="" class="form-control">
                            @if ($errors->has('six_month'))
                            <span class="error">{{$errors->first('six_month')}}</span>   
                            @endif
                        </div>
                    </div>
                    <div class="onboardformsec">
                        <div class="form-group col-sm-6">
                            <label for="">Proposed Products (CIS/C7/UMB) <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['product'] }}" id="{{ $encrypted['product'] }}" value="" class="form-control">
                            @if ($errors->has('product'))
                            <span class="error">{{$errors->first('product')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Approx. Weekly Invoice (exc. VAT)</label>
                            <input type="text" name="{{ $encrypted['weekly_invoice'] }}" id="{{ $encrypted['weekly_invoice'] }}" value="" class="form-control">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Contractor Margin(s) <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['margin'] }}" id="{{ $encrypted['margin'] }}" value="" class="form-control">
                            @if ($errors->has('margin'))
                            <span class="error">{{$errors->first('margin')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Proposed Credit <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['credit'] }}" id="{{ $encrypted['credit'] }}" value="" class="form-control">
                            @if ($errors->has('credit'))
                            <span class="error">{{$errors->first('credit')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Proposed Rebate <em class="text-danger">*</em></label>
                            <input type="text" name="{{ $encrypted['rebate'] }}" id="{{ $encrypted['rebate'] }}" value="" class="form-control">
                            @if ($errors->has('rebate'))
                            <span class="error">{{$errors->first('rebate')}}</span>   
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Rebate applicability threshold <i>(e.g. after x onboard)</i></label>
                            <input type="text" name="{{ $encrypted['rebate_threshold'] }}" id="{{ $encrypted['rebate_threshold'] }}" value="" class="form-control">
                        </div>
<!--                        <div class="form-group col-sm-6">
                            <label for="">Expected Start Date</label>
                            <input type="text" name="{{ $encrypted['start_date'] }}" id="{{ $encrypted['start_date'] }}" value="" class="form-control">
                        </div>-->
                        <div class="form-group col-sm-6">
                            <label for="">Expected Start Date</label>
                            <div class="datepicker-block">
                                <input class="form-control datepicker" readonly="" value="" name="{{ $encrypted['start_date'] }}" id="{{ $encrypted['start_date'] }}" placeholder="From">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12 align-left onboarding_info_submit">
                            <!-- <button type="submit" class="btn btn-primary reset-btn">Cancel</button> -->
                            <button type="submit" class="btn btn-primary add-btn">Submit</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>      

</section>

@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<!--<script src="{{ asset('public/js/addproduct.js') }}"></script>-->
  <script src="{{ asset('public/js/leaddetails.js') }}"></script> 
<script type="text/javascript">
  $(document).ready(function() {
    $("#customerForm").validate({
        rules: {
          
            {{ $encrypted['agency_name'] }}: {
                required: true,
            },

            {{ $encrypted['reg_no'] }}: {
                required: true,
            },
           
            {{ $encrypted['agency_client'] }}: {
                required: true,
            },
            {{ $encrypted['compliance'] }}: {
                required: true,
            },
           {{ $encrypted['initially'] }}: {
                required: true,
                number: true
            },
           {{ $encrypted['one_month'] }}: {
                required: true,
                number: true 
            },
            {{ $encrypted['six_month'] }}: {
                required: true,
                number: true 
            },
            {{ $encrypted['product'] }}: {
                required: true,
            },
            {{ $encrypted['margin'] }}: {
                required: true,
                number: true 
            },
            {{ $encrypted['credit'] }}: {
                required: true,
                number: true 
            },
            {{ $encrypted['rebate'] }}: {
                required: true,
                number: true 
            },
            
        },
        messages: {
           {{ $encrypted['agency_name'] }}: {
                required: "Please enter agency name",
            },
            {{ $encrypted['reg_no'] }}: {
                required: "Please enter registration number",
//                minlength: "Your username must consist of at least 3 characters"
            },
            
            {{ $encrypted['agency_client'] }}: {
                required: "Please enter end client",
            },
            {{ $encrypted['compliance'] }}: {
                required: "Please enter contracts/compliance",
            },
            {{ $encrypted['initially'] }}: {
                required: "Please enter initial value",
                number: "Please input only numeric value"
            },
            {{ $encrypted['one_month'] }}: {
                required: "Please enter after one month value",
                number: "Please input only numeric value"
            },
            {{ $encrypted['six_month'] }}: {
                required: "Please enter after six month value",
                number: "Please input only numeric value"
            },
            {{ $encrypted['product'] }}: {
                required: "Please enter proposed product",
            },
            {{ $encrypted['margin'] }}: {
                required: "Please enter contractor margin",
                number: "Please input only numeric value"
            },
            {{ $encrypted['credit'] }}: {
                required: "Please enter proposed credit",
                number: "Please input only numeric value"
            },
            {{ $encrypted['rebate'] }}: {
                required: "Please enter proposed rebate",
                number: "Please input only numeric value"
            },
            
        }
    });
});

</script>
@endsection
