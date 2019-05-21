@extends('layouts.layout')
@section('title')
  <title>Edit Profile</title>
@endsection
@section('css')
<link href="{{ asset('public/css/editprofile.css') }}" rel="stylesheet">
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

      <div class="addcustomer-form adduser-profile-page edit-profile-page">
        <form role="form" method="POST" action="{{route('update_profile')}}" class="row" id="edituser">
        {{ csrf_field() }}
          <div class="full-wid">
            <div class="form-group col-sm-6">
              <label>Name</label>
              <input type="text" name="{{$encrypted['name']}}" class="form-control" value="{{ $users[0]['display_name'] }}">
              @if ($errors->has('name'))
                 <span class="error">{{$errors->first('name')}}
                </span>   
                 @endif
            </div>
          </div>
          <div class="full-wid role-status-part">
            <div class="col-sm-12">
              <div class="viewgrp-dropdownblk">
                <label>Role</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                      <select class="form-control" name="{{$encrypted['role']}}" @if(($user->user_type != 'IT') || ($user->id == $users[0]['id'])) disabled @endif>
                        <option value="">Select Role</option>
                    <option value="IT" @if(isset($users[0]['user_type']) && $users[0]['user_type'] == 'IT'){{ 'selected' }} @endif>IT</option>
                    <option value="MA" @if(isset($users[0]['user_type']) && $users[0]['user_type'] == 'MA'){{ 'selected' }} @endif>Sales Management</option>
                    <option value="LM" @if(isset($users[0]['user_type']) && $users[0]['user_type'] == 'LM'){{ 'selected' }} @endif>Lead Manager</option>
                    <option value="SP" @if(isset($users[0]['user_type']) && $users[0]['user_type'] == 'SP'){{ 'selected' }} @endif>Sales Person</option>
                    <option value="SM" @if(isset($users[0]['user_type']) && $users[0]['user_type'] == 'SM'){{ 'selected' }} @endif>Senior Management</option>
                    <option value="OM" @if(isset($users[0]['user_type']) && $users[0]['user_type'] == 'OM'){{ 'selected' }} @endif>Operations Management</option>
                      </select>
                    </div>
                </div>
              </div>
              <div class="viewgrp-dropdownblk">
                <label>Status</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                      <select class="form-control" name="{{$encrypted['status']}}" @if(($user->user_type != 'IT') || ($user->id == $users[0]['id'])) disabled @endif>
                        <option value="1" @if(isset($users[0]['is_active']) && $users[0]['is_active'] == '1'){{ 'selected' }} @endif>Active</option>
                        <option value="0" @if(isset($users[0]['is_active']) && $users[0]['is_active'] == '0'){{ 'selected' }} @endif>Inactive</option>
                      </select>
                      <input type="hidden" name="{{$encrypted['user_id']}}" id="{{$encrypted['user_id']}}" value="{{ Crypt::encrypt($users[0]['id']) }}"/>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="full-wid edit-profile-page-update">
            <div class="col-sm-7">
              <div class="form-group form-group-padding-less">
                <label>Email</label>
                <div class="add-attachment">
                  <!-- add row -->
                  
                   @for($i=0;$i<count($emails);$i++)
                  	@if($emails[$i]->is_primary == 1)
                  	
	                  <div class="add-openrow primary-email">                    
	                     <sub> {{ $emails[$i]->user_email }}</sub> <a href="#" class="type-email"><i class="fa fa-check-circle" aria-hidden="true"></i> <span>Primary Email</span></a>                      
	                  </div>
	                @elseif($emails[$i]->is_verified == 1)
              
                  <div class="add-openrow" id="att_{{$emails[$i]->id}}">                    
                     <sub> {{ $emails[$i]->user_email }} </sub> <a href="#" class="type-email" onclick="set_primary_email('{{ Crypt::encrypt($emails[$i]->id) }}')"><i class="fa fa-check-circle" aria-hidden="true"></i> <span>Primary Email</span></a>

                      <a href="#" class="close-link" onclick="closedivEmail('att_{{$emails[$i]->id}}')"><i class="fa fa-times" aria-hidden="true"></i></a>
                  </div>
                  @elseif($emails[$i]->is_verified == 0)
                  <div class="add-openrow verify-emailnow" id="att_{{$emails[$i]->id}}">                    
                      <sub>  {{ $emails[$i]->user_email }} </sub> <a href="#" class="type-email" onclick="sendMail('{{ $emails[$i]->user_email }}','{{ $emails[$i]->id }}')"> <span>Verify Email Now</span></a>
                      <a href="#" class="close-link" onclick="closedivEmail('att_{{$emails[$i]->id}}')"><i class="fa fa-times" aria-hidden="true"></i></a>
                  </div>
                  @endif 
                  @endfor
                 
            <!--      <div class="add-openrow" >
                  	 <div class="form-group col-sm-10">
                            <input type="email" name="{{$encrypted['email']}}[]" placeholder="Email" id="{{$encrypted['email']}}" onKeyUp="checkemailExist('{{$encrypted['email']}}','1')"class="form-control">
                     </div>
                     <a href="#" class="close-link"><i class="fa fa-times" aria-hidden="true"></i></a>
                  </div> -->
                  <div id="add_attachment1">
                  </div>
                  <!-- add row -->
                   <a href="javascript:void(0)" class="btn_add_id1" attach-id="1">Add New Email<i class="fa fa-plus" aria-hidden="true"></i></a>


              <!--    <a href="#">Add New Email<i class="fa fa-plus" aria-hidden="true"></i></a> -->
                
               </div>
              </div>
            </div>
            <div class="col-sm-5">
              <label>Phone No.</label>
              <div class="form-group form-group-padding-less">
              
                <div class="add-attachment">
                  <!-- add row -->
                 @for($i=0;$i<count($phones);$i++)
                 <div class="add-openrow primary-email" id="attPhone_{{$phones[$i]->id}}">                    
                      {{ $phones[$i]->user_phone }}  
                     <a href="#" class="close-link" onclick="closedivPhone('attPhone_{{$phones[$i]->id}}')"><i class="fa fa-times" aria-hidden="true"></i></a>               
                  </div> 
                 @endfor
            
                  <div id="add_attachment2">
                  </div>
                <!--  <div class="add-openrow">                    
                      <a href="#" class="close-link"><i class="fa fa-times" aria-hidden="true"></i></a>
                  </div> -->
                  <!-- add row -->                 
                 <a href="javascript:void(0)" class="btn_add_id2" attach-id2="1">Add New Phone<i class="fa fa-plus" aria-hidden="true"></i></a>
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
	    $("#edituser").validate({
	        rules: {
	        
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
	      });
	});
	</script>

  <script>
  $("body").on("click",".btn_add_id1", function(){
            var id = $(this).attr("attach-id");
           // alert(id);
            id++;
            $(this).attr("attach-id", id);
            var delclass = "'"+"att_"+id+"'";
            var chkemail = "'"+"{{ $encrypted['email']}}"+"'";
           // alert(delclass);
          /*  var html=' <div class="row add-openrow" id="attach'+id+'"> <div class="manage-field">  <div class="form-group col-sm-12">  <input type="email" name="{{ $encrypted['email']}}[]" id="{{ $encrypted['email']}}'+id+'" onKeyUp="checkemailExist('+chkemail+','+id+')" placeholder="Email" class="form-control"> <p class="perr error" id="{{ $encrypted['email']}}email'+id+'"></p>   <a href="#" class="close-link" onclick="closediv('+delclass+')"><i class="fa fa-times" aria-hidden="true"></i></a>   </div>     </div>    </div>';     */

 			var html = '<div class="row add-openrow margin-btn-less" id="att_'+id+'"><div class="form-group col-sm-10"><input type="email" name="{{$encrypted['email']}}[]" placeholder="Email" id="{{$encrypted['email']}}'+id+'" onKeyUp="checkemailExist('+chkemail+','+id+')"class="form-control">@if ($errors->has('email.0'))<span class="error">{{$errors->first('email.0')}}</span>   
                            @endif <span class="perr error" id="{{ $encrypted['email']}}email'+id+'"></span></div><a href="#" class="close-link" onclick="closediv('+delclass+')"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>' ;
            $("#add_attachment1").append(html);
        });

  $("body").on("click",".btn_add_id2", function(){
            var id = $(this).attr("attach-id2");
            id++;
            $(this).attr("attach-id", id);
            var delclass = "'"+"attPhone_"+id+"'";
            var chkphone = "'"+"{{ $encrypted['phone']}}"+"'";
         /*   var html=' <div class="row add-openrow" id="attachPhone'+id+'"> <div class="manage-field">  <div class="form-group col-sm-12">  <input type="tel" name="{{$encrypted['phone']}}[]" id="{{ $encrypted['phone']}}'+id+'" onKeyUp="checkPhoneExist('+chkphone+','+id+')" placeholder="Phone" class="form-control"> <p class="perr error" id="{{ $encrypted['phone']}}phone'+id+'"></p>  <a href="#" class="close-link" onclick="closediv('+delclass+')"><i class="fa fa-times" aria-hidden="true"></i></a>   </div>     </div>    </div>'; */
          var html = '<div class="row add-openrow primary-email margin-btn-less" id="attPhone_'+id+'"><div class="form-group col-sm-10"><input type="text" name="{{$encrypted['phone']}}[]" placeholder="Phone" id="{{$encrypted['phone']}}'+id+'" onKeyUp="checkPhoneExist('+chkphone+','+id+')"  class="form-control"> @if ($errors->has('phone.0'))<span class="error">{{$errors->first('phone.0')}}</span>@endif<span class="perr error" id="{{ $encrypted['phone']}}phone'+id+'"></span></div><a href="#" class="close-link" onclick="closediv('+delclass+')"><i class="fa fa-times" aria-hidden="true"></i></a></div>' ;                                                      
 
            $("#add_attachment2").append(html);
        }); 


  function closedivEmail(id)
  { 
  	var mid = id.split('_');
  	var eid = mid[1];
  	//var uid = $('#{{$encrypted['user_id']}}').val();
  	if (confirm('Are you sure you want to delete this?')) 
        {
          $.ajax({
              url: "{{ route('delete_user_email') }}",
              type: "POST",
              data: {'id':eid,'_token': '<?php echo csrf_token();?>'},  
              success: function (response) {
                  if(response>0)
                  {

                   document.location.href= "";
                   
                   // redirect('/master_loan');
                  }
              }
          }); 
         }
         
  }       

  function closedivPhone(id)
  { 
  	// alert(id);
  	var mid = id.split('_');
  	var eid = mid[1];
  	//var uid = $('#{{$encrypted['user_id']}}').val();
  	if (confirm('Are you sure you want to delete this?')) 
        {
          $.ajax({
              url: "{{ route('delete_user_phone') }}",
              type: "POST",
              data: {'id':eid,'_token': '<?php echo csrf_token();?>'},  
              success: function (response) {
                  if(response>0)
                  {

                   document.location.href= "";
                   // redirect('/master_loan');
                  }
              }
          }); 
         } 
          
  }       
   
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

  function set_primary_email(id)
 	{
 		var uid = $('#{{$encrypted['user_id']}}').val();
 		//alert(uid);
 	   // var cry_uid = {{ Crypt::encrypt("'+uid+'") }} ;
 	   // alert(cry_uid);
 		$.ajax({
              url: "{{ route('set_primary_email') }}",
              type: "POST",
              data: {'id':id,'user_id':uid,'_token': '<?php echo csrf_token();?>'},  
              success: function (response) {
                  if(response>0)
                  {
                   document.location.href= "";
                   // redirect('/master_loan');
                  }
              }
          });   
 	} 


 	function sendMail(email_id,eid)
 	{
 		var uid = $('#{{$encrypted['user_id']}}').val();
 		$.ajax({
 			url: "{{ route('sendVerificationMail') }}",
 			type: "POST",
 			data: {'eid':eid,'email_id':email_id,'_token': '<?php echo csrf_token();?>'},
 			success: function(response){
 				 if(response == '')
                  {
                  	alert('A verification email has been sent to selected emailid. Please verify the mail');
                   document.location.href= "";
                   // redirect('/master_loan');
                  }
 			}
 		})
 	}
  </script>


  <script src="{{ asset('public/js/editprofile.js') }}"></script>
@endsection