@extends('layouts.layout')
@section('title')
  <title>@if($lead_data['id']!='')Edit @else Add @endif Lead</title>
@endsection
@section('css')
<link href="{{ asset('public/css/addlead.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
      <div class="lead-manage-form">
        
          @if (session('success_message'))
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('success_message') }}
          </div> 
          @endif
          <form role="form" id="leadadd" method="POST" action="{{route('submit-lead')}}" class="" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" value="@if($lead_data['id']!=''){{ Crypt::encrypt($lead_data['id']) }} @endif" name="{{$formfield['id']}}">
        <div class="view-by">
          <!-- <form action="#" class=""> -->
            <div class="field-second-grp">
              <div class="viewgrp-dropdownblk">
                <label>Customer Name</label>
                <div class="viewgrp-dropdown">
                  <span class="edit-magicsearch">{{$customer_details['company_name']}}</span>  
                </div>
                <input type="hidden" name="{{$formfield['customer_id']}}" id="{{$formfield['customer_id']}}"  value="{{Crypt::encrypt($lead_data['custom_id'])}}">
              </div>
              <div class="viewgrp-dropdownblk select-contact">
                <label>Select Contact</label>
                <div class="viewgrp-dropdown dropdown">            
                    <div class="magicsearch-wrapper">
                      <select class="form-control" name="{{$formfield['customer_contact_person_id']}}" onchange="fetchdetails(this.value);" id="contact_persons">
                        <option value="">Select</option>
                        @if (count($contact_person)>0)
                        @foreach ($contact_person as $contacts)             
                         <option value="{{$contacts['id']}}" @if($lead_data['customer_contact_person_id']==$contacts['id']) selected="" @endif>{{$contacts['contact_person_name']}}</option>
                        @endforeach 
                        @endif
                      </select>
                    </div>
                </div>
              </div>
            </div>
          <!-- </form>    -->       
        </div>
          
          <div class="form-group product_sec">
              <label for="{{$formfield['lead_source']}}">Lead source</label>
              <input type="text" name="{{$formfield['lead_source']}}"   value="{{$lead_data['lead_source']}}" class="form-control">
              <p class="perr error" id="pNameError"></p>
          </div>

        <div id="contact-person-details">
          @if(!empty($contact_person_details))
          <div class="lead-person-details" >
            <h3>{{$contact_person_details['contact_person_name']}}</h3>
            <p><a href="#"><i class="fa fa-globe"></i> {{$contact_person_details['contact_person_email1']}}</a></p>
            <p><i class="fa fa-phone"></i> {{$contact_person_details['contact_person_phone1']}}</p>
          </div>
          @endif
        </div>  
        <div id="attachdoc-details">
          <div class="document-block" >
           <h4>Agreement Documents</h4>
           @if(!empty($customer_attachments))
           <ul>
            @foreach ($customer_attachments as $key => $value)
            <li>{{$customer_attachments[$key]['customer_attachment_name']}}<a href="{{asset('public/uploads/customer/'.$customer_attachments[$key]['customer_attachment_file_name'])}}" download><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            @endforeach
           </ul>
           @else
           No attached documents
           @endif
          </div>
        </div>
        <div class="additional-information-blk additional-information-blk-dev">
            @if (Auth::user()->user_type != 'SP' && Auth::user()->user_type == 'MA' )
          <div class="viewgrp-dropdownblk select-contact">
            <label>Select Sales Person</label>
            <div class="viewgrp-dropdown dropdown">            
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="{{$formfield['sales_person_id']}}">
                    <!-- <option value="">Select</option> -->
                    @if(count($salesperson)>0)
                    @foreach($salesperson as $saleperson)
                    <option value="{{$saleperson['id']}}" @if($lead_data['sales_person_id']==$saleperson['id']) selected=""  @endif>{{$saleperson['display_name']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
            </div>
          </div>
            @endif
            @if (Auth::user()->user_type != 'SP' && Auth::user()->user_type != 'MA' )
            <div class="viewgrp-dropdownblk select-contact">
              <label>Select Sales Person</label>
              <div class="viewgrp-dropdown dropdown">            
                  <div class="magicsearch-wrapper">
                    <select class="form-control" name="{{$formfield['sales_person_id']}}">
                      <!-- <option value="">Select</option> -->
                      @if(count($nonExclusiveSp)>0)
                      @foreach($nonExclusiveSp as $saleperson)
                      <option value="{{$saleperson['id']}}" @if($lead_data['sales_person_id']==$saleperson['id']) selected=""  @endif>{{$saleperson['display_name']}}</option>
                      @endforeach
                      @endif
                    </select>
                  </div>
              </div>
            </div>
              @endif

          <div class="viewgrp-dropdownblk  @if(Auth::user()->user_type != 'SP') select-contact @endif">
            <label>Select Lead Type</label>
            <div class="viewgrp-dropdown dropdown">            
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="{{$formfield['strength']}}">
                    <option value="0" @if($lead_data['lead_strength_id']=='0') selected="" @endif>New</option>
                    @if(count($strengths)>0)
                    @foreach($strengths as $strength)
                    @if($strength['id']!=0)
                    <option value="{{$strength['id']}}" @if($lead_data['lead_strength_id']==$strength['id']) selected="" @endif>{{$strength['loan_type']}}</option>
                    @endif
                    @endforeach
                    @endif
                  </select>
                </div>
            </div>
          </div>
          <div class="additional-information-blk">            
          @if (Auth::user()->user_type != 'SP')
          <div class="viewgrp-dropdownblk select-contact">
            <label>Lead Status</label>
            <div class="viewgrp-dropdown dropdown">            
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="{{$formfield['is_active']}}">
                    <option value="1" @if($lead_data['is_active']=='1') selected="" @endif>Active</option>
                    <option value="0" @if($lead_data['is_active']=='0') selected="" @endif>Dead</option>                   
                  </select>
                </div>
            </div>
          </div>
          @else
          <input type="hidden" value="{{$lead_data['is_active']}}" name="{{$formfield['is_active']}}">
          @endif
          <div class="additional-information-textarea">
            <div class="form-group">
              <label for="{{$formfield['additional_info']}}">Additional Information</label>
              <textarea class="form-control" name="{{$formfield['additional_info']}}" rows="6">{{$lead_data['additional_info']}}</textarea>
            </div>
          </div>
        </div>

        <div class="product-management-table">
          <h2>Product Management</h2>
          <div class="table-part">
            <table class="table">
              <thead>
                <tr>
                  <th data-toggle="true">Product Name</th>
                  <th data-hide="phone,tablet">Margin Value</th>
                  <th data-hide="phone,tablet">End Margin</th>
                  <!-- <th data-hide="phone,tablet">Proposed Value</th> -->                  
                  <th data-hide="phone,tablet">Anticipated Volume</th>
                  <th data-hide="phone" class="text-center">Gross Total</th>
                  <th data-hide="phone" class="text-center">Net Total</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody id="addproduct">
                @php
                $allprd=''
                @endphp

                @php
                $totval=0
                @endphp

                @if(!empty($leadproducts))
                @foreach($leadproducts as $key => $value)
                <tr id="prerow{{$existing_product[$key]['id']}}">
                  <td>{{$existing_product[$key]['prod_name']}}</td>
                  <td>{{$leadproducts[$key]['margin_value']}}<input type="hidden" name="{{$formfield['margin_value']}}[]" id="{{$formfield['margin_value']}}{{$existing_product[$key]['id']}}" value="{{$leadproducts[$key]['margin_value']}}"></td>
                  <td>{{$leadproducts[$key]['end_margin']}}<input type="hidden" name="{{$formfield['end_margin']}}[]" id="{{$formfield['end_margin']}}{{$existing_product[$key]['id']}}" value="{{$leadproducts[$key]['end_margin']}}"></td>
                  <td>
                    <input type="text" id="{{$formfield['quantity']}}{{$existing_product[$key]['id']}}" name="{{$formfield['quantity']}}[]"  class="form-control" min="1" onkeyup="changetotal('{{$leadproducts[$key]['margin_value']}}','{{$leadproducts[$key]['end_margin']}}','{{$formfield['quantity']}}','{{$existing_product[$key]['id']}}')" value="{{$leadproducts[$key]['quantity']}}">
                  </td>
                  <td class="text-center" id="grosstot{{$existing_product[$key]['id']}}">{{number_format($leadproducts[$key]['margin_value']*$leadproducts[$key]['quantity'],2)}}</td>
                  <td class="text-center" id="nettot{{$existing_product[$key]['id']}}">{{number_format($leadproducts[$key]['end_margin']*$leadproducts[$key]['quantity'],2)}}</td>
                  <td class="text-center viewgrp-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">
                      <li><a href="#" onclick="removepreproduct('{{$existing_product[$key]['id']}}')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                    </ul>
                  </td>
                </tr> 
                <?php
                  if($allprd!='')
                  {
                    $allprd=$allprd.','.$existing_product[$key]['id'];
                  }
                  else{
                    $allprd=','.$existing_product[$key]['id'];
                  }
                ?>

                @php
                $totval=$totval+($leadproducts[$key]['margin_value']*$leadproducts[$key]['quantity'])
                @endphp

                @endforeach
                @endif               
              </tbody>
              <tfoot>
                <tr class="add-new-row">
                  <td colspan="8">
                    <div class="viewgrp-dropdownblk">
                      <div class="viewgrp-dropdown after-dot">
                        <input class="magicsearch addprod" id="add-new-row" placeholder="Select Product">
                      </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="fetchproduct()">Add New Row</button>
                  </td>
                  
                </tr>
                <tr class="hidden-xs hidden-sm">
                  <td class="total" colspan="5">Total</td>
                  <td class="total-price totprice" id="" colspan="3">{{number_format($totval,2)}}</td>
                </tr>
              </tfoot>
            </table>
            <input type="hidden" id="existprod" name="{{$formfield['products']}}" value="{{$allprd}}">
            <div class="addlead-tatal hidden-md hidden-lg">
              <span class="total">Total</span>
              <span id="" class="total-price totprice">{{number_format($totval,2)}}</span>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group lead-button">
            <button type="submit" class="btn btn-primary"> Save </button>
            @if (Auth::user()->user_type == 'SP')
            <span class="leadsale" data-toggle="modal" data-target=".activity-details"> Upload Supporting Documents </span>
            <span class="leadsale marleft" data-toggle="modal" data-target=".converted-details"> Set To Converted </span>
            @endif
        </div>
        
        </form>
      </div>

      @if (Auth::user()->user_type == 'SP')
      <div class="modal activity-details fade activity-details-withfield" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="close-icon"></i>
                        </button>
                        <h4 class="modal-title">Supporting Documents</h4>
                    </div>

                    <div class="modal-body">
                        <form class="row" action="{{route('upload-support-doc')}}" id="upload_doc" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="abc-supplies text-center" >
                                <span class="supplies-number">L00{{$lead_data['id']}}</span>
                            </div>
                            <br>
                            <br>
                             
                            <input type="hidden" value="@if($lead_data['id']!=''){{ Crypt::encrypt($lead_data['id']) }} @endif" name="{{$formfield['id']}}">
                            
                            <div class="form-group col-sm-12">
                                <label>Upload File</label>
                                <input type="file" multiple="" class="form-control " id="{{$formfield['supportdoc']}}" name="{{$formfield['supportdoc']}}[]">
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary add-btn">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
      </div>
      @endif 
      <!-- New Modal -->
      @if (Auth::user()->user_type == 'SP')
      <div class="modal converted-details fade activity-details-withfield" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="close-icon"></i>
                    </button>
                    <h4 class="modal-title">Complete Lead</h4>
                </div>
                <div class="modal-body">
                    <form class="row" action="{{route('complete_lead')}}" id="upload_doc" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                         <input type="hidden" value="@if($lead_data['id']!=''){{ Crypt::encrypt($lead_data['id']) }} @endif" name="{{$formfield['id']}}">
                        <div class="form-group col-sm-12">
                            <h3 class="converted_title">Do you want to complete the lead and proceed To <span>Onboarding</span> Form</h3>
                        </div>
                        <div class="form-group col-lg-12">
                            <input type="submit" class="btn btn-primary add-btn" name="Yes" value="Yes">
                            <button type="button" class="btn btn-primary add-btn" data-dismiss="modal">No</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
      @endif 
    </section>
@endsection
@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<script type="text/javascript">
$(function() {
    var dataSource = [
    <?php 
        if(count($products)>0)
        {
          foreach ($products as $proddata) {
            ?>
            
            {id:"<?php echo $proddata['id']; ?>", name:"<?php echo $proddata['prod_name']; ?>"},
            <?php
          }
        }
        ?>
        
    ];
    
    $('#add-new-row').magicsearch({
        dataSource: dataSource,
        fields: ['name'],
        id: 'id',
        format: '%name%',
        dropdownBtn: true,
        focusShow: true
    });

        

});  
function fetchdetails(id)
{
    if(id==''){
      $("#contact-person-details").css('display','none');
    }
    else{
      $.post('<?php echo route('select-contact-details')?>', {
          'id': id,
          '_token': '<?php echo csrf_token();?>',
          }, function(data) {
            $("#contact-person-details").css('display','block');
            $("#contact-person-details").html(data);
         })
    }

}


function fetchproduct(){
  if($(".addprod").attr("data-id")!='')
  {
    existprod = $("#existprod").val();
    theArray = existprod.split(",");
    checking = false;
    for(i=0;i<theArray.length;i++)
    {
      if(theArray[i]==$(".addprod").attr("data-id"))
      {
        checking = true;
      }
    }
    if (checking==true) { 
     alert("This product is already exist"); 
    }
    else
    {
      if(existprod!='')
      {
        insertval = $("#existprod").val()+','+$(".addprod").attr("data-id");
      }
      else{
        insertval = ','+$(".addprod").attr("data-id");
      }
      existprod = $("#existprod").val(insertval);
      $.post('<?php echo route('product-details')?>', {
          'id': $(".addprod").attr("data-id"),
          'quantity' : "{{$formfield['quantity']}}",
          'end_margin' : "{{$formfield['end_margin']}}",
          'margin_value' : "{{$formfield['margin_value']}}",
          '_token': '<?php echo csrf_token();?>',
          }, function(data) {
            $("#addproduct").append(data);
            $('.table').trigger('footable_initialize')
         })
    }
  }
  else
  {
    alert("Please select product");
  }
}

function changetotal(margin,endmargin,quantity,id)
{  
  //alert(margin);alert(endmargin);alert(quantity);alert(id);
  var quantity = $("#"+quantity+id).val();
  pregrosstot = $("#grosstot"+id).html();
  prenettot = $("#nettot"+id).html();

  grosstot = margin * quantity;
  nettot = endmargin * quantity;
  totprice = $(".totprice").html();
  if(!isNaN(nettot) && nettot > 0)
  {
    $("#nettot"+id).html(nettot.toFixed(2));
  }
  if(!isNaN(grosstot) && grosstot > 0)
  {
    $("#grosstot"+id).html(grosstot.toFixed(2));
    if(Number(grosstot)=='' || Number(grosstot)==0)
    {
      newval = (Number(totprice) - Number(pregrosstot));
      $(".totprice").html(newval.toFixed(2));
    }
    else
    {
      newval = (Number(totprice) - Number(pregrosstot)) + Number(grosstot);
      $(".totprice").html(newval.toFixed(2));
    }
  }
  $('.table').trigger('footable_initialize');
}

function removeproduct(id)
{ 
  pregrosstot = $("#grosstot"+id).html();
  totprice = $(".totprice").html();
  newval = (Number(totprice) - Number(pregrosstot));
  $(".totprice").html(newval.toFixed(2));
  $("#row"+id).remove();  
  existprod = $("#existprod").val();
  if (existprod.indexOf(',') > -1) 
  {
    var rest = existprod.replace(','+id,'');
  }
  else{
    var rest = existprod.replace(id,'');
  }
  $("#existprod").val(rest);
  $('.table').trigger('footable_initialize');
}
function removepreproduct(id)
{  
  pregrosstot = $("#grosstot"+id).html();
  totprice = $(".totprice").html();
  newval = (Number(totprice) - Number(pregrosstot));
  $(".totprice").html(newval.toFixed(2));
  $("#prerow"+id).remove();  
  existprod = $("#existprod").val();
  if (existprod.indexOf(',') > -1) 
  {
    var rest = existprod.replace(','+id,'');
  }
  else{
    var rest = existprod.replace(id,'');
  }
  $("#existprod").val(rest);
  $('.table').trigger('footable_initialize');
}
/*$("#{{$formfield['supportdoc']}}").click(function(){
        alert('clicked');
        $("#{{$formfield['supportdoc']}}").attr('class','valid');
        $("#{{$formfield['supportdoc']}}-error").css('display','none');
      });*/
$(document).ready(function() {
     
      
      $("#leadadd").validate({
          ignore: '',
          rules: {
              "<?php echo $formfield['proposed_value']?>[]": {
                  required: true,
                  number: true
              },
              "<?php echo $formfield['quantity']?>[]": {
                  required: true,
                  number: true
              }, 
              {{$formfield['custom_id']}} : "required",           
              {{$formfield['customer_contact_person_id']}} : "required",          
              {{$formfield['sales_person_id']}} : "required",             
              {{$formfield['additional_info']}} : "required",          
              
          },
          messages: {
             "<?php echo $formfield['proposed_value']?>[]":
                {
                    required: "Please enter proposed value",
                    number: "Please enter only numeric value"
                },
              "<?php echo $formfield['quantity']?>[]":
                {
                    required: "Please enter anticipated volume",
                    number: "Please enter only numeric value"
                },   
              {{$formfield['custom_id']}} : "Please select customer",  
              {{$formfield['customer_contact_person_id']}} : "Please select contact person",        
              {{$formfield['sales_person_id']}} : "Please select sales person",            
              {{$formfield['additional_info']}} : "Please enter additional information",                          
          }
      });


      $("#upload_doc").validate({
              rules: {
                 
                 "<?php echo $formfield['supportdoc']?>[]": {
                      required: true,
                  },
               
               
              },
              messages: {
               
                 "<?php echo $formfield['supportdoc']?>[]": {
                      required: "Please seelct file",
                  },

              }
            });
      
  });


</script>
<script src="{{ asset('public/js/addlead.js') }}"></script>

@endsection
