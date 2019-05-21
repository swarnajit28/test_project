@extends('layouts.layout')
@section('title')
  <title>Customer</title>
@endsection
@section('css')
<link href="{{ asset('public/css/addcustomer.css') }}" rel="stylesheet">

<style type="text/css">
  input.error {
    border: 1px solid red !important;
}
.error textarea {
    border: 1px solid red !important;
}
/*.addcustomer-form form .attach-grp span.error {
    position: unset !important;
}*/
.addcustomer-form form .form-group p.perr{
  position: absolute !important;
    right: 15px !important;
    bottom: 5px !important;
    font-size: 10px !important;
    font-size: 1rem !important;
    text-transform: uppercase !important;
    color: #f65a61 !important;
}
</style>
@endsection

@section('content')
<!-- {{print_r($contact_details)}} -->
<section class="content content-custom">
      
      <div class="addcustomer-form">
        @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
        @endif
        <form role="form" id="customerForm" method="POST" action="{{ route('submit-customer') }}" class="row" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" value="@if($customer_data['id']!=''){{ Crypt::encrypt($customer_data['id']) }} @endif" name="{{$formfield['id']}}">
          <div class="form-group col-sm-6 ">
            <label for="company_name">company name</label>
            <input type="text" name="{{$formfield['company_name']}}" id="{{$formfield['company_name']}}" value="{{ $customer_data['company_name'] }}"  onKeyUp="checkNameExist()" class="form-control">
            @if ($errors->has('company_name'))
                <span class="error">Please enter company name</span>
            @endif
            <p class="perr error" id="pNameError"></p>
          </div>
          <div class="form-group col-sm-6">
            <label class="blank-space">&nbsp;</label>
            <div class="viewgrp-dropdownblk">
              <label>Status</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                    <select class="form-control" name="{{$formfield['is_active']}}" id="{{$formfield['is_active']}}">
                      <option value="1" @if($customer_data['is_active']=='1') selected @endif>Active</option>
                      <option value="0" @if($customer_data['is_active']=='0') selected @endif>Inactive</option>
                    </select>
                  </div>
              </div>
            </div>
          </div>
         
          <div class="form-group col-sm-6 reg_number">
                   <label for="registration_number">company registration number</label>
                    <input type="text" name="{{$formfield['registration_number']}}" id="{{$formfield['registration_number']}}" value="{{ $customer_data['registration_number'] }}"  onKeyUp="checkRegistrationExist()" class="form-control">
                    <p class="perr error" id="rNumberError"></p>
