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
            <div class="">
                <form action="{{route('lockExclusiveDays')}}" method="POST" id="customerForm">
                    {{ csrf_field() }}
                    <div class="field-first-grp">
                        <p>Number of days client exclusive for sale executive:</p>
                        <div class="input-field">
                            <input type="text" name="lock_days"  id="lock_days"  placeholder="Enter Targer Value" class="form-control" value="<?php echo isset($lock_days['customer_exclusive_lock_days']) ? $lock_days['customer_exclusive_lock_days'] : '0'; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit">Modify</button>
                    </div>
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
            lock_days: {
                required: true,
                number: true
            },
        },
        messages: {
           lock_days: {
                required: "Please Input Values",
            },
            
        }
    });
});
</script>
@endsection


