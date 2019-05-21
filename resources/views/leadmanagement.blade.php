@extends('layouts.layout')
@section('title')
  <title>Lead Management</title>
@endsection
@section('css')
<link href="{{ asset('public/css/leadmanagement.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="content content-custom">
      <div class="lead-manage-form">
        <div class="view-by">
          <form action="#" class="">
            <div class="field-second-grp">
              <div class="viewgrp-dropdownblk">
                <label>Select Customer</label>
                <div class="viewgrp-dropdown ui-drop">
                  <input placeholder="Test Company 1" id="select-customer" data-href="http://api.jqueryui.com/autocomplete/">
                  <!-- <input class="magicsearch" id="select-customer" placeholder="Test Company 1"> -->
                </div>
              </div>
            </div>
          </form>          
        </div>

        <div class="lead-person-details">
          <h3>Brayan Scott</h3>
          <p><a href="#"><i class="fa fa-globe"></i> brayan@gmail.com</a></p>
          <p><i class="fa fa-phone"></i> 07687687673</p>
        </div>

        <div class="document-block">
          <h4>Agreement Documents</h4>
          <ul>
            <li>Document 1 <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            <li>Document 2 <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            <li>Document 3 <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            <li>Documentnamecomeshere3 <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            

          </ul>
        </div>

        <div class="document-block">
          <h4>Contract Agreements</h4>
          <ul>
            <li>Document 1 <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            <li>Document 2 <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            <li>Document 3 <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
            <li>Documentnamecomeshere3 <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
          </ul>
        </div>
        <div class="product-management-table">
          <h2>Product Management</h2>
          <div class="table-part">
            <table class="table">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>Margin Value</th>
                  <th>End Margin</th>
                  <th>Proposed Value</th>
                  <th>Quantity</th>
                  <th class="text-center">Total</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Product 1</td>
                  <td>150</td>
                  <td>110</td>
                  <td>
                    <input type="text" name="proposed-value" class="form-control">
                  </td>
                  <td>
                    <input type="text" name="quantity" class="form-control">
                  </td>
                  <td class="text-center">110000</td>
                  <td class="text-center viewgrp-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">
                      <li><a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
                      <li><a href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Lead</a></li>
                      <li><a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <td>Product 1</td>
                  <td>150</td>
                  <td>110</td>
                  <td>
                    <input type="text" name="proposed-value" class="form-control">
                  </td>
                  <td>
                    <input type="text" name="quantity" class="form-control">
                  </td>
                  <td class="text-center">110000</td>
                  <td class="text-center viewgrp-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">
                      <li><a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a></li>
                      <li><a href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Lead</a></li>
                      <li><a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                    </ul>
                  </td>
                </tr>
                <tr class="add-new-row">
                  <td colspan="8">
                    <div class="viewgrp-dropdownblk">
                      <div class="viewgrp-dropdown">
                        <input class="magicsearch" id="add-new-row" placeholder="Select Product">
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add New Row</button>
                  </td>
                  
                </tr>
                
              </tbody>
              <tfoot>
                <tr>
                  <td class="total" colspan="5">Total</td>
                  <td class="total-price" colspan="3">220000</td>
                </tr>
              </tfoot>
            </table>
            
          </div>
        </div>
      </div>

</section>
@endsection

@section('script-section')
  <script src="{{ asset('public/js/leadmanagement.js') }}"></script>
@endsection
