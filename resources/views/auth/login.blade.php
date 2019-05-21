<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" name="viewport">
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800" rel="stylesheet"> 
  <link href="https://fonts.googleapis.com/css?family=Nunito:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
  @include('layouts.favicon')
<link href="{{ asset('public/css/login.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
<body>
  <div class="outer">
    <div class="middle">
      <div class="inner">
        <div class="login-left login-header">
          <div class="login-leftinner">
            <a href="#"><img src="{{ asset('public/images/login-logo.png') }}" width="207" height="60" alt=""></a>
            <h2>CRM Login</h2>
            <div class="main-footer hidden-xs">
                <span> <div id="year"> © 2019</div></span> Simplify. All Rights Reserved.
              <a href="https://simplifybusiness.co.uk" target="_blank">simplifybusiness.co.uk</a>
            </div>

          </div>
        </div>
        <div class="login-right login-right">
          
          <form role="form" method="POST" action="{{ route('login') }}" class="row" id="user_login">
          {{ csrf_field() }}
              <div class="form-group col-sm-12">
                <label for="username">Username</label>
                <input id="username" type="email" class="form-control @if ($errors->has('username')) {{ ' error'}} @endif" name="username" id="username" value="{{ old('username') }}">

                @if ($errors->has('username'))
                                    <span class="error">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
              </div>
              <div class="form-group col-sm-12">
                <label for="password">Password</label>
                 <input id="password" type="password" class="form-control @if ($errors->has('username')) {{ ' error'}} @endif" id="password" name="password">

                                
              </div>
              <div class="form-group col-sm-12 btn-group">
                <input type="hidden" name="save" value="contact">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
              <a href="{{ route('password.request') }}" class="col-sm-12 forgot-passwordlink">Forgot your password?</a>
          </form>
          <div class="main-footer hidden-sm hidden-md hidden-lg">
            <span>© 2018</span> Simplify. All Rights Reserved.
            <a href="https://simplifybusiness.co.uk" target="_blank">simplifybusiness.co.uk</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{ asset('public/js/login.js') }}"></script>

</body>
</html>
<script src="{{ asset('public/js/validate.js') }}"></script>
</script>
<script type="text/javascript">
     $(document).ready(function() {
           var d = new Date();
           var n="© "
           n+=d.getFullYear();
           document.getElementById("year").innerHTML = n;
        });
    </script>
        
<script type="text/javascript">
  $(document).ready(function() {
     $("#user_login").validate({
        rules:{
                'username' : { required: true },
                'password' : { required: true }
              },

        message:{
                  'username' : "Username required",
                  'password' : "Password required"
                }
      });
  });

</body>