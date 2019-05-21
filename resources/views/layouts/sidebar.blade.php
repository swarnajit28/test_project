@extends('layouts.topbar')
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->      
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      
      <!-- Manager start -->
      @if (Auth::user()->user_type == 'MA')
      <ul class="sidebar-menu" data-widget="tree">        
          <li {{{ (Request::is('user-type-dashboard') ? 'class=active' : '') }}}><a href="{{route('user-type-dashboard')}}"><i class="icon-dashboard"></i> <span>My Dashboard</span></a></li>
          <li {{{ (Request::is('manage_customer') ? 'class=active' : '') }}}><a href="{{route('list-customer')}}"><i class="icon-my-user"></i> <span>My Customer</span></a></li>
          <li {{{ (Request::is('manage_client') ? 'class=active' : '') }}}><a href="{{route('list-client')}}"><i class="icon-client"></i> <span>My Client</span><span class="pull-right-container"><small class="label pull-right bg-red">{{Session::get('outside_FA_updated_on')}}</small></span></a></li>
          <li {{{ (Request::is('salepersons') ? 'class=active' : '') }}}><a href="{{route('sp-list')}}"><i class="icon-sales-person"></i> <span>My Sales Person</span></a></li>
          <li {{{ (Request::is('ListAllproduct') ? 'class=active' : '') }}}><a href="{{route('list_product')}}"><i class="icon-manage-products"></i> <span>Products</span></a></li>
          <li {{{ (Request::is('list_lead') ? 'class=active' : '') }}}><a href="{{route('list-lead')}}"><i class="icon-manage-leads"></i> <span>My Leads</span></a></li>
          <li {{{ (Request::is('payment-options') ? 'class=active' : '') }}} ><a href="{{route('payment-options')}}"><i class="icon-business-expenses"></i> <span>My Payment Options</span></a></li>
          <li {{{ (Request::is('expense-type') ? 'class=active' : '') }}} ><a href="{{route('expense-type')}}"><i class="icon-business-expenses"></i> <span>My Expense Types</span></a></li>
          <li {{{ (Request::is('list_business_expense') ? 'class=active' : '') }}} ><a href="{{route('list_business_expense')}}"><i class="icon-business-expenses"></i> <span>Business Expenses</span></a></li> 
<!--          <li {{{ (Request::is('business_expense') ? 'class=active' : '') }}} ><a href="{{route('business_expense')}}"><i class="icon-manage-leads"></i> <span>Business expenses</span></a></li> -->
          <li {{{ (Request::is('lead-report') ? 'class=active' : '') }}} ><a href="{{route('report_dashbord')}}"><i class="icon-performance"></i> <span>Reports</span></a></li>
<!--          <li {{{ (Request::is('business_expense_report') ? 'class=active' : '') }}} ><a href="{{route('business_expense_report')}}"><i class="icon-manage-leads"></i> <span>Business Report</span></a></li> -->
          
          <li {{{ (Request::is('umbrella_calculator') ? 'class=active' : '') }}} ><a href="{{route('umbrella_calculator')}}"><i class="icon-umbrella-calculator"></i> <span>Umbrella Calculator</span></a></li>
          <li {{{ (Request::is('DocumentStore') ? 'class=active' : '') }}} ><a href="{{route('DocumentStore')}}"><i class="icon-document-storage"></i> <span>Documents Store</span></a></li>
          
  <!--        <li><a href="#"><i class="icon-performance"></i> <span>Performance</span></a></li>
          <li><a href="#"><i class="icon-audit-trail"></i> <span>Audit Trail</span></a></li>-->


      </ul>
      @endif
      <!-- Manager end -->

      @if (Auth::user()->user_type == 'IT')
      <ul class="sidebar-menu" data-widget="tree">       
        <li {{{ (Request::is('user-type-dashboard') ? 'class=active' : '') }}}><a href="{{route('user-type-dashboard')}}"><i class="icon-dashboard"></i> <span>My Dashboard</span></a></li>
        <li  {{{ (Request::is('user_list') ? 'class=active' : '') }}} ><a href="{{route('user_list')}}"><i class="icon-my-user"></i> <span>My User</span></a></li>
