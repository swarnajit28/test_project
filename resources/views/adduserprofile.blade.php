@extends('layouts.layout')
@section('title')
  <title>Add User</title>
@endsection
@section('css')
<link href="{{ asset('public/css/adduserprofile.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="content content-custom">
              @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
       @endif
      
      <div class="addcustomer-form adduser-profile-page">
        <form role="form" method="POST" action="{{route('submit_user')}}" class="row" id="customerForm">
            {{ csrf_field() }}
           <input type="hidden" value="@if($p_data['id']!=''){{ Crypt::encrypt($p_data['id']) }} @endif" name="{{$encrypted['id']}}">
          <div class="full-wid">
            <div class="form-group col-sm-6">
              <label>Name</label>
              <input type="text" name="{{$encrypted['name']}}" class="form-control">
              <!-- <span class="error">Please enter valid field</span> -->
               @if ($errors->has('name'))
                 <span class="error">{{$errors->first('name')}}
                </span>   
                 @endif
            </div>
          </div>
          <div class="full-wid">
            <div class="col-sm-6">
              <div class="form-group form-group-padding-less">
                <div class="add-attachment" >
                  <!-- add row -->
             <div id="add_attachment1">
                  <!-- add row -->
                  <div class="row add-openrow" id="attach1">                   
                      <div class="manage-field">                        
                          <div class="form-group col-sm-12">
                            <input type="email" name="{{$encrypted['email']}}[]" placeholder="Primary Email" id="{{$encrypted['email']}}1" onKeyUp="checkemailExist('{{$encrypted['email']}}','1')"class="form-control">
<!--                            <a href="#" class="close-link"><i class="fa fa-times" aria-hidden="true"></i></a>-->
                            @if ($errors->has('email.0'))
                            <span class="error">{{$errors->first('email.0')}}</span>   
                            @endif
                            <span class="perr error" id="{{$encrypted['email']}}email1"></span>
                          </div>                       
                      </div>                
                  </div>
                  <!-- add row -->
             </div>
                  <a href="javascript:void(0)" class="btn_add_id1" attach-id="1">Add New Email<i class="fa fa-plus" aria-hidden="true"></i></a>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group form-group-padding-less">
                <div class="add-attachment">
                  <!-- add row -->
                  <div id="add_attachment2">
                  <div class="row add-openrow" id="attachPhone1">                    
                      <div class="manage-field">                        
                          <div class="form-group col-sm-12">
                              <input type="tel" name="{{$encrypted['phone']}}[]" placeholder="Phone" id="{{$encrypted['phone']}}1" onKeyUp="checkPhoneExist('{{$encrypted['phone']}}','1')"  class="form-control">
