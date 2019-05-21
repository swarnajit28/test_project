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
	<div class="home-pay-estimation-page">
      <h3>Hello {{$individuals_name}},</h3>
      <p>Further to our conversation moments ago, I am pleased to confirm that your take home pay would likely be £{{$total}} based on the following criteria:</p>
      <div class="home-pay-estimation-part">
         @foreach($umbrella_array as $key=>$value)
      	<p><sub>{{$key}}</sub> <span>{{$value}}</span></p>
<!--      	<p><sub>Time Worked</sub> <span>6 Days, Per Week</span></p>
      	<p><sub>Rate of Pay</sub> <span>€ 60</span></p>
      	<p><sub>Gross Pay </sub><span>€ 360</span></p>
      	<p><sub>Less Employer National Insurance</sub> <span>-€ 21.28</span></p>-->
        @endforeach
      </div>
      <div class="home-pay-estimation-blk">
      	<p>Your estimated take home pay <span>£{{$total}}</span></p>
      </div>
      <h3>Next Step…</h3>
      <p>To benefit from the services provided by Simplify, please continue to the registration process. <a href="https://simplifybusiness.co.uk/sign-up-today/umbrella-registration/"  target="_blank">here</a></p>
      <h3>Why Simplify is the right choice…</h3>
      <p>As a Professional Passport accredited provider of payroll services, Simplify prides itself as a customer focused organisation having successfully served the contractor market for over 10 years. As a market leader operating throughout the UK, we provide a highly competitive proposition, coupled with unmatched regulatory adherence.</p>
      <h3>How to contact me…</h3>
      <p>You can contact me on 01332 59 59 59, or via email helen.greer@simplifybusiness.co.uk.   I am here to help you with any queries you may have.    If I am on the phone, or otherwise unavailable serving other customers, then our service excellence team are on standby to assist – you can also reach them on 01332 59 59 59.</p>
      <h3>Simplify Business Ltd | Registered Address | St. Peters House, Mansfield Road, Derby, DE1 3TP</h3>
      <p>The information contained in or attached to this email is intended only for the use of the individual or entity to which it is addressed. If you are not the intended recipient, or a person responsible for delivering it to the intended recipient, you are not authorised to and must not disclose, copy, distribute, or retain this message or any part of it. It may contain information that is confidential and/or covered by legal professional or other privilege (or other rules or laws with similar effect in jurisdictions outside England and Wales). The views expressed in this email are not necessarily the views of Simplify, and the company, its directors, officers or employees make no representation or accept any liability for its accuracy or completeness unless expressly stated to the contrary.

Simplify may monitor the content of emails within its network to ensure compliance with its policies and procedures, and for the purposes of staff training.

Email transmission cannot be guaranteed to be secure or error-free as information could be intercepted, corrupted, lost, destroyed, arrive late or incomplete, or contain viruses. Simplify therefore does not accept liability for any errors or omissions in the contents of the message, which arise as a result of email transmission.</p>
        </div>
</section>

@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>


<script src="{{ asset('public/js/addproduct.js') }}"></script>


@endsection