<!--                    <p class="perr error" id="rNumberError">ww</p>-->
                  </div>
          
          <div class="form-group col-sm-6">
            <label  class="blank-space">&nbsp;</label>
          @if (Auth::user()->user_type == 'SP')
          <input type="hidden" value="{{Auth::user()->id}}" name="{{$formfield['sales_person_id']}}">
          @else
          @if($sp['user_id']=='')
          <div class="viewgrp-dropdownblk select-sales-person-field">
            <label>Sales Person</label>
            <div class="viewgrp-dropdown dropdown">            
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="{{$formfield['sales_person_id']}}">
                    <option value="">Select</option>
                    @if(count($salesperson)>0)
                    @foreach($salesperson as $saleperson)
                    <option value="{{$saleperson['id']}}" @if($sp['user_id']==$saleperson['id']) selected @endif>{{$saleperson['display_name']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
            </div>            
          </div>
          @else
           @if ((Auth::user()->user_type == 'MA')&&(isset($is_customer_client)&& $is_customer_client!=1))
           <div class="viewgrp-dropdownblk select-sales-person-field">
            <label>Sales Person</label>
            <div class="viewgrp-dropdown dropdown">            
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="{{$formfield['sales_person_id']}}">
                    <option value="">Select</option>
                    @if(count($nonExclusiveSp)>0)
                    @foreach($nonExclusiveSp as $saleperson)
                    <option value="{{$saleperson['id']}}" @if($sp['user_id']==$saleperson['id']) selected @endif>{{$saleperson['display_name']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
            </div>            
          </div>
           @else
          <input type="hidden" value="{{$sp['user_id']}}" name="{{$formfield['sales_person_id']}}">
          @endif
          @endif
          @endif
          </div>
           
            @if((Auth::user()->user_type == 'OM')&&(isset($is_customer_client)&& $is_customer_client==1))   
            <div class="full-wid">
                    <div class="form-group col-sm-6">
                        <label class="blank-space">&nbsp;</label>
                        <div class="viewgrp-dropdownblk outside-FA">
                            <label>outside FA</label>
                            <div class="viewgrp-dropdown">
                                <div class="magicsearch-wrapper">
                                    <select class="form-control" name="{{$formfield['is_outside_FA']}}" id="{{$formfield['is_outside_FA']}}">
                                        <option value="1" @if($customer_data['is_outside_FA']=='1') selected @endif>Yes</option>
                                        <option value="2" @if($customer_data['is_outside_FA']=='2') selected @endif>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @else
            <input type="hidden" name="{{$formfield['is_outside_FA']}}" value="{{$customer_data['is_outside_FA']}}">
            @endif
            <div class="col-sm-12">
              <div class="add-customer-reapet-part">
                    <div class="form-group col-sm-6">
                     <label for="address _line_1">address line 1</label>
                      <input type="text" name="{{$formfield['address_line_1']}}" id="{{$formfield['address_line_1']}}" value="{{ $customer_data['address _line_1'] }}"  class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                     <label for="address _line_2">address line 2</label>
                      <input type="text" name="{{$formfield['address_line_2']}}" id="{{$formfield['address_line_2']}}" value="{{ $customer_data['address _line_2'] }}"  class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                     <label for="address _line_3">address line 3</label>
                      <input type="text" name="{{$formfield['address_line_3']}}" id="{{$formfield['address_line_3']}}" value="{{ $customer_data['address _line_3'] }}"  class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                     <label for="city">city</label>
                      <input type="text" name="{{$formfield['city']}}" id="{{$formfield['city']}}" value="{{ $customer_data['city'] }}"   class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                     <label for="county">county</label>
                      <input type="text" name="{{$formfield['county']}}" id="{{$formfield['county']}}" value="{{ $customer_data['county'] }}"   class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                     <label for="country">country</label>
                      <input type="text" name="{{$formfield['country']}}" id="{{$formfield['country']}}" value="{{ $customer_data['country'] }}"  class="form-control">
                    </div>
                     <div class="form-group col-sm-6">
                     <label for="postal_code">postal code</label>
                      <input type="text" name="{{$formfield['postal_code']}}" id="{{$formfield['postal_code']}}" value="{{ $customer_data['postal_code'] }}"  class="form-control">
                    </div>
              </div>
            </div>
          
          <div class="col-sm-12" id="add_contact">    
             @if(count($contact_details)<=1)
              <div class="add-customer-reapet-part" id="contact1">                       
              
                <div class="full-wid">

                  <div class="form-group col-sm-6">
                    <label for="{{$formfield['contact_person_name']}}">company contact</label>
                    <input type="text" name="{{$formfield['contact_person_name']}}[]" id="{{$formfield['contact_person_name']}}1" value="{{$contact_details[0]['contact_person_name']}}" class="form-control">
                  </div>
                 
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_phone1']}}">contact phone 1</label>
                  <input type="text" name="{{$formfield['contact_person_phone1']}}[]" id="{{$formfield['contact_person_phone1']}}1" value="{{$contact_details[0]['contact_person_phone1']}}" class="form-control">
                </div>
                <div class="form-group col-sm-6 ">
                  <label for="{{$formfield['contact_person_phone2']}}">contact phone 2</label>
                  <input type="text" name="{{$formfield['contact_person_phone2']}}[]" id="{{$formfield['contact_person_phone2']}}1" value="{{$contact_details[0]['contact_person_phone2']}}" class="form-control">
                  
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_email1']}}">contact Email 1</label>
                  <input type="email" name="{{$formfield['contact_person_email1']}}[]" id="{{$formfield['contact_person_email1']}}1" onKeyUp="checkemailExist('{{$formfield['contact_person_email1']}}','1')" value="{{$contact_details[0]['contact_person_email1']}}" class="form-control">
                  <p class="perr error" id="{{$formfield['contact_person_email1']}}email1"></p>
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_email2']}}">contact Email 2</label>
                  <input type="email" name="{{$formfield['contact_person_email2']}}[]" id="{{$formfield['contact_person_email2']}}1" value="{{$contact_details[0]['contact_person_email2']}}" class="form-control">
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-sm-12">
                  <label for="{{$formfield['contact_person_note']}}">contact notes</label>
                  <textarea class="form-control" rows="5" name="{{$formfield['contact_person_note']}}[]" id="{{$formfield['contact_person_note']}}1">{{$contact_details[0]['contact_person_note']}}</textarea>
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_job_title']}}">Contact Job Title / Role</label>
                  <input type="text" name="{{$formfield['contact_person_job_title']}}[]" id="{{$formfield['contact_person_job_title']}}1" value="{{$contact_details[0]['contact_person_job_title']}}" class="form-control">
                </div>
                <div class="form-group col-sm-6">
<!--                  <label for="{{$formfield['contact_person_job_role']}}">contact job role</label>-->
                  <input type="hidden" name="{{$formfield['contact_person_job_role']}}[]" id="{{$formfield['contact_person_job_role']}}1" value="test_val" class="form-control">
                </div>
              </div>
             @else
             @foreach ($contact_details as $key => $value)

              <div class="add-customer-reapet-part" id="contact{{$key}}">  
                <a href="#" class="cacr-link" onclick="closediv('contact{{$key}}')"><i class="fa fa-times" aria-hidden="true"></i></a>                     
              
                <div class="full-wid">
                  <div class="form-group col-sm-6">
                    <label for="{{$formfield['contact_person_name']}}">company contact</label>
                    <input type="text" name="{{$formfield['contact_person_name']}}[]" id="{{$formfield['contact_person_name']}}{{$key}}" value="{{$contact_details[$key]['contact_person_name']}}" class="form-control">
                  </div>
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_phone1']}}">contact phone 1</label>
                  <input type="text" name="{{$formfield['contact_person_phone1']}}[]" id="{{$formfield['contact_person_phone1']}}{{$key}}" value="{{$contact_details[$key]['contact_person_phone1']}}" class="form-control">
                </div>
                <div class="form-group col-sm-6 ">
                  <label for="{{$formfield['contact_person_phone2']}}">contact phone 2</label>
                  <input type="text" name="{{$formfield['contact_person_phone2']}}[]" id="{{$formfield['contact_person_phone2']}}{{$key}}" value="{{$contact_details[$key]['contact_person_phone2']}}" class="form-control">
                  
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_email1']}}">contact Email 1</label>
                  <input type="email" name="{{$formfield['contact_person_email1']}}[]" id="{{$formfield['contact_person_email1']}}{{$key}}" onKeyUp="checkemailExist('{{$formfield['contact_person_email1']}}','{{$key}}')" value="{{$contact_details[$key]['contact_person_email1']}}" class="form-control">
                  <p class="perr error" id="{{$formfield['contact_person_email1']}}email{{$key}}"></p>
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_email2']}}">contact Email 2</label>
                  <input type="email" name="{{$formfield['contact_person_email2']}}[]" id="{{$formfield['contact_person_email2']}}{{$key}}" value="{{$contact_details[$key]['contact_person_email2']}}" class="form-control">
                </div>
                <div class="clearfix"></div>
                <div class="form-group col-sm-12">
                  <label for="{{$formfield['contact_person_note']}}">contact notes</label>
                  <textarea class="form-control" rows="5" name="{{$formfield['contact_person_note']}}[]" id="{{$formfield['contact_person_note']}}{{$key}}">{{$contact_details[$key]['contact_person_note']}}</textarea>
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_job_title']}}">contact job title</label>
                  <input type="text" name="{{$formfield['contact_person_job_title']}}[]" id="{{$formfield['contact_person_job_title']}}{{$key}}" value="{{$contact_details[$key]['contact_person_job_title']}}" class="form-control">
                </div>
                <div class="form-group col-sm-6">
                  <label for="{{$formfield['contact_person_job_role']}}">contact job role</label>
                  <input type="text" name="{{$formfield['contact_person_job_role']}}[]" id="{{$formfield['contact_person_job_role']}}{{$key}}" value="{{$contact_details[$key]['contact_person_job_role']}}" class="form-control">
                </div>
              </div>
             @endforeach
             @endif
            
            
          </div>


          <div class="form-group add-attachment-group col-sm-12">
            <div class="add-attachment">
              <a href="javascript:void(0)" class="btn_add_id" contact-id="1">Add New Company Contact<i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
          </div>

          <div class="form-group col-sm-12">
            <div class="add-attachment manage-attachment">
              <div class="add-openrow" id="add_attachment">
                <h2>Manage Attachment</h2> 
                
                @if(count($attach_details)<=1)              
                <div class="row" id="attach1">
                  <div class="manage-field">
                    <div class="form-group attach-grp col-sm-6">
                      <input type="text" name="{{$formfield['customer_attachment_name']}}[]" id="{{$formfield['customer_attachment_name']}}1" value="{{$attach_details[0]['customer_attachment_name']}}" placeholder="Filename" class="form-control">
                    </div>
                    <div class="form-group attach-grp col-sm-6">
                      @if($attach_details[0]['customer_attachment_file_name']!='')
                      <input type="text" class="form-control" readonly="" name="{{$formfield['hide_attach']}}[]" value="{{$attach_details[0]['customer_attachment_file_name']}}">

                      <input type="file" style="display: none;" readonly="" class="form-control" name="{{$formfield['customer_attachment_file_name']}}[]" id="{{$formfield['customer_attachment_file_name']}}1">
                      @else
                      <input type="text" style="display: none;" readonly="" class="form-control" name="{{$formfield['hide_attach']}}[]" value="0">

                      <input type="file" class="form-control adfile custom-input-file" name="{{$formfield['customer_attachment_file_name']}}[]" id="{{$formfield['customer_attachment_file_name']}}1">
                      @endif
                      
                    </div>
                  </div>                  
                  <a href="#" class="close-link" onclick="closediv('attach1')"><i class="fa fa-times" aria-hidden="true"></i></a>                  
                </div>
                @else
                @foreach ($attach_details as $key => $value)
                <div class="row" id="attach{{$key}}">
                  <div class="manage-field">
                    <div class="form-group attach-grp col-sm-6">
                      <input type="text" name="{{$formfield['customer_attachment_name']}}[]" id="{{$formfield['customer_attachment_name']}}{{$key}}" placeholder="Filename" class="form-control" value="{{$attach_details[$key]['customer_attachment_name']}}">
                    </div>
                    <div class="form-group attach-grp col-sm-6">
                      @if($attach_details[$key]['customer_attachment_file_name']!='')
                      <input type="text" class="form-control" readonly="" name="{{$formfield['hide_attach']}}[]" value="{{$attach_details[$key]['customer_attachment_file_name']}}">

                      <input type="file" style="display: none;" readonly="" class="form-control" name="{{$formfield['customer_attachment_file_name']}}[]" id="{{$formfield['customer_attachment_file_name']}}{{$key}}">
                      @else
                      <input type="text" style="display: none;" readonly="" class="form-control" name="{{$formfield['hide_attach']}}[]" value="0">

                      <input type="file" class="form-control adfile custom-input-file" name="{{$formfield['customer_attachment_file_name']}}[]" id="{{$formfield['customer_attachment_file_name']}}{{$key}}">
                      @endif

                    </div>
                  </div>       
                            
                <a href="#" class="close-link" onclick="closediv('attach{{$key}}')"><i class="fa fa-times" aria-hidden="true"></i></a>  
                             
                </div>
                @endforeach
                @endif
              </div>
              <a href="javascript:void(0)" class="btn_add_id1" attach-id="1">Add More Attachment<i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
          </div>

          
          

          <div class="clearfix"></div>
          
          <div class="form-group col-lg-12">
            <!-- <button type="submit" class="btn btn-primary reset-btn">Cancel</button> -->
            <button type="submit" class="btn btn-primary add-btn">@if($customer_data['id']!='')Edit @else Add @endif Customer</button>
          </div>
        </form>
      </div>      
      
</section>

@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<script type="text/javascript">
  jQuery.validator.addClassRules('adfile', {
        required: true /*,
        other rules */
    });
  $(document).ready(function() {
     
      
      $("#customerForm").validate({
          ignore: '',
          rules: {
              <?php echo $formfield['company_name']?>: "required",   
              <?php echo $formfield['sales_person_id']?>: "required",          
              "<?php echo $formfield['contact_person_name']?>[]": "required",
              "<?php echo $formfield['contact_person_phone1']?>[]": {
                  required: true,
                  number: true
              },
              "<?php echo $formfield['contact_person_phone2']?>[]": {
                  number: true
              },
              "<?php echo $formfield['contact_person_email1']?>[]": {
                  required: true,
                  email: true
              },
                         
              "<?php echo $formfield['contact_person_note']?>[]": "required",           
              "<?php echo $formfield['contact_person_job_title']?>[]": "required",           
              "<?php echo $formfield['contact_person_job_role']?>[]": "required",         
              "<?php echo $formfield['customer_attachment_name']?>[]": "required",       
              /*"<?php echo $formfield['customer_attachment_file_name']?>[]": 
                {
                    required: true,
                }  ,*/
          },
          messages: {
              <?php echo $formfield['company_name']?>: "Please enter company name",
              <?php echo $formfield['sales_person_id']?>: "Please select sales person",
              "<?php echo $formfield['contact_person_name']?>[]": "Please enter your company contact ",
              "<?php echo $formfield['contact_person_phone1']?>[]":
                {
                    required: "Please enter your phone number",
                    number: "Please enter only numeric value"
                },
              "<?php echo $formfield['contact_person_phone2']?>[]":
                {
                    number: "Please enter only numeric value"
                },
              "<?php echo $formfield['contact_person_email1']?>[]": "Please enter a valid email address",
              
              "<?php echo $formfield['contact_person_note']?>[]": "Please enter a note",
              "<?php echo $formfield['contact_person_job_title']?>[]": "Please enter job title",
              "<?php echo $formfield['contact_person_job_role']?>[]": "Please enter job role",
              "<?php echo $formfield['customer_attachment_name']?>[]": "Please enter filename",
              /*"<?php echo $formfield['customer_attachment_file_name']?>[]": "Please select file",*/
          }
      });
  });

 $("body").on("click",".btn_add_id1", function(){
            var id = $(this).attr("attach-id");
            id++;
            $(this).attr("attach-id", id);
            var delclass = "'"+"attach"+id+"'";
            var html='<div class="row" id="attach'+id+'"><div class="manage-field"><div class="form-group  attach-grp col-sm-6"><input type="text" name="<?php echo $formfield['customer_attachment_name']?>[]" placeholder="Filename" id="<?php echo $formfield['customer_attachment_name']?>'+id+'" class="form-control"></div><div class="form-group  attach-grp col-sm-6"><input type="file" class="form-control adfile custom-input-file'+id+'" id="<?php echo $formfield['customer_attachment_file_name']?>'+id+'" name="<?php echo $formfield['customer_attachment_file_name']?>[]"><input type="text" style="display: none;" readonly="" value="0" name="<?php echo $formfield['hide_attach']?>[]"></div></div><a href="#" class="close-link" onclick="closediv('+delclass+')"><i class="fa fa-times" aria-hidden="true"></i></a></div>';
            $("#add_attachment").append(html);

            $('.custom-input-file'+id).simpleFileInput({
                placeholder : 'Attach file',
                buttonText : 'Select',
                allowedExts : ['pdf', 'txt','png', 'gif', 'jpg', 'jpeg']
              });
        });



 $("body").on("click",".btn_add_id", function(){
            var id = $(this).attr("contact-id");
            id++;
            $(this).attr("contact-id", id);
            var delclass = "'"+"contact"+id+"'";
            var chkemail = "'"+"<?php echo $formfield['contact_person_email1']?>"+"'";
            var html='<div class="add-customer-reapet-part" id="contact'+id+'"><a href="#" class="cacr-link" onclick="closediv('+delclass+')"><i class="fa fa-times" aria-hidden="true"></i></a><div class="full-wid"><div class="form-group col-sm-6"><label for="<?php echo $formfield['contact_person_name']; ?>">company contact</label><input type="text" name="<?php echo $formfield['contact_person_name']; ?>[]" id="<?php echo $formfield['contact_person_name']; ?>'+id+'" class="form-control"></div></div><div class="form-group col-sm-6"><label for="<?php echo $formfield['contact_person_phone1']; ?>">contact phone 1</label><input type="text" name="<?php echo $formfield['contact_person_phone1']; ?>[]" id="<?php echo $formfield['contact_person_phone1']; ?>'+id+'" class="form-control"></div><div class="form-group col-sm-6 "><label for="<?php echo $formfield['contact_person_phone2']; ?>">contact phone 2</label><input type="text" name="<?php echo $formfield['contact_person_phone2']; ?>[]" id="<?php echo $formfield['contact_person_phone2']; ?>'+id+'" class="form-control"></div><div class="form-group col-sm-6"><label for="<?php echo $formfield['contact_person_email1']?>">contact Email 1</label><input type="email" name="<?php echo $formfield['contact_person_email1']?>[]" id="<?php echo $formfield['contact_person_email1']?>'+id+'" onKeyUp="checkemailExist('+chkemail+','+id+')" class="form-control"><p class="perr error" id="<?php echo $formfield['contact_person_email1']?>email'+id+'"></p></div><div class="form-group col-sm-6"><label for="<?php echo $formfield['contact_person_email2']?>">contact Email 2</label><input type="email" name="<?php echo $formfield['contact_person_email2']?>[]" id="<?php echo $formfield['contact_person_email2']?>'+id+'" class="form-control"></div><div class="clearfix"></div><div class="form-group col-sm-12"><label for="<?php echo $formfield['contact_person_note']; ?>">contact notes</label><textarea class="form-control" rows="5" name="<?php echo $formfield['contact_person_note']; ?>[]" id="<?php echo $formfield['contact_person_note']; ?>'+id+'"></textarea></div><div class="form-group col-sm-6"><label for="<?php echo $formfield['contact_person_job_title']; ?>">contact job title</label><input type="text" name="<?php echo $formfield['contact_person_job_title']; ?>[]" id="<?php echo $formfield['contact_person_job_title']; ?>'+id+'" class="form-control"></div><div class="form-group col-sm-6"><label for="<?php echo $formfield['contact_person_job_role']; ?>">contact job role</label><input type="text" name="<?php echo $formfield['contact_person_job_role']; ?>[]" id="<?php echo $formfield['contact_person_job_role']; ?>'+id+'" class="form-control"></div></div>';
            $("#add_contact").append(html);
        });

 function closediv(id)
 { 
    $("#"+id).remove();     
 }

 function checkemailExist(id,inr)
  { 
    var email     = $("#"+id+inr).val();
    //alert(email);
    $.post('<?php echo route('check-email')?>', {
          'email': email,
          '_token': '<?php echo csrf_token();?>',
          }, function(response) {
             if(response == "Y"){
                  $("#"+id+'email'+inr).text("Email No. already exist");
                  $(".add-btn").attr("disabled", "disabled");  
             }else{
                   $("#"+id+'email'+inr).text("");
                   $(".add-btn").removeAttr("disabled"); 
             }  
         })
  }
  
  
  function checkNameExist()
  { 
    var company_name= $({{$formfield['company_name']}}).val();
    $.post('{{route('duplicateCustomerCheck')}}', {
          'company_name': company_name,
          '_token': '{{csrf_token()}}',
          }, function(response) {
             if(response == "Y"){  
                  $("#pNameError").text("Name already exist");
                  $(".add-btn").attr("disabled", "disabled");  
             }else{
                   $("#pNameError").text("");
                   $(".add-btn").removeAttr("disabled"); 
             }  
         })
  }
  
  function checkRegistrationExist()
  { 
    var registratoin_number = $({{$formfield['registration_number']}}).val();
    $.post('{{route('duplicateRegistrationCheck')}}', {
          'registration_number': registratoin_number,
          '_token': '{{csrf_token()}}',
          }, function(response) {
              response = JSON.parse(response);
              //console.log(response);
             // alert(response.status);
             if(response.status == "Y"){  
                  //var text_data="Already exist. This customar assign with "+response.customar_information.company_name;
                  var text_data = "Another Sales Executive is currently exploring new business opportunities with this client, please refer to your manager for guidance.";
                  $("#rNumberError").text(text_data);
                  $(".add-btn").attr("disabled", "disabled");  
             }else{
                   $("#rNumberError").text("");
                   $(".add-btn").removeAttr("disabled"); 
             }  
         })
  }
</script>
<script src="{{ asset('public/js/addcustomer.js') }}"></script>
<script type="text/javascript">
  $('.custom-input-file').simpleFileInput({
    placeholder : 'Attach file',
    buttonText : 'Select',
    allowedExts : ['pdf', 'txt','png', 'gif', 'jpg', 'jpeg']
  });
</script>
@endsection