<!--                            <a href="#" class="close-link"><i class="fa fa-times" aria-hidden="true"></i></a>-->
                          @if ($errors->has('phone.0'))
                            <span class="error">{{$errors->first('phone.0')}}</span>   
                            @endif
                            <span class="perr error" id="{{$encrypted['phone']}}phone1"></span>
                          </div>                        
                      </div>               
                  </div>
                  </div>   
                  <!-- add row -->                 
                  <a href="javascript:void(0)" class="btn_add_id2" attach-id2="1">Add New Phone<i class="fa fa-plus" aria-hidden="true"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="full-wid role-status-part">
            <div class="col-sm-12">
              <div class="viewgrp-dropdownblk">
                <label>Role</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                      <select class="form-control" name="{{$encrypted['role']}}">
                          <option  value="IT">IT Manager</option>
                          <option  value="MA"> Sales Management</option>
                          <option  value="LM"> Lead Manager</option>
                          <option  value="SP"> Sales Persons</option>
                          <option  value="SM"> Senior Management</option>
                          <option  value="OM"> Operations Management</option>
                      </select>
                    </div>
                </div>
              </div>
              <div class="viewgrp-dropdownblk">
                <label>Status</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                      <select class="form-control" name ="{{$encrypted['status']}}">
                        <option  value="1">Active</option>
                        <option  value="0">Inactive</option>
                      </select>
                    </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="clearfix"></div>
          
          <div class="form-group col-lg-12">
            
            <button type="submit" class="btn btn-primary add-btn">Submit</button>
          </div>
        </form>
      </div>      
      
    </section>
   @endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#customerForm").validate({
        rules: {
        {{ $encrypted['name'] }}: {
                required: true,
                minlength: 3
            }, 
            '{{ $encrypted['phone'] }}[]': {
                required: true,
                number:true,
                minlength: 10,
              
            },
          '{{$encrypted['email']}}[]': {
                required: true,
                minlength: 3
            }, 
          
        },
        messages: {
           {{ $encrypted['name'] }}: {
                required: "Please enter your product name",
                minlength: "Your username must consist of at least 3 characters"
            },
            
         
        }
    });
});

 $("body").on("click",".btn_add_id1", function(){
            var id = $(this).attr("attach-id");
            id++;
            $(this).attr("attach-id", id);
            var delclass = "'"+"attach"+id+"'";
            var chkemail = "'"+"{{ $encrypted['email']}}"+"'";
           // alert(delclass);
            var html=' <div class="row add-openrow" id="attach'+id+'"> <div class="manage-field">  <div class="form-group col-sm-12">  <input type="email" name="{{ $encrypted['email']}}[]" id="{{ $encrypted['email']}}'+id+'" onKeyUp="checkemailExist('+chkemail+','+id+')" placeholder="Email" class="form-control"> <span class="perr error" id="{{ $encrypted['email']}}email'+id+'"></span>   <a href="#" class="close-link" onclick="closediv('+delclass+')"><i class="fa fa-times" aria-hidden="true"></i></a>   </div>     </div>    </div>';                                                       
 
            $("#add_attachment1").append(html);
        });
        
        
 $("body").on("click",".btn_add_id2", function(){
            var id = $(this).attr("attach-id2");
            id++;
            $(this).attr("attach-id", id);
            var delclass = "'"+"attachPhone"+id+"'";
            var chkphone = "'"+"{{ $encrypted['phone']}}"+"'";
            var html=' <div class="row add-openrow" id="attachPhone'+id+'"> <div class="manage-field">  <div class="form-group col-sm-12">  <input type="tel" name="{{$encrypted['phone']}}[]" id="{{ $encrypted['phone']}}'+id+'" onKeyUp="checkPhoneExist('+chkphone+','+id+')" placeholder="Phone" class="form-control"> <span class="perr error" id="{{ $encrypted['phone']}}phone'+id+'"></span>  <a href="#" class="close-link" onclick="closediv('+delclass+')"><i class="fa fa-times" aria-hidden="true"></i></a>   </div>     </div>    </div>';                                                       
 
            $("#add_attachment2").append(html);
        }); 
        
  function closediv(id)
 { 
    $("#"+id).remove();     
 }      
   
   
   function checkemailExist(id,inr)
  { 
    var email     = $("#"+id+inr).val();
    //alert(email);
    $.post('{{route('check_email_user')}}', {
          'email': email,
          '_token': '{{csrf_token()}}',
          }, function(response) {
             if(response == "Y"){
                 //alert(id+'email'+inr);
                  $("#"+id+'email'+inr).text("Email No. already exist");
                  $(".add-btn").attr("disabled", "disabled");  
             }else{
                   $("#"+id+'email'+inr).text("");
                   $(".add-btn").removeAttr("disabled"); 
             }  
         })
  }
  
  function checkPhoneExist(id,inr)
  { 
    var phone     = $("#"+id+inr).val();
    //alert(phone);
    $.post('{{route('check_phone_user')}}', {
          'phone': phone,
          '_token': '{{csrf_token()}}',
          }, function(response) {
             if(response == "Y"){
                 //alert(id+'email'+inr);
                  $("#"+id+'phone'+inr).text("Phone No. already exist");
                  $(".add-btn").attr("disabled", "disabled");  
             }else{
                   $("#"+id+'phone'+inr).text("");
                   $(".add-btn").removeAttr("disabled"); 
             }  
         })
  }
</script>

  <script src="{{ asset('public/js/adduserprofile.js') }}"></script>
@endsection