<!--        <li><a href="#"><i class="icon-manage-products"></i> <span>Master Entries</span></a></li>-->
        <li {{{ (Request::is('lead_activity_mode') ? 'class=active' : '') }}}><a href="{{route('lead_activity_mode')}}"><i class="icon-menu-master-entries"></i> <span>Lead Activity Modes</span></a></li>

        <li {{{ (Request::is('product_category') ? 'class=active' : '') }}}><a href="{{route('product_category')}}"><i class="icon-product-category"></i> <span>Product Category</span></a></li>
        <li {{{ (Request::is('master_loan') ? 'class=active' : '') }}}><a href="{{route('master_loan')}}"><i class="icon-lead-type"></i> <span>My Lead Type</span></a></li>
        <li {{{ (Request::is('onboard_email') ? 'class=active' : '') }}}><a href="{{route('onboard_email')}}"><i class="icon-on-boarding-email"></i> <span>On Boarding Email</span></a></li>
        <li {{{ (Request::is('activity-manager-by-user') ? 'class=active' : '') }}}><a href="{{route('activity-manager-by-user')}}"><i class="icon-audit-trail"></i> <span>Audit Trail By User</span></a></li>
        <li {{{ (Request::is('activity-manager-by-product') ? 'class=active' : '') }}}><a href="{{route('activityManagerByProduct')}}"><i class="icon-audit-trail"></i> <span>Audit Trail By Product</span></a></li>
        <li {{{ (Request::is('activity-manager-by-lead') ? 'class=active' : '') }}}><a href="{{route('activityManagerByLead')}}"><i class="icon-audit-trail"></i> <span>Audit Trail By Lead</span></a></li>
        <li {{{ (Request::is('fiveKproject') ? 'class=active' : '') }}}><a href="{{route('fiveKproject')}}"><i class="icon-project-5k"></i> <span>Project 5K</span></a></li>
        <li {{{ (Request::is('lockExclusiveDays') ? 'class=active' : '') }}}><a href="{{route('lockExclusiveDays')}}"><i class="icon-lock-exclusive-days"></i> <span>Lock Exclusive days</span></a></li>
        <li {{{ (Request::is('DocumentStore') ? 'class=active' : '') }}}><a href="{{route('DocumentStore')}}"><i class="icon-document-storage"></i> <span>Document Store</span></a></li>
      </ul>
      @endif
      <!-- IT Manager end -->
      
      
      <!-- Lead Manager start (LM)-->
      @if (Auth::user()->user_type == 'LM')
      <ul class="sidebar-menu" data-widget="tree">        
          <li {{{ (Request::is('user-type-dashboard') ? 'class=active' : '') }}}><a href="{{route('user-type-dashboard')}}"><i class="icon-dashboard"></i> <span>My Dashboard</span></a></li>
          <li {{{ (Request::is('manage_customer') ? 'class=active' : '') }}}><a href="{{route('list-customer')}}"><i class="icon-my-user"></i> <span>My Customer</span></a></li>
          <li {{{ (Request::is('manage_client') ? 'class=active' : '') }}}><a href="{{route('list-client')}}"><i class="icon-client"></i> <span>My Client</span><span class="pull-right-container"><small class="label pull-right bg-red">{{Session::get('outside_FA_updated_on')}}</small></span></a></li>     
          <li {{{ (Request::is('list_lead') ? 'class=active' : '') }}}><a href="{{route('list-lead')}}"><i class="icon-manage-leads"></i> <span>My Leads</span></a></li>
  <!--         <li ><a href="#"><i class="icon-manage-products"></i> <span>Reports</span></a></li> -->
          <li {{{ (Request::is('lead-report') ? 'class=active' : '') }}} ><a href="{{route('lead-report')}}"><i class="icon-performance"></i> <span>Reports</span></a></li>  
          <li {{{ (Request::is('DocumentStore') ? 'class=active' : '') }}} ><a href="{{route('DocumentStore')}}"><i class="icon-document-storage"></i> <span>Documents Store</span></a></li>
      </ul>
      @endif
      <!-- Lead Manager end -->
      
      
      <!--- Sales Person (SP) -->
      @if (Auth::user()->user_type == 'SP')
      <ul class="sidebar-menu" data-widget="tree">        
          <li {{{ (Request::is('user-type-dashboard') ? 'class=active' : '') }}}><a href="{{route('user-type-dashboard')}}"><i class="icon-dashboard"></i> <span>My Dashboard</span></a></li> 
          <li {{{ (Request::is('manage_customer') ? 'class=active' : '') }}}><a href="{{route('list-customer')}}"><i class="icon-my-user"></i> <span>My Customer</span></a></li>
          <li {{{ (Request::is('manage_client') ? 'class=active' : '') }}}><a href="{{route('list-client')}}"><i class="icon-client"></i> <span>My Client</span><span class="pull-right-container"><small class="label pull-right bg-red">{{Session::get('outside_FA_updated_on')}}</small></span></a></li>     
          <li {{{ (Request::is('list_lead') ? 'class=active' : '') }}}><a href="{{route('list-lead')}}"><i class="icon-manage-leads"></i> <span>My Leads</span></a></li>
          <li {{{ (Request::is('ListAllproduct') ? 'class=active' : '') }}}><a href="{{route('list_product')}}"><i class="icon-manage-products"></i> <span>Products</span></a></li>
