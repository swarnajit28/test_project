@extends('layouts.layout')
@section('title')
  <title>Bug List</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/leaddetails.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/business-expense.css') }}" rel="stylesheet">

@endsection

@section('content')
    <!-- Main content -->
    <section class="content content-custom">
           @if (session('success_message'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ Session::get('success_message') }}
    </div> 
    @endif
       <div class="view-by business-expense-report-list">
        <form id="search_user" action="">
           <input type="hidden" name="search" value="search">  
           
        {{ csrf_field() }}
          <div class="field-first-grp ">
 
            <div class="viewgrp-dropdownblk business-expense-dt">
              <label>From Date</label>
              <div class="datepicker-block business-expense-dt-fst">
                <input class="form-control datepicker event_start_date"  name="fromdate" placeholder="From" value="{{$postArr['fromdate']}}">
              </div>
              <div class="datepicker-block datepicker-block-to">
                <input class="form-control datepicker event_end_date"  name="todate" placeholder="To" value="{{$postArr['todate']}}">
              </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>

          </div>

        </form>        
      </div>     
        
        <div class="table-part wid-load-more  doc_store">
          <table class="table " id="loadtable">
            <thead>
              <tr>
                <th data-toggle="true">Doc Id</th>
                <th>Document name</th>
                <th data-hide="phone,tablet">Document Type</th>
                @if (Auth::user()->user_type=='IT')
                <th data-hide="phone,tablet">Document For</th>
                @endif
                <th data-hide="phone,tablet">Document</th>
                <th data-hide="phone,tablet">Posted On</th>
              </tr>
            </thead>
            <tbody id="document-store-table">
                 @if(count($all_data) >0)
                 @foreach($all_data as $data)
                 <tr id="{{$data['id']}}">
                    <td data-hide="phone,tablet">DOC_{{$data['id']}}</td>
                    <td data-hide="phone,tablet">{{$data['document_name']}}</td>
                    <td data-hide="phone,tablet">{{$data['doc_type']}}</td>
                    @if (Auth::user()->user_type=='IT')
                    <td data-hide="phone,tablet">{{$data['user_type']}}</td>
                    @endif
                    <td data-hide="phone,tablet">
                        @if($data['ext']== 'png'||$data['ext']== 'jpeg'||$data['ext']=='jpg')
                    	<a href="{{asset('public/uploads/doc_store/')}}/{{$data['uploaded_file_name']}}" target="_blank" title="Image"><i class="fa fa-file-image-o" aria-hidden="true"></i></a>
                    	 @elseif ($data['ext']== 'pdf')
                        <a href="{{asset('public/uploads/doc_store/')}}/{{$data['uploaded_file_name']}}" target="_blank" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                         @elseif ($data['ext']== 'csv'||$data['ext']=='xlsx')
                        <a href="{{asset('public/uploads/doc_store/')}}/{{$data['uploaded_file_name']}}" target="_blank" title="Reports"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                          @elseif ($data['ext']== 'zip'||$data['ext']=='rar')
                    	<a href="{{asset('public/uploads/doc_store/')}}/{{$data['uploaded_file_name']}}" target="_blank" title="Archive"><i class="fa fa-file-archive-o" aria-hidden="true"></i></a>
                    	@else
                        <a href="{{asset('public/uploads/doc_store/')}}/{{$data['uploaded_file_name']}}" target="_blank" title="Document"><i class="fa fa-cubes" aria-hidden="true"></i></a>
                        @endif
                    </td>   
                    <td data-hide="phone,tablet">{{date('d/m/Y',strtotime($data['created_on']))}}</td>
                 </tr>
              @endforeach
                @else
                <tr><td colspan="6">No records found</td></tr>
                @endif
            </tbody>
          </table>
            @if($search=='no')
          <div id="remove-row">
            <div class="load-more">
                <button id="btn-more"> @if(count($all_data) > 0) Load More @else No More Data @endif</button>
            </div>
          </div>
            @endif
        </div>
    </section>
    
    <!-- /.content -->

@endsection


@section('script-section')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{ asset('public/js/tableSort.js') }}"></script>
<script src="{{ asset('public/js/leaddetails.js') }}"></script>
<script type="text/javascript">

  $(document).ready(function(){
  
   $(document).on('click','#btn-more',function(){
      var id = $('#loadtable tr:last').attr('id');
      //alert(id);
      if (typeof(id) != "undefined")
      {
       $("#btn-more").html("Loading....");
       $.ajax({
           url : '{{route("loadDocumentStore")}}',
           method : "POST",
           //data : {id:id, _token:"{{csrf_token()}}"},
           data : {'id':id, '_token': '<?php echo csrf_token(); ?>'},
           dataType : "text",
           success : function (data)
           {
              if(data != '') 
                {
                    $('#document-store-table').append(data);
                    $('#btn-more').html("Load More");
                }   
                else
                {
                    $('#btn-more').html("No More Data");
                }
           }
       });
       }
   }); 

});
</script>
  
@endsection