@extends('layouts.layout')
@section('title')
  <title>Change Password</title>
@endsection
@section('css')
<link href="{{ asset('public/css/addcustomer.css') }}" rel="stylesheet">

@endsection

@section('content')

	<section class="content content-custom">
      
      <div class="addcustomer-form">
      
    <?php

       if(session()->has('status'))
       {
        $data = session('status');
        //print_r($data);
        $old_password        = $data['old_password'];
        $password            = $data['password'];
        $password_confirmation  = $data['password_confirmation'];
       
       }else
       {
        $old_password        = '';
         $password        = '';
         $password_confirmation = '';
       }

       ?> 
       
       @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
        @endif
        <form role="form" method="POST" id="customerForm" action="{{route('save_change_password')}}" class="row">
        {{ csrf_field() }}
        
          <div class="form-group col-sm-6 ">
              <label for="{{ $encrypted['old_password'] }}">Current Password</label>
              <input type="password" name="{{ $encrypted['old_password'] }}" id="{{ $encrypted['old_password'] }}" class="form-control">
              <!-- <span class="error">Please enter valid field</span> -->
              @if(isset($encrypted['user_id']))
                <input type="hidden" name="{{ $encrypted['user_id'] }}" class="form-control" value="<?php echo isset($user->id)?$user->id:''; ?>"> 
                @endif
          </div>
          @if ($errors->has('old_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                @endif
      
 
          <div class="form-group col-sm-6 ">              
          <label for="{{ $encrypted['password'] }}">New Password</label>
              <input type="password" name="{{ $encrypted['password'] }}" id="{{ $encrypted['password'] }}" class="form-control">
              <!-- <span class="error">Please enter valid field</span> -->
          </div>
           @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
          <div class="form-group col-sm-6 ">
              <label for="{{ $encrypted['password_confirmation'] }}">Confirm Password</label>
              <input type="password" name="{{ $encrypted['password_confirmation'] }}" id="{{ $encrypted['password_confirmation'] }}" class="form-control">
               @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
          </div>
     
          
          <div class="clearfix"></div>
          
          <div class="form-group col-lg-12">
            <button type="submit" class="btn btn-primary add-btn" id="submit">Submit</button>
          </div>
        </form>

     <!--   <div class="password-type">
          <label>Your password should be</label>
          <ul>
            <li>abc</li>
            <li>abc</li>
            <li>abc</li>
          </ul>
        </div> -->

      </div>      
      
    </section>

@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#customerForm").validate({
      rules: {
              {{ $encrypted['old_password'] }}: {
                    required: true,
                    minlength: 6
              },
              {{ $encrypted['password'] }}: {
                    required: true,
                    minlength: 6
                   
              },
              {{ $encrypted['password_confirmation'] }}: {
                    required: true,
                    minlength: 6,
                    equalTo: "#{{ $encrypted['password'] }}"
                
              },
               },
        messages: {
            
                {{ $encrypted['old_password'] }}: {
                    required: "Please enter your old password",
                    minlength: "Password must consist of at least 6 characters"
                },
                {{ $encrypted['password'] }}: {
                    required: "Please enter new password",
                    minlength: "Password must consist of at least 6 characters"
                    
                },
                {{ $encrypted['password_confirmation'] }}: {
                    required: "Please reenter new password",
                    minlength: "Password must consist of at least 6 characters",
                    equalTo: "New Password and Confirm Password should be same",
                },
              },
    });
});
</script>

<script src="{{ asset('public/js/addcustomer.js') }}"></script>

@endsection