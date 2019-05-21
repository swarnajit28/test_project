<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" name="viewport">
  @yield('title')
  @include('layouts.favicon')
  @yield('css') 
  <link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  @include('layouts.sidebar')  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header hidden-lg">
      @if (\Request::is('ListAllproduct'))  
        <h1 class="header-title">List of All Product</h1>
        @elseif (\Request::is('user-type-dashboard'))  
        <h1 class="header-title">Dashboard</h1>
        @elseif (\Request::is('manage_customer'))  
        <h1 class="header-title">List of Customer</h1>
        @elseif (\Request::is('list_lead'))  
        <h1 class="header-title">List of Lead</h1>
        @elseif (\Request::is('searchlead'))  
        <h1 class="header-title">List of Lead</h1>
        @elseif (\Request::is('add_customer'))  
        <h1 class="header-title">Add Customer</h1>
        @elseif (\Request::is('add_lead_customer'))  
        <h1 class="header-title">Add Customer</h1>
        @elseif (\Request::is('add_product'))  
        <h1 class="header-title">Add Product</h1>
        @elseif (\Request::is('add_lead'))  
        <h1 class="header-title">Add Lead</h1>
        @elseif ( Request::segment(1)=='edit_lead')  
        <h1 class="header-title">Edit Lead</h1>
        @elseif ( Request::segment(1)=='editProduct')  
        <h1 class="header-title">Edit Product</h1>
        @elseif ( Request::segment(1)=='edit_customer')  
        <h1 class="header-title">Edit Customer</h1>
        @elseif ( Request::segment(1)=='edit_master_loan_view')  
        <h1 class="header-title">Edit Lead Type</h1>
        @elseif (\Request::is('addNewUser'))  
        <h1 class="header-title">Add User</h1>
        @elseif (\Request::is('searchProduct'))  
        <h1 class="header-title">Search Product</h1>
        @elseif (\Request::is('user_list'))  
        <h1 class="header-title">List Users</h1>
        @elseif (\Request::is('search_user'))  
        <h1 class="header-title">Search Users</h1>
        @elseif (\Request::is('master_loan'))  
        <h1 class="header-title">Master Lead Type</h1>
        @elseif (\Request::is('lead_activity_mode'))  
        <h1 class="header-title">Lead Activity Mode</h1>
        @elseif ( Request::segment(1)=='edit_lead_activity_mode')
        <h1 class="header-title">Edit Lead Activity Mode</h1>
        @elseif (\Request::segment(1)=='lead_details')  
        <h1 class="header-title">Lead Details</h1>
        @elseif (\Request::is('searchactivity'))  
        <h1 class="header-title">Lead Details</h1>
        @elseif ( Request::segment(1)=='userdetails')  
        <h1 class="header-title">View Users</h1>
        @elseif (\Request::is('searchuseractivity'))  
        <h1 class="header-title">View Users</h1>
        @elseif (\Request::is('viewUserProfile'))  
        <h1 class="header-title">View Profile</h1>
        @elseif (\Request::is('user_change_password'))  
        <h1 class="header-title">Change Password</h1>
         @elseif (\Request::is('view_profile'))  
        <h1 class="header-title">Edit Profile</h1>
         @elseif (\Request::is('activity-manager-by-products'))  
        <h1 class="header-title">Activity Manager -By Products</h1>
         @elseif (\Request::segment(1)=='edit_profile')  
        <h1 class="header-title">Edit User Profile</h1>
         @elseif (\Request::is('search_activity_product_manage'))  
        <h1 class="header-title">Activity Manager -By Products</h1>
         @elseif (\Request::is('salepersons'))  
        <h1 class="header-title">Sales Persons</h1>
         @elseif (\Request::segment(1)=='list_sp_lead')  
        <h1 class="header-title">List Of Leads</h1>
         @elseif (\Request::segment(1)=='searchsplead')  
        <h1 class="header-title">List Of Leads</h1>
         @elseif (\Request::is('lead-report'))  
        <h1 class="header-title">Report</h1>
         @elseif (\Request::is('search-lead-report'))  
        <h1 class="header-title">Report</h1>
         @elseif (\Request::is('sp-lead-report'))  
        <h1 class="header-title">Report</h1>
         @elseif (\Request::is('search-sp-lead-report'))  
        <h1 class="header-title">Report</h1>
        @elseif (\Request::is('activity-manager-by-user'))  
        <h1 class="header-title">Audit Trail By User</h1>
         @elseif (\Request::is('activity-manager-by-product'))  
        <h1 class="header-title">Audit Trail By Product</h1>
         @elseif (\Request::is('activity-manager-by-lead'))  
        <h1 class="header-title">Audit Trail By Lead</h1>
        @elseif (\Request::segment(1)=='add_lead_customer_now')  
        <h1 class="header-title">Add Customer</h1>
        @elseif (\Request::is('product_category'))  
        <h1 class="header-title">Product Category Listing</h1>
        @elseif (\Request::segment(1)=='edit_product_category')  
        <h1 class="header-title">Product Category Listing</h1>
        @endif


        @if (\Request::segment(1)=='list_lead_status')  
        <h1 class="header-title">List of @if(isset($status) && ($status != '')){{$status}} @else @endif Lead</h1>
        @endif
        @if (\Request::segment(1)=='searchleadfromdashboard')  
        <h1 class="header-title">List of @if(isset($status) && ($status != '')){{$status}} @else @endif Lead</h1>
        @endif
    </section>
    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
  </div>
  @include('layouts.footer')  

</div>
  @include('layouts.commonscript')    
@yield('script-section')
</body>
</html>