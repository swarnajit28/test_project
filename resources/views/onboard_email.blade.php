@extends('layouts.layout')
@section('title')
<title>On Board Email</title>
@endsection
@section('css')
<link href="{{ asset('public/css/addproduct.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">

    <div class="addcustomer-form addproduct-form mandetails">

        @if (session('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('success_message') }}
        </div> 
        @endif

        <form role="form" id="customerForm" method="POST" action="{{route('submit_onBoardEmail')}}" class="row">
             {{ csrf_field() }}
            
            <div class="personchecking">
                <div class="container">
                    <div class="row">
                        @foreach($allOM_list as $value)
                        <div class="col-lg-4 col-md-4 col-sm-4 customarchecking">
                            <div class="boxone">
                                <div class="areawhite">
                                    <div class="checkarea"><input type="checkbox" name="list_om[]" value="{{$value['id'] }}" @if($value['checked']=='yes')checked @endif></div>
                                    <div class="detailsname">
                                        <h5>{{$value['display_name'] }}</h5>
                                        <p>{{$value['email'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>
 
            <div class="clearfix"></div>

            <div class="form-group col-lg-12 submitsec">
                <button type="submit" class="btn btn-primary add-btn">Add Email</button>
            </div>
        </form>
    </div>      

</section>

@endsection

@section('script-section')
<script src="{{ asset('public/js/validate.js') }}"></script>
<script src="{{ asset('public/js/addproduct.js') }}"></script>

@endsection
