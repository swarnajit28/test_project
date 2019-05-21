@extends('layouts.layout')
@section('title')
  <title></title>
@endsection
@section('css')
<link href="{{ asset('public/css/business-expense.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
      
      <div class="addcustomer-form addproduct-form">
       
       @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
       @endif
        @if (session('error_message'))
        <div class="alert alert-error alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('error_message') }}
        </div> 
        @endif
        <div class="outside_fund_hd">
          <form role="form" id="importForm" method="POST" action="{{route('upload_excel')}}" class="row product_select" enctype="multipart/form-data">
               {{ csrf_field() }} 
               <button type="submit" class="btn btn-primary add-btn">Upload</button>
              <!-- <input type="file" name="import_sheet"> -->
               <div class="custome-browse-button">
                <div class="fileUpload">
                  <input class="uploadFiletext uploadFile">
                  <span class="uploadBtn-wrap uploadBtn">
                    <span class="browse-button-txt1"></span>
                    <input type="file" name="uploadBtn" size="40" class="wpcf7-form-control wpcf7-file upload uploadBtn"  aria-invalid="false">
                  </span>
                </div>
               </div>
          </form>
          <button class="excel_donload_btn" type="button" onclick="window.location='{{route('download_excel') }}'"></button>
        </div>
      </div> 

<!--    <a href="{{route('download_excel')}}"> <span>Test Download</span></a>-->
    

      <div class="table-part wid-load-more">
        <table class="table business-expense-table-pt" id="loadtable">
          <thead>
            <tr>
              <th data-toggle="true">Id</th>
              <th> Client</th>
             <th data-hide="phone,tablet">agreed funding terms</th>
             <th data-hide="phone,tablet">current funding position</th>
             <th data-hide="phone,tablet">exposure to business</th>
             <th data-hide="phone,tablet">Sale Executive</th>
            </tr>
          </thead>
          <tbody id="payment_option"">
              @if(count($outside_funding) >0)
               @foreach($outside_funding as $data)
               <tr id="{{$data['id']}}">
                   <td data-hide="phone,tablet">{{$data['id']}}</td>
                    <td data-hide="phone,tablet">{{$data['client_name']}}</td>
                   <td data-hide="phone,tablet">{{$data['agreed_funding_terms']}}</td>
                   <td data-hide="phone,tablet">{{$data['current_funding_position']}}</td>
                   <td data-hide="phone,tablet">{{$data['exposure_to_business']}}</td>
                   <td data-hide="phone,tablet">{{$data['sale_executive']}}</td>
               </tr>
               @endforeach
              @else
              <tr><td colspan="6">No records found</td></tr>
              @endif
          </tbody>
        </table>

         
      </div>
    </section>

@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="{{ asset('public/js/leaddetails.js') }}"></script>
<script>
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
