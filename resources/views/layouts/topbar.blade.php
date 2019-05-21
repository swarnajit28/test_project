<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('user-type-dashboard') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAlCAIAAAC/AjzkAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAZtJREFUeNpi/P//PwNZ4O//v3c/PlATUCZPOwt52o6/OF1xrAloNxsz20KXqbI80qSawESexXUnO4C2Ahm//v7qPDuRDBPIsfjYi1Pf/3yHc+98vE8ni8+9uoTM/fbnG50s/vL7KzL3H1nJk8w4phyQYzEfG+/AWMzEyDQwFguw8w0KHzMCIX0sFmQXoDzkqeBjFiZmOlkszC6IzGVlYqWTxVysnMhcNrpZLMopgsxlZ2YfmMTFycJBvyITOQtxsXLR0WKkrMvDyk0/i5kYmZEs5qGfxcxIWZmfrDqDTIuR8y5aWqOfxUIcgvSzGDlBiXOJ0s9i5LaAFLcE/SzmR6qS6WoxvAXPzszGzMhMP4vdZB0hDBkeKfq1QIBAXVDFRtJCmEOoyriIzLKP7E7bALS5Btjij78+3Xx/5+ffX3Ttpu5+fKD97ARgVxFYbPVYN5HRSybHx3/+/ek5P+UX2K/vfryfcmk2nYIaGMhffyN6iM+/vaJXK5NDSIVfCc61EDemX3Z6/u3lpIuzHnx6ZCJmkK2XwkF6ew8gwAAUemzNP9V6qAAAAABJRU5ErkJggg==">
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img src="{{ asset('public/images/logo.png') }}" alt="logo" width="134" height="39">
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle hidden-lg" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="hidden-xs">
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
            @elseif (\Request::segment(1)=='onboard_email')  
            <h1 class="header-title">On Board Email</h1>
            @elseif (\Request::segment(1)=='submit_onboarding_info')  
            <h1 class="header-title">Onboarding Info</h1>
            @elseif (\Request::segment(1)=='fiveKproject')  
            <h1 class="header-title">Weekly Report List</h1>
            @elseif (\Request::segment(1)=='addfiveKproject')  
            <h1 class="header-title">Add Weekly Report</h1>
            @elseif (\Request::segment(1)=='lockExclusiveDays')  
            <h1 class="header-title">Exclusive lock days </h1>
            @elseif (\Request::segment(1)=='payment-options')  
            <h1 class="header-title">Payment option list </h1>
            @elseif (\Request::segment(1)=='expense-type')  
            <h1 class="header-title">Expense option list </h1>
            @elseif (\Request::segment(1)=='business_expense')  
            <h1 class="header-title">Add/Edit Business Expense </h1>
            @elseif (\Request::segment(1)=='list_business_expense')  
            <h1 class="header-title">Business Expense </h1>
            @elseif (\Request::segment(1)=='business_expense_report')  
            <h1 class="header-title">Expense Report </h1>
             @elseif (\Request::segment(1)=='umbrella_calculator')  
            <h1 class="header-title">Umbrella Calculator </h1>
            @elseif (\Request::segment(1)=='umbrella_report')  
            <h1 class="header-title">Umbrella Queries </h1>
            @elseif (\Request::segment(1)=='uploadExcel')  
            <h1 class="header-title">Outside Funding Arrangement </h1>
            @elseif (\Request::segment(1)=='manage_client')  
            <h1 class="header-title">My Client </h1>
            @elseif (\Request::segment(1)=='BugReportList')  
            <h1 class="header-title">Bug Report List </h1>
            @elseif (\Request::segment(1)=='report_dashbord')  
            <h1 class="header-title">Reports </h1>
            @elseif (\Request::segment(1)=='DocumentsUpload')  
            <h1 class="header-title">Upload Documents  </h1>
            @elseif (\Request::segment(1)=='DocumentStore')  
            <h1 class="header-title">Store Document List </h1>
            @endif

            @if (\Request::segment(1)=='list_lead_status')  
            <h1 class="header-title">List of @if(isset($status) && ($status != '')){{$status}} @else @endif Lead</h1>
            @endif
            @if (\Request::segment(1)=='searchleadfromdashboard')  
            <h1 class="header-title">List of @if(isset($status) && ($status != '')){{$status}} @else @endif Lead</h1>
            @endif
        </div>

        
        
        
        <!--        <h1 class="header-title">Dashboard</h1>-->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                
                <li><a href="#" class="lead-type-key-modal report_btn" data-toggle="modal" data-target="#report-modal">Report Bug</a>
                <div class="modal fade report_modal" id="report-modal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Report a bug</h4>
                            </div>
                            <div class="modal-body">
                                <form  class="report_form" method="POST" action="{{route('report_bug')}}">
                                       {{ csrf_field() }}
                                       <input type="text" name="subject" placeholder="Subject" required="">
                                       <textarea placeholder="Description" name="description" required=""></textarea>
                                    <input type="submit" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </li>
                <!-- Manager Admin -->     
                @if (Auth::user()->user_type == 'MA') 
                @if (\Request::is('manage_customer'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-customer')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('ListAllproduct'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add_product')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('list_lead'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-lead')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('user-type-dashboard'))  
                <li class="dropdown add-heder">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="plus-imgicon"></i></a>
                    <ul class="dropdown-menu">  
                        <li><a href="{{route('add-customer')}}">Add Customer</a></li>
                        <li><a href="{{route('add_product')}}">Add Product</a></li>
                        <li><a href="{{route('add-lead')}}">Add Lead</a></li> 
                    </ul>
                </li>
                @endif
                @endif
                <!-- Manager Admin end--> 
                
                <!--IT Manager -->

                @if (Auth::user()->user_type == 'IT')       
                @if (\Request::is('user-type-dashboard'))  
                <li class="dropdown add-heder">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="plus-imgicon"></i></a>
                    <ul class="dropdown-menu">  
                        <li><a href="{{route('addNewUser')}}">Add User</a></li>
                        <li><a href="{{route('master_loan')}}">Add Loan Type</a></li>
                        <li><a href="{{route('lead_activity_mode')}}">Lead Activity Modes</a></li>
                        <li><a href="{{route('DocumentsUpload')}}">Document Store</a></li>
                    </ul>
                </li>
                @endif
                @if (\Request::is('user_list'))  
                <li class="dropdown add-heder">
                    <a href="{{route('addNewUser')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('fiveKproject'))  
                <li class="dropdown add-heder">
                    <a href="{{route('addfiveKproject')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('DocumentStore'))  
                <li class="dropdown add-heder">
                    <a href="{{route('DocumentsUpload')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                
                @endif
                <!--IT Manager end-->   
                
                <!--Lead  end-->
                @if (Auth::user()->user_type == 'LM')       
                @if (\Request::is('user-type-dashboard'))  
                <li class="dropdown add-heder">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="plus-imgicon"></i></a>
                    <ul class="dropdown-menu">              
                        <li><a href="{{route('add-customer')}}">Add Customer</a></li> 
                        <li><a href="{{route('add-lead')}}">Add Lead</a></li>
                    </ul>
                </li> 
                @endif 

                @if (\Request::is('manage_customer'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-customer')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li> 
                @endif
                @if (\Request::is('list_lead'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-lead')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>                 
                </li>
                @endif
                @endif
                <!--Lead Manager end-->
                
                
                <!--SALE person -->
                @if (Auth::user()->user_type == 'SP') 
                @if (\Request::is('manage_customer'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-customer')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('ListAllproduct'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add_product')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('list_lead'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-lead')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>            
                </li>
                @endif
                @if (\Request::is('user-type-dashboard'))  
                <li class="dropdown add-heder">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="plus-imgicon"></i></a>
                    <ul class="dropdown-menu">  
                        <li><a href="{{route('add-customer')}}">Add Customer</a></li>
                        <li><a href="{{route('add_product')}}">Add Product</a></li>
                        <li><a href="{{route('add-lead')}}">Add Lead</a></li> 
                    </ul>
                </li>
                @endif
                @endif
                <!--SALE end -->


                <!-- Senior Management -->        

                @if (Auth::user()->user_type == 'SM') 
                @if (\Request::is('manage_customer'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-customer')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('ListAllproduct'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add_product')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('list_lead'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-lead')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('user-type-dashboard'))  
                <li class="dropdown add-heder">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="plus-imgicon"></i></a>
                    <ul class="dropdown-menu">  
                        <li><a href="{{route('add-customer')}}">Add Customer</a></li>
                        <li><a href="{{route('add_product')}}">Add Product</a></li>
                        <li><a href="{{route('add-lead')}}">Add Lead</a></li> 
                    </ul>
                </li>
                @endif
                @endif
                <!-- Operations Management end-->  
                
                <!--SALE person -->
                @if (Auth::user()->user_type == 'OM') 
                @if (\Request::is('manage_customer'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add-customer')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                @if (\Request::is('ListAllproduct'))  
                <li class="dropdown add-heder">
                    <a href="{{route('add_product')}}" class="dropdown-toggle"><i class="plus-imgicon"></i></a>
                </li>
                @endif
                
                @if (\Request::is('user-type-dashboard'))  
                <li class="dropdown add-heder">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="plus-imgicon"></i></a>
                    <ul class="dropdown-menu">  
<!--                        <li><a href="{{route('add-customer')}}">Add Customer</a></li>-->
                        <li><a href="{{route('add_product')}}">Add Product</a></li>
                    </ul>
                </li>
                @endif
                @endif
                <!--Operations Management end -->

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">   
                    <p>          
                        Logged in as    
                        @if (Auth::user()->user_type == 'MA')
                        <span>Sales Management</span>
                        @endif   
                        @if (Auth::user()->user_type == 'IT')
                        <span>IT Manager</span>
                        @endif   
                        @if (Auth::user()->user_type == 'LM')
                        <span>Lead Manager</span>
                        @endif
                        @if (Auth::user()->user_type == 'SP')
                        <span>Sales Executive</span>
                        @endif
                        @if (Auth::user()->user_type == 'SM')
                        <span>Senior Management</span>
                        @endif
                        @if (Auth::user()->user_type == 'OM')
                        <span>Operations Management</span>
                        @endif
                        <a href="{{ route('logout') }}" class="shutdown-icon" onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();" class="shutdown-icon"><i class="fa fa-power-off" aria-hidden="true"></i></a>
                    </p>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
                <!-- Control Sidebar Toggle Button -->

            </ul>
        </div>
    </nav>    
</header>