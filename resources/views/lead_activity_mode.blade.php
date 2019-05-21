@extends('layouts.layout')
@section('title')
  <title>Manage Lead Activity Mode</title>
@endsection
@section('css')
<link href="{{ asset('public/css/manageproduct.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/tableSort.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
      <div class="manage-product-form lead_activity_mode_page">
      
      <div class="view-by">
        <?php

       if(session()->has('status'))
       {
        $data = session('status');
        //print_r($data);
        $activity_mode        = $data['activity_mode'];
        $mode_status        = $data['mode_status'];
       
       }else
       {
        $loan_type        = '';
        $mode_status        = '';
       }

       ?> 
       @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('success_message') }}
        </div> 
       @endif
          <form action="{{route('add_lead_activity_mode')}}" method="POST" id="addleadactivitymode">
           {{ csrf_field() }}
            
            <div class="field-first-grp">
            <p>Name:</p>
              <div class="input-field">
                <input type="text" name="{{ $encrypted['activity_mode'] }}"  id="{{ $encrypted['activity_mode'] }}"  placeholder="Name" class="form-control" value="<?php echo isset($loan_details->activity_mode)?$loan_details->activity_mode:''; ?>">
                @if(isset($encrypted['activity_mode_id']))
                <input type="hidden" name="{{ $encrypted['activity_mode_id'] }}" class="form-control" value="<?php echo isset($loan_details->id)?$loan_details->id:''; ?>"> 
                @endif
                @if ($errors->has('activity_mode'))
                  <span class="error">{{ $errors->first('activity_mode') }}</span>
                @endif
              </div>
               
              <div class="viewgrp-dropdownblk">
                <label>Status</label>
                <div class="viewgrp-dropdown">
                  <div class="magicsearch-wrapper">
                    <select class="form-control" name="{{ $encrypted['mode_status'] }}">
                      <option value="1" <?php if(isset($loan_details->is_active) && ($loan_details->is_active == '1')){ echo 'selected' ; }?>>Active</option>
                      <option value="0" <?php if(isset($loan_details->is_active) && ($loan_details->is_active == '0')){ echo 'selected' ; }?>>Inactive</option>
                    </select>
                  </div>
                  <!-- <input class="magicsearch" id="status-drop" placeholder="All"> -->
                </div>
              </div>
            @if ( Request::segment(1)=='edit_lead_activity_mode')
              <button type="submit" class="btn btn-primary" onclick="validateForm()" id="submit">Edit</button>
           @else
           <button type="submit" class="btn btn-primary" onclick="validateForm()" id="submit">Add</button>
           @endif
            </div>
          </form>
          
        </div>
        
        <div class="table-part wid-load-more">
          <input type="hidden" value="{{$lastmode}}" id="lastmode" name="lastmode">
          <table class="table tablesorter" id="loadtable">
            <thead>
              <tr>
                <th data-toggle="true">Name</th>
                <th data-hide="phone,tablet" class="text-center" data-sorter="false">Status</th>
                <th class="text-center" data-sorter="false">Action</th>
              </tr>
            </thead>
            <tbody id="lead_activity_mode">
            <input type="hidden" name="id" id="hideid">
            @if(count($loan)>0)
            @foreach($loan as $data)
                <tr id="{{$data['id']}}">
                <td data-hide="phone,tablet">{{ $data['activity_mode'] }}</td>
                <td data-hide="phone,tablet" class="text-center" id="stat{{$data['id']}}">@if($data['is_active'] == '1')<i class="fa fa-check" aria-hidden="true" title="Active" style="cursor: pointer;" onclick="change_activity_status('{{$data['is_active']}}','{{$data['id']}}');"></i>@else <i class="fa fa-times" aria-hidden="true" title="Inactive" style="cursor: pointer;" onclick="change_activity_status('{{$data['is_active']}}','{{$data['id']}}');"></i> @endif
                </td>
              <td class="text-center viewgrp-dropdown dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{route('edit_lead_activity_mode',['id' => Crypt::encrypt($data['id'])])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
