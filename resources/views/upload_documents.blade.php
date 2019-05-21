@extends('layouts.layout')
@section('title')
<title>Product Category</title>
@endsection
@section('css')
<link href="{{ asset('public/css/managecustomer.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
    <div class="manage-product-form lead_activity_mode_page">

        <div class="view-by">
            @if (session('success_message'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('success_message') }}
            </div> 
            @endif
            <div class="documentsupload-form">
                <form action="{{route('DocumentsUpload')}}" method="POST" id="customerForm" class=""  enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="company_name">Name</label>
                            <input type="text" name="{{$form_data['doc_name']}}" id="" value="" class="form-control">
<!--                             @if ($errors->has('doc_name'))
                              <span class="error">{{$errors->first('doc_name')}}</span>   
                           @endif-->
                            <p class="perr error" id="pNameError"></p>
                        </div><!-- ./form-group./col-sm-6 -->
                        <div class="form-group col-xs-6">
                            <!-- <label class="blank-space">&nbsp;</label> -->
                            <label for="">Document Type</label>
                            <div class="viewgrp-dropdownblk">
                              <label class="select_label">Type</label>
                              <div class="viewgrp-dropdown">
                                <div class="magicsearch-wrapper">
                                    <select class="form-control valid" name="{{$form_data['doc_type']}}" id="" aria-invalid="false">
                                      <option value="0">Select</option>
                                      <option value="1">Sales Literature</option>
                                      <option value="2">Monthly/Quarterly Reports</option>
                                      <option value="3">Archive Report</option>
                                      <option value="4">Historic Sales</option>
                                    </select>
                                  </div>
                              </div>
                            </div>
                        </div><!-- ./form-group./col-sm-6 -->
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="">Upload file</label>
                            <div class="custome-browse-button">
                                <div class="fileUpload">
                                    <input class="uploadFiletext uploadFile" readonly="">
                                  <span class="uploadBtn-wrap uploadBtn">
                                    <span class="browse-button-txt1"></span>
                                    <input type="file" name="{{$form_data['file_name']}}" size="40" class="wpcf7-form-control wpcf7-file upload uploadBtn"  aria-invalid="false">
                                  </span>
                                </div>
                            </div>
                            <p class="perr error" id="pNameError"></p>
                        </div><!-- ./form-group./col-sm-6 -->
                        <div class="form-group col-xs-6">
                            <label for="">Document for</label>
                            <div class="viewgrp-dropdownblk">
                                <label class="select_label">For</label>
                                <div class="viewgrp-dropdown">
                                    <div class="magicsearch-wrapper">
                                        <select class="form-control valid" name="{{$form_data['doc_for']}}" id="" aria-invalid="false">
                                            <option value="0">Select</option>
                                            @if(count($all_user_type) >0)
                                            @foreach($all_user_type as $data)
                                            <option value="{{$data['id']}}">{{$data['user_type']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div><!-- ./form-group./col-sm-6 -->
                    </div>
                <button type="submit" class="btn btn-primary add-btn">Save</button>
                </form>
            </div>
        </div>
    </div>

</section>
@endsection

@section('script-section')

<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="{{ asset('public/js/managecustomer.js') }}"></script>
<script type="text/javascript">
     $(document).ready(function() {
        $("#customerForm").validate({
            rules: {
                {{ $form_data['doc_name'] }}: {
                    required: true
                },
                {{ $form_data['doc_type'] }}: {
                    required: true
                },
                {{ $form_data['file_name'] }}: {
                    required: true
                },
                {{ $form_data['doc_for'] }}: {
                    required: true
                },
              },
              messages: {
                {{ $form_data['doc_name'] }}: {
                    required: "Please enter document name"
                },
                {{ $form_data['doc_type'] }}: {
                    required: "Please select a value"
                },
                {{ $form_data['file_name'] }}: {
                    required: "Please select file"
                },
                {{ $form_data['doc_for'] }}: {
                    required: "Please select a value"
                },
              }
        });
      });


    function getFileData(myFile){
    //console.log(myFile.value);
     var file = myFile.files?myFile.files[0] : myFile.value;
     if(file.name){
      var filename = file.name; 
     }else{
      var fils = file.split("\\");
      var filename = fils[fils.length-1];
     }
     
     return filename;
    }
   $('.upload').on('change', function(){
        $(this).parent().parent().find(".uploadFiletext").val(getFileData(this));
   });

</script>
@endsection


