<footer class="main-footer">
    <div class="user-setting">
<!--      <a href="{{route('view_profile')}}">-->
          <a href="{{route('view_user_profile')}}">
        <div class="number">{{substr(Auth::user()->display_name, 0, 1)}}</div>
        <div class="usrsetting-details">
          
            {{Auth::user()->display_name}}
            <span>Last on : 
                @if(Session::has('last_login_data'))
                    {{ Session::get('last_login_data')}}
                @endif </span>
          
        </div>
      </a>  
    </div>
    <a href="#" class="setting-link"><span class="setting-icon"></span></a>
    <div class="copyright">
        <span><div id="year">&copy; 2018</div></span> Simplify. All Rights Reserved.
    </div>
    <div class="copyright copyright-collage">
        <span>&copy;</span>
    </div>    
  </footer>

