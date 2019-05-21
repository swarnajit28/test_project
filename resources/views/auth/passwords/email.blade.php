<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" name="viewport">
  <title>Forgot Password</title>
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800" rel="stylesheet"> 
  <link href="https://fonts.googleapis.com/css?family=Nunito:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link rel="apple-touch-icon" sizes="57x57" href="favicons/apple-touch-icon-57x57.png"><link rel="apple-touch-icon" sizes="60x60" href="favicons/apple-touch-icon-60x60.png"><link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-touch-icon-72x72.png"><link rel="apple-touch-icon" sizes="76x76" href="favicons/apple-touch-icon-76x76.png"><link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-touch-icon-114x114.png"><link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-touch-icon-120x120.png"><link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-touch-icon-144x144.png"><link rel="apple-touch-icon" sizes="152x152" href="favicons/apple-touch-icon-152x152.png"><link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-touch-icon-180x180.png"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"><meta name="apple-mobile-web-app-title" content="webpack4"><link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png"><link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png"><link rel="shortcut icon" href="favicons/favicon.ico"><meta name="mobile-web-app-capable" content="yes"><meta name="theme-color" content="#fff"><meta name="application-name" content="webpack4"><link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" href="favicons/apple-touch-startup-image-320x460.png"><link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" href="favicons/apple-touch-startup-image-640x920.png"><link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" href="favicons/apple-touch-startup-image-640x1096.png"><link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="favicons/apple-touch-startup-image-750x1294.png"><link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 3)" href="favicons/apple-touch-startup-image-1182x2208.png"><link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 3)" href="favicons/apple-touch-startup-image-1242x2148.png"><link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 1)" href="favicons/apple-touch-startup-image-748x1024.png"><link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 1)" href="favicons/apple-touch-startup-image-768x1004.png"><link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)" href="favicons/apple-touch-startup-image-1496x2048.png"><link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" href="favicons/apple-touch-startup-image-1536x2008.png"><link href="css/forgotpassword.css" rel="stylesheet"></head>
  @include('layouts.favicon')
<link href="{{ asset('public/css/forgotpassword.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
<body>
  <div class="outer">
    <div class="middle">
      <div class="inner">
        <div class="login-left">
          <div class="login-leftinner">
            <a href="#"><img src="{{ asset('public/images/login-logo.png') }}" width="207" height="60" alt=""></a>
            <h2>Forgot Password</h2>
            <div class="main-footer hidden-xs">
              <span>© 2018</span> Simplify. All Rights Reserved.
              <a href="https://simplifybusiness.co.uk" target="_blank">simplifybusiness.co.uk</a>
            </div>
          </div>
        </div>
       
        <div class="login-right">
          <div class="forgot-step">
            <ul>
              <li @if (!session('status')) class="step-active" @endif>1</li>
              <li @if (session('status')) class="step-active" @endif>2</li>
              <li>3</li>
            </ul>
          </div>

          <!-- step one start-->
          <!-- <form role="form" method="POST" action="#" class="row forgot-stepone">
              <div class="form-group col-sm-12">
                <label>Email Address</label>
                <input type="email" name="email-address" placeholder="Username" class="form-control">
              </div>
              <div class="form-group col-sm-12 btn-group">
                <input type="hidden" name="save" value="contact">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="#" class="forgot-passwordlink"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Login</a>
              </div>
          </form> -->
          <!-- step one end-->

          <!-- step two start-->
          <!-- <div class="forgot-steptwo">
            <p>
              Thank you for requesting a password reset. Please check your <a href="#">Inbox</a> and follow the instruction outlined by the Simplify IT department. If you continue to experience issues going access to your account please call <b>0330 122 1223</b>
            </p>
            <a href="#" class="forgot-passwordlink"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Login</a>
          </div> -->
          <!-- step two end-->

          <!-- step three start-->
          <form role="form" method="POST" action="{{ route('password.email') }}" class="row forgot-stepone" id="customerForm">
          {{ csrf_field() }}
              @if (session('status'))
                 <p>
                    Thank you for requesting a password reset. Please check your <a href="#">Inbox</a> and follow the instruction outlined by the Simplify IT department. If you continue to experience issues going access to your account please call <b>0330 122 1223</b>
                </p>
              @else
              <div class="form-group col-sm-12">
                <label>Username</label>
                <input type="text" name="email" id="email" placeholder="Username" class="form-control @if ($errors->has('email')) {{ 'error' }}@endif" value="{{ old('email') }}" onKeyUp="checkemailexist(this.value);">
                 @if ($errors->has('email'))
                                    <span id="phperror" class="error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                <span id="email-error1" class="error"></span>
              </div>
              <div class="form-group col-sm-12 btn-group">
               
                <input type="hidden" name="save" value="contact">
                <button type="submit" class="btn chkbtn btn-primary">Submit</button>
              @endif
                <a href="{{route('login')}}" class="forgot-passwordlink"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Login</a>
              </div>
          </form>
          <!-- step three end-->
          <div class="main-footer hidden-sm hidden-md hidden-lg">
            <span>© 2018</span> Simplify. All Rights Reserved.
            <a href="https://simplifybusiness.co.uk" target="_blank">simplifybusiness.co.uk</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{ asset('public/js/forgotpassword.js') }}"></script>
<script src="{{ asset('public/js/validate.js') }}"></script>
 <script>
$(document).ready(function() {
    $("#customerForm").validate({
        rules: {
            'email': {
                required: true,
                email: true
            },
          },
          message:
          {
              'email':{
                required: 'Please enter user name',
                email: 'Please enter a valid emailid'
              },
          }
        });
});
function checkemailexist(email)
{
  $("#customerForm").removeData("validator");
  $("#phperror").css('display','none');
    $.post('<?php echo route('check-active-user')?>', {
          'email': email,
          '_token': '<?php echo csrf_token();?>',
          }, function(response) {
             if(response == "N"){

                  $("#email-error").css('display','none');
                  $("#email-error").html("");
                  $("#phperror").css('display','none');

                  $("#email-error1").css('display','block');
                  $("#email-error1").html("Username does not exist");
                  $(".chkbtn").attr("disabled", "disabled");  
             }
             else if(response == "NY"){

                  $("#email-error").css('display','none');
                  $("#email-error").html("");
                  $("#phperror").css('display','none');

                  $("#email-error1").css('display','block');
                  $("#email-error1").html("This username is now inactive");
                  $(".chkbtn").attr("disabled", "disabled");  
             }
             else if(response == "Y"){
                  
                  $("#email-error1").css('display','none');
                  $("#email-error1").html("");
                  $(".chkbtn").removeAttr("disabled"); 

                  $("#customerForm").validate({
                    rules: {
                        'email': {
                            required: true,
                            email: true
                        },
                      },
                      message:
                      {
                          'email':{
                            required: 'Please enter user name',
                            email: 'Please enter a valid emailid'
                          },
                      }
                    });
             }  
         })
}
</script> 
</body>
</html>