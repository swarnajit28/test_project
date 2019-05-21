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
          
          <form role="form" method="POST" action="{{ route('login') }}" class="row">
          {{ csrf_field() }}
              <div class="form-group{{ $errors->has('email') ? ' has-error' : 'col-sm-12' }}">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
              </div>
              <div class="form-group{{ $errors->has('email') ? ' has-error' : 'col-sm-12' }}">
                <label for="password">Password</label>
                 <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
              </div>
              <div class="form-group col-sm-12 btn-group">
                <input type="hidden" name="save" value="contact">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
              <a href="{{ route('password.request') }}" class="col-sm-12 forgot-passwordlink">Forgot your password?</a>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{ asset('public/js/login.js') }}"></script>
</body>
</html>