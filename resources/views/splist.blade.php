@extends('layouts.layout')
@section('title')
  <title>Sales Persons</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('css')
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/managecustomer.css') }}" rel="stylesheet">

@endsection

@section('content')
    <!-- Main content -->
    <section class="content content-custom">
      <div class="view-by">
        <form id="search_user" action="{{route('sp-list')}}">
        <input type="hidden" name="id" id="hideid">
        <input type="hidden" value="{{$lastsp}}" id="lastsp" name="lastsp">
        {{ csrf_field() }}
          <div class="field-first-grp ">
            <p>Search By</p>            

            <div class="input-field">
              <input type="text" name="display_name" id="display_name" placeholder="Name" class="form-control" value="<?php if(isset($postArr['display_name'])){ echo $postArr['display_name'] ; } ?>">
            </div>
            <div class="input-field">
              <input type="text" name="username" id="username" placeholder="Email" class="form-control" value="<?php if(isset($postArr['username'])){ echo $postArr['username'] ; } ?>">
              
            </div>
          </div>
          <div class="field-second-grp">
            <div class="viewgrp-dropdownblk">
              <label>Status</label>
              <div class="viewgrp-dropdown">
                <div class="magicsearch-wrapper">
                  <select class="form-control" name="status" id="status">
                  <option value="">Select Status</option>
                    <option value="1" @if(isset($postArr['status']) && $postArr['status'] == '1'){{ 'selected' }} @endif>Active</option>
                    <option value="0" @if(isset($postArr['status']) && $postArr['status'] == '0'){{ 'selected' }} @endif>Inactive</option>
                  </select>
                </div>
                <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
              </div>
            </div>
           
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
          </div>
        </form>        
      </div>      
      <div class="table-part wid-load-more">
        <table class="table" id="loadtable">
          <thead>
            <tr>
              <th data-toggle="true">Name</th>
              <th data-hide="phone,tablet">Email</th>
              <th data-hide="phone,tablet" class="text-center" data-sorter="false">Status</th>
              <th class="text-center" data-sorter="false">Action</th>
            </tr>
          </thead>
          <tbody id="load-data">
          @if(count($user_list)>0)
          @foreach($user_list as $list)
          <?php $cnt = count($user_list); ?>
            <tr id="{{$list->id}}">
              <td>{{$list->display_name}}</td>
              <td>{{$list->username}}</td>
              
          <td class="text-center" id="stat{{$list->id}}">@if($list->is_active == '1')
              <button class="tick" onclick="change_user_status('{{$list->is_active}}','{{$list->id}}');"><i class="fa fa-check" aria-hidden="true" title="active"></i>@else <i class="fa fa-times" aria-hidden="true" title="Inactive" onclick="change_user_status('{{$list->is_active}}','{{$list->id}}');"></button></i> @endif</td>
              <td class="text-center ">
                <a class="table-link" href="{{ route('list-sp-lead',['id' => Crypt::encrypt($list['id']) ]) }}"><i class="fa fa-eye"  aria-hidden="true"></i> View Leads</a>
              </td>
            </tr>
            @endforeach
            @else
            <tr class="ndf">
              <td colspan="5" align="center"> No records found </td>
            </tr>
            @endif

          </tbody>
        </table>
         @if(isset($postArr['perPage'])) 
          <div id="remove-row">
              <div class="load-more">
                  <button id="btn-more" data-id="{{(isset($list->id)) ? $list->id : '' }}" > @if(count($user_list) > 0) Load More @else No More Data @endif</button>
                  <!-- <a href="#">Load More</a> -->
              </div>
          </div>
        @endif 
        <input type="hidden" id="perPage" value="{{(isset($postArr['perPage']) ? $postArr['perPage'] : '')}}">
      </div>
    </section>
    <!-- /.content -->

@endsection


@section('script-section')
<script src="{{ asset('public/js/tableSort.js') }}"></script>
<script type="text/javascript">
    
   $(document).ready(function() 
    { 
        $("#loadtable").tablesorter(); 
    } 
    )  

  $(document).ready(function(){
  $('#hideid').val($('#loadtable tr:last').attr('id'));
   $(document).on('click','#btn-more',function(){
      var id = $(this).data('id');
     // alert(id);
      //$("#hideid").val($(this).data('id'));
      var perPage = $("#perPage").val();
    
       $("#btn-more").html("Loading....");
       $.ajax({
           url : '{{route("loadsplist")}}',
           method : "POST",
           //data : {id:id, _token:"{{csrf_token()}}"},
           data : $('#search_user').serialize(),
           dataType : "text",
           success : function (data)
           {
              if(data != '') 
                {
                    $('#load-data').append(data);
                    id= id-perPage;
                    $('#btn-more').data('id', id);
                    //$("#btn-more").data(id);
                    if($("#lastsp").val()!='')
                    {
                      lastid = $('#loadtable tr:last').attr('id');
                      if(lastid == $("#lastsp").val())
                      {
                        $('#btn-more').data('id', '0');
                        $('#btn-more').html("No More Data");
                        $('#hideid').val(0);
                      }
                      else{
                        $("#btn-more").html("Load More");
                        $('#hideid').val($('#loadtable tr:last').attr('id'));
                      }
                    }
                    else{
                       $("#btn-more").html("Load More");
                       $('#hideid').val($('#loadtable tr:last').attr('id'));
                    }

                    $(function(){
                      $("#loadtable").tablesorter();
                      $("#loadtable").trigger("update"); 
                      $('.table').trigger('footable_initialize');
                    });
                }
                else
                {
                    $('#btn-more').html("No More Data");
                }
           }
       });
   });  
}); 

  function change_user_status(status,id)
  {
    if(confirm('Are you sure you want to change customer status?'))
      {
        if(status==0)
        {
          status = 1;
        }
        else if(status==1)
        {
          status = 0;
        }
        $.post('<?php echo route('spstatuschange')?>', {
          'id': id,
          'status': status,
          '_token': '<?php echo csrf_token();?>',
          }, function(data) {
             $("#stat"+id).html(data);
             $('.table').trigger('footable_initialize');
         })
      }
      return false;
  }
</script>
  <script src="{{ asset('public/js/managecustomer.js') }}"></script>
@endsection