<!--                    <li><a href="javascript:void(0)" onclick="delete_activity_mode('{{ $data['id'] }}')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>-->
                  </ul>
               <!--   <a href="{{route('edit_master_loan_view',['id' => Crypt::encrypt($data['id'])])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           
                  <a href="javascript:void(0)" onclick="delete_loan('{{ $data['id'] }}')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>  -->
                </td>
              </tr>
              @endforeach
             @else
             <tr class="ndf"><td colspan="3" align="center">No records found</td></tr>
             @endif
            </tbody>
          </table>
       @if(isset($page)) 
          <div id="remove-row">
              <div class="load-more">
                  <button id="btn-more" data-id="{{(isset($data['id'])) ? $data['id'] : '' }}" > @if(count($loan) > 0) Load More @else No More Data @endif</button>
                  <!-- <a href="#">Load More</a> -->
              </div>
          </div>
        @endif
        <input type="hidden" id="perPage" value="{{(isset($page) ? $page : '')}}">
        </div>
      </div>

    </section>
@endsection

@section('script-section')

<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="{{ asset('public/js/tableSort.js') }}"></script>

<script type="text/javascript">
  $(function(){
    $("#loadtable").tablesorter({headers: {2: {sorter: false}}});
  });
</script> 
<script type="text/javascript">
      $(document).ready(function() {
        $("#addleadactivitymode").validate({
            rules: {
                {{ $encrypted['activity_mode'] }}: {
                    required: true
                },
              },
              messages: {
                {{ $encrypted['activity_mode'] }}: {
                required: "Please enter activity name"
                },
              }
        });
      });

      function delete_activity_mode(id)
      {
       // alert(id);
        if (confirm('Are you sure you want to delete this?')) 
        {
          $.ajax({
              url: "{{ route('delete_activity_mode') }}",
              type: "POST",
              data: {'id':id,'_token': '<?php echo csrf_token();?>'},  
              success: function (response) {
                  if(response>0)
                  {
                   document.location.href= "lead_activity_mode";
                   // redirect('/master_loan');
                  }
              }
          }); 
         }
      } 
      
  </script>

  <script type="text/javascript">

  $(document).ready(function(){
  $('#hideid').val($('#loadtable tr:last').attr('id'));
   $(document).on('click','#btn-more',function(){
      var id = $(this).data('id');

      var perPage = $("#perPage").val();
    
       $("#btn-more").html("Loading....");
       $.ajax({
           url : '{{route("loadLeadActivityMode")}}',
           method : "POST",
           //data : {id:id, _token:"{{csrf_token()}}"},
           data : {'id':id, '_token': '<?php echo csrf_token();?>'},
           dataType : "text",
           success : function (data)
           {
            //alert(response);
              /*if(response != '') 
              {
                  $('#lead_activity_mode').append(response); 
                  id= $('#activity_mode_id').val();
                  $("#hideid").val($(this).data('id'));
                  $('#btn-more').data('id', id);
                  $("#btn-more").html("Load More");
                    $(function(){
                      $("#loadtable").tablesorter();
                      $("#loadtable").trigger("update"); 
                    });
              }
              else
              {
                  $('#btn-more').html("No More Data");
              }*/

              if(data != '') 
                {
                    $('#lead_activity_mode').append(data);
                    id= id-perPage;
                    $('#btn-more').data('id', id);
                    //$("#btn-more").data(id);
                    if($("#lastmode").val()!='')
                    {
                      lastid = $('#loadtable tr:last').attr('id');
                      //alert(lastid);
                      if(lastid == $("#lastmode").val())
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

function change_activity_status(status,id)
  {
    if(confirm('Are you sure you want to change mode status?'))
      {
        if(status==0)
        {
          status = 1;
        }
        else if(status==1)
        {
          status = 0;
        }
        $.post('<?php echo route('activity-mode-stat-change')?>', {
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
 <script src="{{ asset('public/js/addcustomer.js') }}"></script>
@endsection