<!--          <li {{{ (Request::is('business_expense') ? 'class=active' : '') }}} ><a href="{{route('business_expense')}}"><i class="icon-manage-leads"></i> <span>Business expenses</span></a></li> -->
          <li {{{ (Request::is('sp-lead-report') ? 'class=active' : '') }}} ><a href="{{route('sp-lead-report')}}"><i class="icon-performance"></i> <span>Reports</span></a></li> 
          <li {{{ (Request::is('umbrella_calculator') ? 'class=active' : '') }}} ><a href="{{route('umbrella_calculator')}}"><i class="icon-umbrella-calculator"></i> <span>Umbrella Calculator</span></a></li>
<!--          <li {{{ (Request::is('test') ? 'class=active' : '') }}} ><a href="{{route('test')}}"><i class="icon-menu_sales_executive"></i> <span>Test</span></a></li>-->
          <li {{{ (Request::is('list_business_expense') ? 'class=active' : '') }}} ><a href="{{route('list_business_expense')}}"><i class="icon-business-expenses"></i> <span>Business expenses</span></a></li> 
          <li {{{ (Request::is('DocumentStore') ? 'class=active' : '') }}} ><a href="{{route('DocumentStore')}}"><i class="icon-document-storage"></i> <span>Documents Store</span></a></li>
      </ul>
      @endif
            <!-- Sales Person end-->
            
             <!--- Senior Management (SM) -->
      @if (Auth::user()->user_type == 'SM')
      <ul class="sidebar-menu" data-widget="tree">        
          <li {{{ (Request::is('user-type-dashboard') ? 'class=active' : '') }}}><a href="{{route('user-type-dashboard')}}"><i class="icon-dashboard"></i> <span>My Dashboard</span></a></li>
          <li {{{ (Request::is('manage_customer') ? 'class=active' : '') }}}><a href="{{route('list-customer')}}"><i class="icon-my-user"></i> <span>My Customer</span></a></li>
          <li {{{ (Request::is('manage_client') ? 'class=active' : '') }}}><a href="{{route('list-client')}}"><i class="icon-client"></i> <span>My Client</span><span class="pull-right-container"><small class="label pull-right bg-red">{{Session::get('outside_FA_updated_on')}}</small></span></a></li>
          <li {{{ (Request::is('salepersons') ? 'class=active' : '') }}}><a href="{{route('sp-list')}}"><i class="icon-sales-person"></i> <span>My Sales Person</span></a></li>
          <li {{{ (Request::is('ListAllproduct') ? 'class=active' : '') }}}><a href="{{route('list_product')}}"><i class="icon-manage-products"></i> <span>Products</span></a></li>
          <li {{{ (Request::is('list_lead') ? 'class=active' : '') }}}><a href="{{route('list-lead')}}"><i class="icon-manage-leads"></i> <span>My Leads</span></a></li>
          <li {{{ (Request::is('uploadExcel') ? 'class=active' : '') }}}><a href="{{route('upload_excel')}}"><i class="icon-mail"></i> <span> Outside Funding</span></a></li>
          <li {{{ (Request::is('lead-report') ? 'class=active' : '') }}} ><a href="{{route('lead-report')}}"><i class="icon-performance"></i> <span>Reports</span></a></li>
          <li {{{ (Request::is('umbrella-report') ? 'class=active' : '') }}} ><a href="{{route('umbrella-report')}}"><i class="icon-umbrella-calculator"></i> <span>Umbrella Reports</span></a></li>  
          <li {{{ (Request::is('DocumentStore') ? 'class=active' : '') }}} ><a href="{{route('DocumentStore')}}"><i class="icon-document-storage"></i> <span>Documents Store</span></a></li>
      </ul>
      @endif
       <!-- Senior Management end-->
       
        <!--- Operations Management (OM) -->
       @if (Auth::user()->user_type == 'OM')
      <ul class="sidebar-menu" data-widget="tree">        
        <li {{{ (Request::is('user-type-dashboard') ? 'class=active' : '') }}}><a href="{{route('user-type-dashboard')}}"><i class="icon-dashboard"></i> <span>My Dashboard</span></a></li> 
