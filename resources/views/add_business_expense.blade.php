@extends('layouts.layout')
@section('title')
  <title>List Of Leads</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/business-expense.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">

@endsection

@section('content')
    <!-- Main content -->
    <section class="content content-custom">
        <div class="lead-manage-form">
            <form role="form" id="addexpenseform" method="POST" action="{{route('submitBusinessExpense')}}" class="" enctype="multipart/form-data">
          {{ csrf_field() }}
            <div class="business-expense-page">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text"  name="{{$formfield['company_name']}}" class="form-control" value="Simplify Business Ltd" readonly="readonly">
                        </div>
                        <div class="form-group">
                           
                                <label> Sales Executive </label>
           
                         
                                        <select class="form-control" name="{{$formfield['sales_person_id']}}" id="{{$formfield['sales_person_id']}}" onchange="selectcustomer(this.value);">
                                            <option value="">Select</option>
                                            @if(count($salesperson)>0)
                                            @foreach($salesperson as $saleperson)
                                            @if ((Auth::user()->user_type=='SP') && (Auth::user()->id==$saleperson['id']))
                                            <option value="{{$saleperson['id']}}" >{{$saleperson['display_name']}}</option>
                                            @elseif((Auth::user()->user_type=='MA'))
                                            <option value="{{$saleperson['id']}}" >{{$saleperson['display_name']}}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
      
                               

                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Return To</label>
                            <input type="text"  name="{{$formfield['return_to']}}" class="form-control">
                        </div>
                       <div class="form-group">
                            <label>Reporting Period</label>
                            <input type="text"  name="{{$formfield['reporting_period']}}" class="form-control" value="{{$month}}/{{$year}}" readonly="readonly">
                        </div>
                    </div>
                </div>
                 
                <div class="product-management-table">
                    <h2>Standard Expenses( Cash/Personal CC/DC and Centtrip)</h2>
                    <div class="table-part">
                        <table class="table standard-expense-table breakpoint" id="sta_exp">
                            <thead>
                                <tr>
                                    <th data-toggle="true">Recpt N0.</th>
                                    <th data-hide="phone,tablet">date of exp</th>
                                    <th data-hide="phone,tablet">Detail</th>
                                    <th data-hide="phone,tablet">Payment method</th>
                                    <th data-hide="phone,tablet">Client Name</th>
                                    <th data-hide="phone,tablet">Client Contact</th>
                                    <th data-hide="phone,tablet">VAT  APPL</th>
                                    <th data-hide="phone" class="text-center">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="addexpense">
                                
                                <tr id="1">
                                    <td>1</td>
                                    <td>
                                        <div class="form-group">
                                            <div class="datepicker-block">
                                                <input type="text" name="{{$formfield['st_ex_date']}}[]" placeholder="Select Date" class="form-control datepicker-expence">
                                            </div>
                                        </div>
                                    </td>
                                   <td>
                                        <div class="form-group">
                                            <select class="form-control" name="{{$formfield['business_expense']}}[]">
                                                <option value="">Select</option>
                                                 @if(count($expense_type)>0)
                                                @foreach($expense_type as $et)
                                                <option value="{{$et['id']}}" >{{$et['expense_type']}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="{{$formfield['payment_option']}}[]" onchange="calculation()">
                                                <option value="">Select</option>
                                                 @if(count($payment_method)>0)
                                                @foreach($payment_method as $pm)
                                                <option value="{{$pm['id']}}||{{$pm['is_reimbursable']}}">{{$pm['payment_option']}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="form-group" >
                                            <select class="form-control contact_persons" id="contact_persons" name="{{$formfield['contact_person']}}[]">
                                                <option value="">Select</option>
                                               
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="{{$formfield['client_contact']}}[]" class="form-control">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control"  name="{{$formfield['vat']}}[]">
                                           <option value="1">Yes</option>
                                           <option value="0">No</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="{{$formfield['total']}}[]"  class="form-control decnum" onkeyup="calculation()">
                                        </div>
                                    </td>
                                    <td class="text-center viewgrp-dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onclick="removeespense(1)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                                        </ul>
                                    </td>
                                </tr>
                                
                            </tbody>
                          
                            <tr id='' class="add-new-row">
                                    <td colspan="9">
                                       <button type="button" class="btn btn-primary" onclick="addnewexpense()">Add New Row</button>
                                    </td>                    
                                </tr>
                           
                        </table>
                        
                    </div>
                </div>
               

                <div class="product-management-table">
                      <h2>Mileage Report</h2>
                      <br>
                      <br>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Total Mileage at the Start of the Reporting Period</label>
                                <input type="text" name="{{$formfield['start_milage']}}" id="start_milage" class="form-control" value="9000">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Starting mileage + accumulative Miles In Period</label>
                                <input type="text" name="{{$formfield['end_milage']}}" id="end_milage" class="form-control">
                            </div>
                        </div>
                    </div>
                  
                    <div class="table-part">
                        <table class="table mileage-report-table breakpoint" id="mileage_report">
                            <thead>
                                <tr>
                                    <th data-toggle="true">Recpt N0.</th>
                                    <th data-hide="phone,tablet">date of exp</th>
                                    <th data-hide="phone,tablet">Location Postal  Code - From / To  </th>
                                    <th data-hide="phone,tablet">Miles Covered</th>
                                    <th data-hide="phone,tablet">@ Rate (AUTO)</th>
                                    <th data-hide="phone,tablet">Client Name</th>
                                    <th data-hide="phone" class="text-center">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="addmileage">
                                <tr id="1">
                                    <td>1</td>
                                    <td>
                                        <div class="form-group">
                                            <div class="datepicker-block">
                                                <input type="text" name="{{$formfield['mileage_date']}}[]" placeholder="Select Date" class="form-control datepicker-expence">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="{{$formfield['location']}}[]" class="form-control">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="{{$formfield['total_mileage']}}[]" class="form-control decnum" onkeyup="rateCalculation()">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="{{$formfield['rate']}}[]" class="form-control" readonly="readonly" value="0.45">                        
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group" >
                                            <select class="form-control contact_persons1" id="contact_persons1" name="{{$formfield['contact_person1']}}[]">
                                                <option value="">Select</option>
                                               
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="{{$formfield['total_price']}}[]" class="form-control" readonly="readonly">                        
                                        </div>
                                    </td>
                                    <td class="text-center viewgrp-dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onclick="removemilage(1)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                            <tr class="add-new-row">
                                    <td colspan="9">
                                        <button type="button" class="btn btn-primary" onclick="addnewmileage()">Add New Row</button>
                                    </td>                    
                                </tr>
                           
                        </table>
                        <!-- <div class="addlead-tatal hidden-md hidden-lg">
                          <span class="total">Total</span>
                          <span class="total-price">220000</span>
                        </div> -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 cost-expenses-part">
                        <h2>Costs to Reimburse</h2>
                        <div class="cost-expenses-block">
                            <div class="form-group">
                                <label>Fuel to Reimburse</label>
                                <input type="text" name="{{$formfield['fuel_reimburse']}}" id="fuel_reimburse" placeholder=" £" class="form-control" value="0" readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Cash & Personal Debit/Credit Card payments to Reimburse</label>
                                <input type="text" name="{{$formfield['total_cash_reimbursement']}}" placeholder=" £ " class="form-control" id="total_cash_reimbursement" value="0" readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Total to Reimburse</label>
                                <input type="text"  placeholder=" £" name="{{$formfield['all_total_reimburse']}}" id="all_total_reimburse" class="form-control" value="0" readonly="readonly">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 cost-expenses-part">
                        <h2>Expenses Summary</h2>
                        <div class="cost-expenses-block">
                            <div class="form-group">
                                <label>NET Expenses In Period</label>
                                <input type="text"  placeholder=" £" name="{{$formfield['net_expense']}}" id="net_expense" class="form-control" readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Vat which can be reclaimed by Simplify @ 20% :   </label>
                                <input type="text"  placeholder=" £ " name="{{$formfield['vat_reclaimed']}}" id="vat_reclaimed" class="form-control" readonly="readonly">
                                <span class="note-value">note.  There is currently no consideration for reclaiming VAT @ £25 per 1000 miles within this calculation. </span>
                            </div>
                            <div class="form-group">
                                <label>Gross Expenses In Period </label>
                                <input type="text"  placeholder=" £ " name="{{$formfield['gross_expense']}}" id="gross_expense" class="form-control" readonly="readonly">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="acknowledgement-section">
                    <h2>Acknowledgement</h2>
                    <p>I certify that the above is a true statement, that the expenses claimed were incurred by me on official Simplify Business Ltd business and that the original receipts will be made available upon request.   </p>
                    <div class="acknowledgement-part">
                        <h3>Bank Deatails of Claimant</h3>

                        <div class="acknowledgement-blk clearfix">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Sort Code</label>
                                    <input type="text" name="{{$formfield['sort_code']}}" placeholder="00-00-00" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Account Number</label>
                                    <input type="text" name="{{$formfield['account_number']}}" placeholder="0000000000" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="acknowledgement-blk clearfix">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Sale person</label>
                                        <input type="text" name="{{$formfield['acknowledged_by']}}" class="form-control">
                                    </div>
                                    <div class="form-group signature-blk">
                                        <label>Signature</label>
                                        <div class="form_upload">

                                            <!-- <div class="raddo">
                                                <div class="summary_check1 sign_rad">
                                                    <input type="radio" value="1"  name="{{$formfield['sign_type'] }}" onchange="signType('name')"  id="{{ $formfield['sign_type'] }}1" checked="">
                                                    <label class="texPadn" for="{{ $formfield['sign_type'] }}1">Name</label>

                                                    <input type="radio" value="2"  name="{{ $formfield['sign_type'] }}" onchange="signType('signPad')" id="{{ $formfield['sign_type'] }}2">
                                                    <label class="texPadn" for="{{ $formfield['sign_type'] }}2">Signature Pad</label>

                                                    <input type="radio" value="3"  name="{{ $formfield['sign_type'] }}" onchange="signType('file')" id="{{ $formfield['sign_type'] }}3">
                                                    <label class="texPadn" for="{{ $formfield['sign_type'] }}3">File Upload</label>
                                                </div>
                                            </div> -->

                                            <div class="ckeck-option clearfix">
                                              <div class="check-field">
                                                <input type="radio" value="1" id="c1" name="{{$formfield['sign_type'] }}" onchange="signType('name')" checked="">
                                                <label for="c1"><span></span>Name</label>
                                              </div>

                                              <div class="check-field">
                                                <input type="radio" value="2" id="c2" name="{{ $formfield['sign_type'] }}" onchange="signType('signPad')">
                                                <label for="c2"><span></span>Signature Pad</label>
                                              </div>

                                              <div class="check-field">
                                                <input type="radio" value="3" id="c3" name="{{ $formfield['sign_type'] }}" onchange="signType('file')">
                                                <label for="c3"><span></span>File Upload</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label>Name</label>
                                        
                                        <div id="name">
                                            <input type="text" class="form-control name"  name="{{ $formfield['signed_file'] }}">
                                        </div>
                                        
                                        
                                        <div class="sign_box" id="file">
                                            <div class="name-field-sig-file-upload">
                                              <input type="file" name="{{ $formfield['upload_file'] }}" id="{{ $formfield['upload_file'] }}" class="inputfile inputfile-6 file-business" data-multiple-caption="{count} files selected"  accept=".jpeg,.jpg,.png" />                   
                                            </div>   
                                            <div class="allowedsec">[allowed types jpeg, jpg, png]</div>
                                        </div>
                                        
<!--                                        <div class="sign_box" id="file">
                                            <input type="file" name="{{ $formfield['signed_file'] }}" id="{{ $formfield['signed_file'] }}" class="inputfile inputfile-6 file" data-multiple-caption="{count} files selected"  accept=".jpeg,.jpg,.png" />
                                            <div class="allowedsec">[allowed types jpeg, jpg, png]</div><br>
                                        </div>-->
                                        
                                        <div id="signPad">
                                            <div id="signPad1">
                                                <input type="hidden" class="imgOutput signPad form-control" name="{{ $formfield['signature_file'] }}" id="signP">
                                                <canvas id="signature-pad" class="canvbox" onclick="image_convert()"></canvas>
                                                <br>
                                                <button type="button" id="clear-signature">Clear</button>
                                            </div>
                                            <div id="signPad2">

                                                <div class="sigimg">
                                                    <img style="height: 100px; width: 200px; border: 1.5px solid #ddd;" src="{{asset('public/images/blank_sign.png')}}">

                                                </div>
                                                <div class="signChange">
                                                    <button type="button"  onclick="signType('change')">Change</button>
                                                </div>
                                            </div>
                                        </div>
                                        
        
                                        
                                        </div>     
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="datepicker-block">
                                            <input type="text" name="{{ $formfield['acknowledged_on'] }}" placeholder="Select Date" class="form-control datepicker-expence">
                                        </div>
                                    </div>
                                </div> 
                                </div>
<!--                                @if ((Auth::user()->user_type=='MA'))
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Approved by</label>
                                        <input type="text" name="" class="form-control">
                                    </div>
                                    <div class="form-group signature-blk">
                                        <label>Signature</label>
                                        <div class="ckeck-option clearfix">
                                            <div class="check-field">
                                                <input type="checkbox" id="c4" name="">
                                                <label for="c4"><span></span>Name</label>
                                            </div>
                                            <div class="check-field">
                                                <input type="checkbox" id="c5" name="">
                                                <label for="c5"><span></span>Signature Pad</label>
                                            </div>
                                            <div class="check-field">
                                                <input type="checkbox" id="c6" name="">
                                                <label for="c6"><span></span>File Upload</label>
                                            </div>
                                        </div>
                                        <div class="name-field-sig-file-upload">
                                            <div class="signature-pad"></div>
                                             <input type="file" class="form-control" id="custom-input-file">                    
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="datepicker-block">
                                            <input type="text" name="" placeholder="Select Date" class="form-control datepicker-expence">
                                        </div>
                                    </div>
                                </div> 
                                @endif-->
                            </div>                  
                        </div>

                    </div>
                </div>
                  @if($is_approved=='0')
                <div class="business-expense-btn">
                    <div class="form-group">
                        <input type="hidden" name="" value="contact">
                        <a href="{{route('list_business_expense')}}" class="btn btn-primary reset-btn">Cancel</a>
                        <button type="submit" class="btn btn-primary add-btn">ADD</button>
                    </div>
                </div>
                 @endif 
            </div>
                </form>
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('script-section')  
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="{{ asset('public/js/business-expense.js') }}"></script>  
  <script src="{{ asset('public/js/validate.js') }}"></script>
  <script src="{{ asset('public/js/tableSort.js') }}"></script>
  <script src="{{ asset('public/js/signature_pad.js') }}"></script>

<script type="text/javascript">
$( document ).ready(function() {
  var maxD = new Date();  
  var d = new Date();
  //console.log(d);
  var minD= new Date(d.setMonth(d.getMonth() - 1));
  //console.log(kk);
    $( ".datepicker-expence" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        numberOfMonths: 2,
        //minDate: new Date(2019, 0, 1),
        minDate: minD,
//        maxDate: '+1M',
        maxDate: maxD,
    });

});
    
$(document).ready(function() {
     
      
      $("#addexpenseform").validate({
          ignore: '',
          rules: {
//              "<?php echo $formfield['st_ex_date']?>[]": {
//                  required: true,
//              },
//              "<?php echo $formfield['business_expense']?>[]": {
//                  required: true,
//              }, 
//               
//              "<?php echo $formfield['payment_option']?>[]": {
//                  required: true,
//              }, 
//              "<?php echo $formfield['contact_person']?>[]": {
//                  required: true,
//              }, 
//               "<?php echo $formfield['client_contact']?>[]": {
//                  required: true,
//              },
//              "<?php echo $formfield['vat']?>[]": {
//                  required: true,
//              },
//              "<?php echo $formfield['total']?>[]": {
//                  required: true,
//                  number: true,
//              },
//                      
//              "<?php echo $formfield['mileage_date']?>[]": {
//                  required: true,
//              }, 
//               "<?php echo $formfield['location']?>[]": {
//                  required: true,
//              },
//              "<?php echo $formfield['total_mileage']?>[]": {
//                  required: true,
//                  number: true,
//              },
//              "<?php echo $formfield['contact_person1']?>[]": {
//                  required: true, 
 //             },                  
              {{$formfield['custom_id']}} : "required",      
              {{$formfield['sales_person_id']}} : "required",
              {{$formfield['return_to']}} : "required", 
              {{$formfield['reporting_period']}} : "required",  
              {{$formfield['sort_code']}} : "required",
              {{$formfield['account_number']}} : "required",
              {{$formfield['acknowledged_by']}} : "required",
              {{$formfield['signed_file']}} : "required",
              {{$formfield['acknowledged_on']}} : "required",
//              {{$formfield['upload_file']}} : "required",
              {{$formfield['signature_file']}} : "required",
          },
          messages: {
//             "<?php echo $formfield['st_ex_date']?>[]":
//                {
//                    required: "Please enter date",
//                },
//              "<?php echo $formfield['business_expense']?>[]":
//                {
//                    required: "Please select",
//                },
//               
//               "<?php echo $formfield['payment_option']?>[]":
//                {
//                    required: "Please select",
//                }, 
//                 "<?php echo $formfield['contact_person']?>[]":
//                {
//                    required: "Please select",
//                },  
//                 "<?php echo $formfield['client_contact']?>[]":
//                {
//                    required: "Please input value",
//                },
//                "<?php echo $formfield['vat']?>[]":
//                {
//                    required: "Please select",
//                },
//                "<?php echo $formfield['total']?>[]":
//                {
//                    required: "Please input value",
//                    number: "Only numeric "
//                }, 
//                "<?php echo $formfield['mileage_date']?>[]":
//                {
//                    required: "Please enter date",
//                },        
//                "<?php echo $formfield['location']?>[]":
//                {
//                    required: "Please enter location",
//                },        
//                "<?php echo $formfield['total_mileage']?>[]":
//                {
//                    required: "Please enter total mileage",
//                },        
//                "<?php echo $formfield['contact_person1']?>[]":
//                {
//                    required: "Please select contact person",
//                }, 

              {{$formfield['custom_id']}} : "Please select customer",          
              {{$formfield['sales_person_id']}} : "Please select sales person",
              {{$formfield['sort_code']}} : "Please input sort code", 
              {{$formfield['account_number']}} : "Please input account no", 
              {{$formfield['acknowledged_by']}} : "Input acknowledged_by", 
              {{$formfield['signed_file']}} : "Input your signed", 
              {{$formfield['acknowledged_on']}} : "Input Date",
//              {{$formfield['upload_file']}} : "Upload File",
              {{$formfield['signature_file']}} : "Please sign in sinature pad",
          }
      });
  });
      
    
function selectcustomer(id){
  if(id!='')
  {
    $.post('<?php echo route('fetch_sp_customer')?>', {
      'id': id,
      '_token': '<?php echo csrf_token();?>',
      }, function(data) {
     $(".contact_persons").html(data); 
     $(".contact_persons1").html(data);
    //console.log(data);
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

function addnewexpense()
{
   var html= $("#contact_persons").html();
   var sta_exp_id= $('#sta_exp tr').eq(-2).attr('id');
   var new_sta_exp_id=Number(sta_exp_id) + 1;
   //alert(sta_exp_id);
   //alert(html);
    var data='<tr id="'+new_sta_exp_id+'">\n\
                                    <td>'+new_sta_exp_id+'</td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <div class="datepicker-block">\n\
                                                <input type="text" name="{{$formfield["st_ex_date"]}}[]" placeholder="Select Date" class="form-control datepicker-expence">\n\
                                            </div>\n\
                                        </div>\n\
                                    </td>\n\
                                   <td>\n\
                                        <div class="form-group">\n\
                                            <select class="form-control" name="{{$formfield["business_expense"]}}[]">\n\
                                                <option value="">Select</option>'@if(count($expense_type)>0)@foreach($expense_type as $et)+'<option value="{{$et["id"]}}" >{{$et["expense_type"]}}</option>'@endforeach @endif+
                                           
                                            '</select>\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <select class="form-control" name="{{$formfield["payment_option"]}}[]" onchange="calculation()">\n\
                                                <option value="">Select</option>'@if(count($payment_method)>0)@foreach($payment_method as $pm)+'<option value="{{$pm["id"]}}||{{$pm["is_reimbursable"]}}" >{{$pm["payment_option"]}}</option>'@endforeach @endif+
                                           
                                            '</select>\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <select class="form-control contact_persons" id="contact_persons" name="{{$formfield["contact_person"]}}[]">'
                                                +html+
                                            '</select>\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <input type="text" name="{{$formfield["client_contact"]}}[]" class="form-control">\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <select class="form-control"  name="{{$formfield["vat"]}}[]">\n\
                                           <option value="1">Yes</option>\n\
                                           <option value="0">No</option>\n\
                                            </select>\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <input type="text" name="{{$formfield["total"]}}[]" class="form-control decnum" onkeyup="calculation()">\n\
                                        </div>\n\
                                    </td>\n\
                                    <td class="text-center viewgrp-dropdown">\n\
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>\n\
                                        <ul class="dropdown-menu">\n\
                                            <li><a href="#" onclick="removeespense('+new_sta_exp_id+')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>\n\
                                        </ul>\n\
                                    </td>\n\
                                </tr>'
    $("#addexpense").append(data);
    
  var d = new Date();
  var maxD = new Date();  
  var minD= new Date(d.setMonth(d.getMonth() - 1));
    $( ".datepicker-expence" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        numberOfMonths: 2,
        minDate: minD,
        maxDate: maxD,
    });
    
    $(document).ready(function(){
    $('.decnum').keypress(function(event) {
      if ((event.which != 46 || $(this).val().indexOf('.') != -1) && ((event.which < 48 && event.which != 8) || event.which > 57)) {
        //  alert('Hi');
        event.preventDefault();
      }
    });
  });
    //alert(data);
}

function removeespense(id)
{
    var id=id;
    $('#sta_exp').find('#'+id).remove(); 
    calculation();
    Expenses_summary();
//    $("#"+id).remove(); 
}

function removemilage(id)
{
    var id=id;
    $('#mileage_report').find('#'+id).remove(); 
    rateCalculation();
    Expenses_summary();
}



  function addnewmileage()
    {
       var html= $("#contact_persons").html();
       var mileage_id= $('#mileage_report tr').eq(-2).attr('id');
       var new_mileage_id=Number(mileage_id) + 1;
       var data1='<tr id="'+new_mileage_id+'">\n\
                                    <td>'+new_mileage_id+'</td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <div class="datepicker-block">\n\
                                                <input type="text" name="{{$formfield["mileage_date"]}}[]" placeholder="Select Date" class="form-control datepicker-expence">\n\
                                            </div>\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <input type="text" name="{{$formfield["location"]}}[]" class="form-control">\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <input type="text" name="{{$formfield["total_mileage"]}}[]" class="form-control" onkeyup="rateCalculation()">\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group">\n\
                                            <input type="text" name="{{$formfield["rate"]}}[]" class="form-control" readonly="readonly">\n\
                                        </div>\n\
                                    </td>\n\
                                    <td>\n\
                                        <div class="form-group" >\n\
                                            <select class="form-control contact_persons1" id="contact_persons1" name="{{$formfield["contact_person1"]}}[]">'
                                                +html+
                                            '</select>\n\
                                        </div>\n\
                                    </td>\n\
                                     <td>\n\
                                        <div class="form-group">\n\
                                            <input type="text" name="{{$formfield["total_price"]}}[]" class="form-control" readonly="readonly">\n\
                                        </div>\n\
                                    </td>\n\
                                    <td class="text-center viewgrp-dropdown">\n\
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>\n\
                                        <ul class="dropdown-menu">\n\
                                            <li><a href="#" onclick="removemilage('+new_mileage_id+')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>\n\
                                        </ul>\n\
                                    </td>\n\
                                </tr>'
    
     $("#addmileage").append(data1);
     var maxD = new Date();  
     var d = new Date();
     var minD= new Date(d.setMonth(d.getMonth() - 1));
      $( ".datepicker-expence" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy', 
        numberOfMonths: 2,
        minDate: minD,
//        maxDate: '+1M',
        maxDate: maxD,
    });
}

  $(document).ready(function(){
    $('.decnum').keypress(function(event) {
      if ((event.which != 46 || $(this).val().indexOf('.') != -1) && ((event.which < 48 && event.which != 8) || event.which > 57)) {
        //  alert('Hi');
        event.preventDefault();
      }
    });
  });
  
 function calculation()
    {
        var total_reimburse_cash=0;
       //var id= $('#{{$formfield["total"]}}').val();
       $('input[name="{{$formfield["total"]}}[]"]').each(function(index){
       //console.log($(this).val());
        //console.log('index'+index);
        payment_new_text = $(this).closest('tr').children('td:eq(3)').find('select[name="{{$formfield["payment_option"]}}[]"] option:selected').text();
        payment_new_val = $(this).closest('tr').children('td:eq(3)').find('select[name="{{$formfield["payment_option"]}}[]"] option:selected').val();
        //console.log(payment_new_val);
        var split_select_value = payment_new_val.split('||')[1];
        var select_value=Number(split_select_value);
        
        if(select_value!=0)
        {
            total_reimburse_cash=Number(total_reimburse_cash)+Number($(this).val());
        }
       });
       $('#total_cash_reimbursement').val(total_reimburse_cash);
      var fuel_reimburse= $('#fuel_reimburse').val();
      var all_total_reimburse=Number(fuel_reimburse)+Number(total_reimburse_cash);
      $('#all_total_reimburse').val(all_total_reimburse);
      Expenses_summary();
    }
    
 function rateCalculation()
 {  
     var total_milage=Number($('#start_milage').val());
     //var milage=Number(val);
     var total;
     var total_amt=0;
   $('input[name="{{$formfield["rate"]}}[]"]').each(function(index){
     var milage = $(this).closest('tr').children('td:eq(3)').find('input[name="{{$formfield["total_mileage"]}}[]"]').val();   
     total_milage=total_milage+Number(milage);
     //alert(total_milage);
     if(total_milage>10000)
     {
        $(this).closest('tr').children('td:eq(4)').find('input[name="{{$formfield["rate"]}}[]"]').val('0.25');
        total=Number(milage)*0.25
        $(this).closest('tr').children('td:eq(6)').find('input[name="{{$formfield["total_price"]}}[]"]').val(total);
        //alert(milage);
     } 
     else
     {
        $(this).closest('tr').children('td:eq(4)').find('input[name="{{$formfield["rate"]}}[]"]').val('0.45');
        total=Number(milage)*0.45
        $(this).closest('tr').children('td:eq(6)').find('input[name="{{$formfield["total_price"]}}[]"]').val(total);
     }  
     total_amt=total_amt+total;
  });
  //alert(total_amt);
  $('#fuel_reimburse').val(total_amt);
  var cash=$('#total_cash_reimbursement').val();
  var all_total_reimburse=Number(total_amt)+Number(cash);
  $('#all_total_reimburse').val(all_total_reimburse);
  $('#end_milage').val(total_milage);
  Expenses_summary();
 }
 
 
 function Expenses_summary()
 { 
     var total_standard_expense=0;
     var standard_expense=0; 
     var milage_expense=0;
     var total_milage_expense=0;
      $('input[name="{{$formfield["total"]}}[]"]').each(function(index){
      standard_expense= $(this).closest('tr').children('td:eq(7)').find('input[name="{{$formfield["total"]}}[]"]').val();  
      total_standard_expense=total_standard_expense+Number(standard_expense);
    });
    $('input[name="{{$formfield["rate"]}}[]"]').each(function(index){
     milage_expense= $(this).closest('tr').children('td:eq(6)').find('input[name="{{$formfield["total_price"]}}[]"]').val();  
      total_milage_expense=total_milage_expense+Number(milage_expense);
    });
    
    var grand_total=total_standard_expense+total_milage_expense;
    var vat_reclaimed= (total_standard_expense/120)*20;
    var net_expense= grand_total-vat_reclaimed;
    var vat_reclaimed = vat_reclaimed.toFixed(2);
    var net_expense = net_expense.toFixed(2);
    $('#gross_expense').val(grand_total);
    $('#vat_reclaimed').val(vat_reclaimed);
    $('#net_expense').val(net_expense);
    //alert(grand_total); 
 }
 

  $(document).ready(function(){
       $('#file').hide();
       $('#signPad').hide();
       $(".signPad").prop('disabled', true);
       
//            $('#signPad').hide();

//        var signType = "<?php echo $formfield['sign_type'] ?>";
//          alert(signType);
//          if(signType==0 || signType=='')
//          {
//            $('#file').hide();
//            $('#signPad').hide();
//            $('#name').hide();
//          }
//          if(signType==1)
//          {
//            $('#file').hide();
//            $('#signPad').hide();
//          }
//          if(signType==2)
//          {
//            $('#file').hide();
//            $('#name').hide();
//            $('#signPad1').hide();
//            $('#signPad2').show();
//          }
//          if(signType==3)
//          {
//            $('#signPad').hide();
//            $('#name').hide();
//          }
//          $('[data-toggle="tooltip"]').tooltip();   
          
          
          $( "#<?php echo $formfield['sign_date'] ?>" ).datepicker({
              dateFormat: 'dd/mm/yy',
              minDate: -30,
              maxDate: "+1M"
          });

          

      });
    
      var canvas = document.getElementById("signature-pad");
          var signaturePad = new SignaturePad(canvas);
          $('#clear-signature').click(function(){
              signaturePad.clear();
          });   
    
    function signType(type)
      {
          //alert(type);
        if(type=="change")
        {
          $('#name').hide();
          $('#file').hide();
          $(".name").prop('disabled', true);
          $(".file").prop('disabled', true);
          $(".name").val("");
          $(".file").val("");
          $('.signPad').removeAttr('disabled');
          $('#signPad1').show();
          $('#signPad2').hide();


          $(".name").removeClass('borderClass');
          $(".file").removeClass('borderClass');
        }
        if(type=="name")
        {
          $('#file').hide();
          $('#signPad').hide();
          $(".name").prop('disabled', true);
          $(".signPad").prop('disabled', true);
          $(".name").val("");
          $(".signPad").val("");
          var canvas = document.getElementById("signature-pad");
          var signaturePad = new SignaturePad(canvas);
          signaturePad.clear();
          $('.name').removeAttr('disabled');
          $('#name').show();

          $(".file").removeClass('borderClass');
          $('#signPad').removeClass('borderClass');
        }
        if(type=="signPad")
        {
          $('#name').hide();
          $('#file').hide();
          $(".name").prop('disabled', true);
          $(".file").prop('disabled', true);
          $(".name").val("");
          $(".file").val("");
          $('.signPad').removeAttr('disabled');
          $('#signPad').show();
          $('#signPad2').hide();
      
          $(".name").removeClass('borderClass');
          $(".file").removeClass('borderClass');
        }
        if(type=="file")
        {
          $('#name').hide();
          $('#signPad').hide();
          $(".name").prop('disabled', true);
          $(".signPad").prop('disabled', true);
          $(".name").val("");
          $(".signPad").val("");
          var canvas = document.getElementById("signature-pad");
          var signaturePad = new SignaturePad(canvas);
          signaturePad.clear();
          $('.file').removeAttr('disabled');
          $('#file').show();

      
          $(".name").removeClass('borderClass');
          $('#signPad').removeClass('borderClass');
        }
      }  
      
      function clearForm()
      { console.log("chk");
        
      }

      $('.file-business').simpleFileInput({
        placeholder : 'Attach file',
        buttonText : 'Select',
        allowedExts : ['png', 'jpg','jpeg']
    });
  
  function image_convert()
  {
       $('.imgOutput').val('');
      $('.imgOutput').val(canvas.toDataURL());
  }  
</script>
@endsection
