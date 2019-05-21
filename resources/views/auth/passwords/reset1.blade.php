<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800" rel="stylesheet"> 
  <link href="https://fonts.googleapis.com/css?family=Nunito:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
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
            <h2>CRM Login</h2>
            <div class="main-footer">
              <span>© 2018</span> Simplify. All Rights Reserved.
            </div>
          </div>
        </div>
        <div class="login-right">
          <div class="forgot-step">
            <ul>
              <li>1</li>
              <li>2</li>
              <li class="step-active">3</li>
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

         <form role="form" method="POST" action="{{route('password_reset')}}" class="row" id="loginForm">
            {{ csrf_field() }}
          <input type="hidden" name="token" value="{{ $token }}">
              <div class="form-group col-sm-12">
                <label>Enter New Password</label>
                <input type="password" name="new_password" id="new_password" placeholder="Enter new password" class="form-control">
              </div>
              <div class="form-group col-sm-12">
                <label>Re-Enter Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" class="form-control">
              </div>
              <div class="form-group col-sm-12 btn-group">
                <input type="hidden" name="save" value="contact">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
          </form>

        </div>
      </div>
    </div>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{ asset('public/js/forgotpassword.js') }}"></script>
<script src="{{ asset('public/js/validate.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#loginForm").validate({ 
    	rules: {
              'new_password' : {
                    required: true,
                    minlength: 6             
              },
              'confirm_password': {
                    required: true,
                    minlength: 6,
                    equalTo: "#new_password"                                                      
              	},
              },
        messages: {
                'new_password': {
                    required: "Please enter new password",
                    minlength: "Password must consist of at least 6 characters"           
                },
                'confirm_password': {
                    required: "Please reenter new password",
                    minlength: "Password must consist of at least 6 characters",
                    equalTo: "New Password and Confirm Password should be same"
                },
              }
    });
  });
</script>
</body>
</html>