<!--        <li {{{ (Request::is('list_lead') ? 'class=active' : '') }}}><a href="{{route('list-lead')}}"><i class="icon-manage-leads"></i> <span>My Leads</span></a></li>-->
<!--        <li {{{ (Request::is('manage_customer') ? 'class=active' : '') }}}><a href="{{route('list-customer')}}"><i class="icon-n"></i> <span>My Customer</span></a></li>-->
         <li {{{ (Request::is('manage_client') ? 'class=active' : '') }}}><a href="{{route('list-client')}}"><i class="icon-client"></i> <span>My Client</span><span class="pull-right-container"><small class="label pull-right bg-red">{{Session::get('outside_FA_updated_on')}}</small></span></a></li>
        <li {{{ (Request::is('ListAllproduct') ? 'class=active' : '') }}}><a href="{{route('list_product')}}"><i class="icon-manage-products"></i> <span>Products</span></a></li>
        <li {{{ (Request::is('uploadExcel') ? 'class=active' : '') }}}><a href="{{route('upload_excel')}}"><i class="icon-mail"></i> <span> Outside Funding</span></a></li>
        <li {{{ (Request::is('DocumentStore') ? 'class=active' : '') }}} ><a href="{{route('DocumentStore')}}"><i class="icon-document-storage"></i> <span>Documents Store</span></a></li>
<!--        <li {{{ (Request::is('downloadExcel') ? 'class=active' : '') }}}><a href="{{route('download_excel')}}"><i class="icon-manage-products"></i> <span>Test Download</span></a></li>-->
        
<!--        <li {{{ (Request::is('sp-lead-report') ? 'class=active' : '') }}} ><a href="{{route('sp-lead-report')}}"><i class="icon-menu_sales_executive"></i> <span>Reports</span></a></li>        -->
      </ul>
      @endif
        <!-- Operations Management end-->
      
      <a href="#" class="sidebar-toggle custom-toggle hidden-xs" data-toggle="push-menu" role="button">
      <span class="arrow-toggle"></span>
      </a>
    </section>
        
    <!-- /.sidebar -->
  </aside>
  <!-- <script src="{{ asset('public/js/app.js') }}"></script> -->
  
 