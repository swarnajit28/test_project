@extends('layouts.layout')
@section('title')
  <title>@if($lead_data['id']!='')Edit @else Add @endif Lead</title>
@endsection
@section('css')
<link href="{{ asset('public/css/addlead.css') }}" rel="stylesheet">

@endsection

@section('content')
<section class="content content-custom">
      <div class="lead-manage-form addlead-page">
        
          @if (session('success_message'))
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('success_message') }}
          </div> 
          @endif
          <form role="form" id="leadadd" method="POST" action="{{route('submit-lead')}}" class="" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" value="@if($lead_data['id']!=''){{ Crypt::encrypt($lead_data['id']) }} @endif" name="{{$formfield['id']}}">
        <div class="view-by @if (Auth::user()->user_type != 'SP') addlead-management-field @endif">
          

        <div class="field-first-grp">
          @if (Auth::user()->user_type == 'SP')
          <input type="hidden" value="{{Auth::user()->id}}" name="{{$formfield['sales_person_id']}}">
          @else
          <div class="viewgrp-dropdownblk">
            <label>Select Sales Person</label>
            <div class="viewgrp-dropdown dropdown">            
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="{{$formfield['sales_person_id']}}" id="{{$formfield['sales_person_id']}}" onchange="selectcustomer(this.value);">
                    <option value="">Select</option>
                    @if(count($salesperson)>0)
                    @foreach($salesperson as $saleperson)
                    <option value="{{$saleperson['id']}}" @if (Session::has('sp_id') && (Session::get('sp_id')==$saleperson['id'])) selected=""  @endif>{{$saleperson['display_name']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
            </div>            
          </div>
          @endif
          <!-- <input type="hidden" name="{{$formfield['strength']}}" value="0"> -->
          <!-- <div class="viewgrp-dropdownblk  @if (Auth::user()->user_type != 'SP') select-contact @endif"> -->
            <div class="viewgrp-dropdownblk select-contact">
            <label>Select Lead Type</label>
            <div class="viewgrp-dropdown dropdown">            
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="{{$formfield['strength']}}">
                    <option value="0">New</option>
                    @if(count($strengths)>0)
                    @foreach($strengths as $strength)
                    @if($strength['id']!=0)
                    <option value="{{$strength['id']}}">{{$strength['loan_type']}}</option>
                    @endif
                    @endforeach
                    @endif
                  </select>
                </div>
            </div>
          </div>
        </div>

        <div class="field-second-grp">
          <div class="viewgrp-dropdownblk">
            <label>Select Customer</label>
            <div class="viewgrp-dropdown ui-drop">
              <input placeholder="Select" name="{{$formfield['custom_id']}}" id="select-customer" @if (Session::has('customer')) value="{!! session('customer') !!}" @endif>  
            </div>
            <input type="hidden" name="{{$formfield['customer_id']}}" id="{{$formfield['customer_id']}}"  @if (Session::has('customer_id')) value="{!! session('customer_id') !!}" @endif>
          </div>
          <div class="viewgrp-dropdownblk select-contact">
            <label>Select Contact</label>
            <div class="viewgrp-dropdown dropdown">            
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="{{$formfield['customer_contact_person_id']}}" onchange="fetchdetails(this.value);" id="contact_persons">
                    <option value="">Select</option>
                    @if (Session::has('contact_person'))
                    @foreach (session('contact_person') as $contacts)             
                     <option value="{{$contacts['id']}}">{{$contacts['contact_person_name']}}</option>
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
              <label for="{{$formfield['lead_source']}}"> Lead source</label>
              <input type="text" name="{{$formfield['lead_source']}}" id="" value="" class="form-control">
              <p class="perr error" id="pNameError"></p>
          </div>

        <div id="contact-person-details" style="display: none;"  >
          
        </div>  
        <div id="attachdoc-details" style="display:  @if (Session::has('customer_attachments')) block; @else none; @endif" >
          @if (Session::has('customer_attachments'))
          {!! session('customer_attachments') !!} 
          @endif      
        </div>
        <div class="field-second-grp">
          
          <div class="additional-information-textarea">
            <div class="form-group">
              <label for="{{$formfield['additional_info']}}">Additional Information</label>
              <textarea class="form-control" name="{{$formfield['additional_info']}}" rows="6"></textarea>
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
                  <td class="total-price totprice" id="" colspan="3">0</td>
                </tr>
              </tfoot>
            </table>
            <input type="hidden" id="existprod" name="{{$formfield['products']}}" value="">
            <div class="addlead-tatal hidden-md hidden-lg">
              <span class="total">Total</span>
              <span id="" class="total-price totprice">0</span>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group lead-button">
            <button type="submit" class="btn btn-primary"> Save </button>
        </div>
        </form>
      </div>

    </section>
@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
@if (Auth::user()->user_type == 'SP')
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

    
    // autocomplete

    

    var availableTags = [
        <?php 
        if (Session::has('customerlist'))
        {
        foreach (session('customerlist') as $custom) 
        {
            ?>
            
            {label:"<?php echo $custom['company_name']; ?>", data:"<?php echo $custom['id']; ?>"},
            <?php
          }
        }
        else if(count($customers)>0)
        {
          foreach ($customers as $custdata) {
            ?>
            
            {label:"<?php echo $custdata['company_name']; ?>", data:"<?php echo $custdata['id']; ?>"},
            <?php
          }
        }
        ?>
        ];
      
   
    $("#select-customer").autocomplete({
        source: availableTags,
        minLength: 0,
        appendTo: ".ui-drop",
        position: { my: "left top", at: "left bottom", of: ".ui-drop" },
        
        search:function(ev, ui){
          var $this = $(this);
          var $nxt = $(ev.target).next();
          var $data = $this.data("href");
          setTimeout(function(){
            if(!$nxt.is(":visible")){
              $nxt.children().remove().promise().done(function(){
                $("#ui-id-1").html("");
                $("#ui-id-1").css('display','block');
                $("#ui-id-1").append("<a class='add-product-link' href='<?php echo route('add-lead-customer')?>'>Add Customer</a>");
                $nxt.show();
              })
            }
          },100)
          
        },
        select: function( event, ui ) {
          // console.log(ui);
          // console.log(ui.item.data);
        $("#contact-person-details").css('display','none');
          $.post('<?php echo route('select-contact')?>', {
          'id': ui.item.data,
          '_token': '<?php echo csrf_token();?>',
          }, function(data) {
            $("#{{$formfield['customer_id']}}").val(ui.item.data);
            $("#contact_persons").html(data);
         })

          $.post('<?php echo route('select-customer-attachment')?>', {
            'id': ui.item.data,
            '_token': '<?php echo csrf_token();?>',
            }, function(data) {
              $("#attachdoc-details").css('display','block');
              $("#attachdoc-details").html(data);
           })

        }

    }).focus(function () {

        $(this).autocomplete("search");
    });

});  
</script>
@else
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

      
     var source = [
    <?php 
    if (Session::has('customerlist'))
    {
        foreach (session('customerlist') as $custom) 
        {
            ?>
            
            {label:"<?php echo $custom['company_name']; ?>", data:"<?php echo $custom['id']; ?>"},
            <?php
          }
        }
        ?>
        
    ];   
   
    $("#select-customer").autocomplete({
        source: source,
        minLength: 0,
        appendTo: ".ui-drop",
        position: { my: "left top", at: "left bottom", of: ".ui-drop" },
        
        search:function(ev, ui){
          var $this = $(this);
          var $nxt = $(ev.target).next();
          var $data = $this.data("href");
          setTimeout(function(){
            if(!$nxt.is(":visible")){
              $nxt.children().remove().promise().done(function(){
                $("#ui-id-1").html("");
                $("#ui-id-1").css('display','block');
                <?php 
                  if (Session::has('customerlist'))
                  {
                    ?>                    
                    $("#ui-id-1").append("<a class='add-product-link' href='javascript:void(0)' onclick='addleadcustomer({{Session::get('sp_id')}})'>Add Customer</a>");
                    <?php
                  }
                ?>
                $nxt.show();
              })
            }
          },100)
          
        },
        select: function( event, ui ) {
          // console.log(ui);
          // console.log(ui.item.data);
        $("#contact-person-details").css('display','none');
          $.post('<?php echo route('select-contact')?>', {
          'id': ui.item.data,
          '_token': '<?php echo csrf_token();?>',
          }, function(data) {
            $("#{{$formfield['customer_id']}}").val(ui.item.data);
            $("#contact_persons").html(data);
         })

          $.post('<?php echo route('select-customer-attachment')?>', {
            'id': ui.item.data,
            '_token': '<?php echo csrf_token();?>',
            }, function(data) {
              $("#attachdoc-details").css('display','block');
              $("#attachdoc-details").html(data);
           })

        }

    }).focus(function () {

        $(this).autocomplete("search");
    });
    


});  
</script>
@endif
<script type="text/javascript">
function selectcustomer(id){
  if(id!='')
  {
    $.post('<?php echo route('select-sp-customers')?>', {
      'id': id,
      '_token': '<?php echo csrf_token();?>',
      }, function(response) {

        data = $.parseJSON(response);
        if (data.length == 0) {
           $("#{{$formfield['customer_id']}}").val('');
            $("#select-customer").val('');
            $("#contact_persons").html('');
            $("#attachdoc-details").css('display','none');
            $("#contact-person-details").css('display','none');
            


            $("#select-customer").autocomplete({
                source: '',
                minLength: 0,
                appendTo: ".ui-drop",
                position: { my: "left top", at: "left bottom", of: ".ui-drop" },
                
                search:function(ev, ui){
                  var $this = $(this);
                  var $nxt = $(ev.target).next();
                  var $data = $this.data("href");
                  setTimeout(function(){
                    if(!$nxt.is(":visible")){
                      $nxt.children().remove().promise().done(function(){
                        $("#ui-id-1").html("");
                        $("#ui-id-1").css('display','block');
                        $("#ui-id-1").html("<a class='add-product-link' href='javascript:void(0)' onclick='addleadcustomer("+id+")'>Add Customer</a>");
                        $nxt.show();
                      })
                    }
                  },1000)
                  
                }

            }).focus(function () {

                $(this).autocomplete("search");
            });
        }

        else {
            $("#select-customer").val('');
            $("#contact_persons").html('');
            $("#attachdoc-details").css('display','none');
            $("#contact-person-details").css('display','none');
        var newresponse = [];
        for(i=0;i<data.length;i++){
          newresponse.push(
              {label:data[i].label, data:data[i].data}
          );
        }
        $("#select-customer").autocomplete({
            source: newresponse,
            minLength: 0,
            appendTo: ".ui-drop",
            position: { my: "left top", at: "left bottom", of: ".ui-drop" },
            
            search:function(ev, ui){
              var $this = $(this);
              var $nxt = $(ev.target).next();
              var $data = $this.data("href");
              setTimeout(function(){
                if(!$nxt.is(":visible")){
                  $nxt.children().remove().promise().done(function(){

                    $("#ui-id-1").html("");
                    $("#ui-id-1").css('display','block');
                    $("#ui-id-1").append("<a class='add-product-link' href='javascript:void(0)' onclick='addleadcustomer("+id+")'>Add Customer</a>");
                    $nxt.show();
                  })
                }
              },100)
              
            },
            select: function( event, ui ) {
              // console.log(ui);
              // console.log(ui.item.data);
            $("#contact-person-details").css('display','none');
              $.post('<?php echo route('select-contact')?>', {
              'id': ui.item.data,
              '_token': '<?php echo csrf_token();?>',
              }, function(data) {
                $("#{{$formfield['customer_id']}}").val(ui.item.data);
                $("#contact_persons").html(data);
             })

              $.post('<?php echo route('select-customer-attachment')?>', {
                'id': ui.item.data,
                '_token': '<?php echo csrf_token();?>',
                }, function(data) {
                  $("#attachdoc-details").css('display','block');
                  $("#attachdoc-details").html(data);
               })

            }

        }).focus(function () {

            $(this).autocomplete("search");
        });
        }



     })

    
  }
  else if(id==''){


    $("#{{$formfield['customer_id']}}").val('');
    $("#select-customer").val('');
    $("#contact_persons").html('');
    $("#attachdoc-details").css('display','none');
    $("#contact-person-details").css('display','none');


    $("#select-customer").autocomplete({
        source: '',
        minLength: 0,
        appendTo: ".ui-drop",
        position: { my: "left top", at: "left bottom", of: ".ui-drop" },
        
        search:function(ev, ui){
          var $this = $(this);
          var $nxt = $(ev.target).next();
          var $data = $this.data("href");
          setTimeout(function(){
            if(!$nxt.is(":visible")){
              $nxt.children().remove().promise().done(function(){
                $("#ui-id-1").html("");
                $("#ui-id-1").css('display','block');
                $nxt.show();
              })
            }
          },100)
          
        },
        select: function( event, ui ) {
          // console.log(ui);
          // console.log(ui.item.data);
        $("#contact-person-details").css('display','none');
          $.post('<?php echo route('select-contact')?>', {
          'id': ui.item.data,
          '_token': '<?php echo csrf_token();?>',
          }, function(data) {
            $("#{{$formfield['customer_id']}}").val(ui.item.data);
            $("#contact_persons").html(data);
         })

          $.post('<?php echo route('select-customer-attachment')?>', {
            'id': ui.item.data,
            '_token': '<?php echo csrf_token();?>',
            }, function(data) {
              $("#attachdoc-details").css('display','block');
              $("#attachdoc-details").html(data);
           })

        }

    }).focus(function () {

        $(this).autocomplete("search");
    });
  }
}
function addleadcustomer(id)
{
  $.post('<?php echo route('crypt-id')?>', {
      'id': id,
      '_token': '<?php echo csrf_token();?>',
      }, function(data) {
        document.location=data;  
     })
  
}
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

function isInArray(value, array) {
  return array.indexOf(value) > -1;
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

    /*if(theArray!='')
    {*/
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
            $('.table').trigger('footable_initialize');
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
  });
</script>
<script src="{{ asset('public/js/addlead.js') }}"></script>
@endsection
