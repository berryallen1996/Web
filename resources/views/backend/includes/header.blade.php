 
<header class="main-header">
    <a href="javascript:;" style="cursor:default;" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        
         @if(Auth::User()->user_type=='super-admin' || Auth::User()->user_type=='super-sub-admin')
            <span class="logo-mini"><img src="{{ asset('/backend/dist/img/logo.png') }}" /></span> 
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="{{ asset('/backend/dist/img/logo.png') }}" /></span> 
        @endif
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="javascript:;" class="sidebar-toggle" data-toggle="offcanvas" role="button" data-url="{{ url('ajax/togglesidebar') }}">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- <span class="page-title">{{ $page_title or '' }}</span> -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav ">
            <!-- Menu Setting -->
            <li class="dropdown notifications-menu">
                   <a href="" title="Bookmark">
                       <span class="setting-icon"><img src="{{ asset('/backend/dist/img/bookmark.png') }}" /></span>
                   </a>
                 
               </li>
               <!-- END  Setting -->
               
                <!-- User Menu -->
                <li class="dropdown notifications-menu">

                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="user-image">
                           @if(!empty(Auth::User()->profile_picture))
                           
                                <?php  $img = Auth::User()->profile_picture; ?>
                                <img  src="{{ asset('uploads/profile_picture/'.$img) }}" />
                            
                            @endif
                        </span>
                       
                        <!-- <span class="hidden-xs pull-left" style="position: relative; top: 6px;">{{ Auth::guard('web')->user()->first_name }}</span> -->
                    </a>
                    <ul class="dropdown-menu">
                        @if(Auth::User()->user_type=='super-admin')
                            <li><a href="{{ url(USERTYPE_ADMINURL.'/edit_profile') }}" title="Edit Profile">Edit Profile <i class="fa fa-power-pencil"></i></a></li>
                        
                        @endif
                        <li><a href="{{ url('admin/logout') }}" title="Sign Out">Logout <i class="fa fa-power-off"></i></a></li>
                    </ul>
                </li>
            </ul>
            <div class="search-section">
                <form role="form" method="get" id="myForm" action="" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token" />
                        
                    <div id="custom-search-input">
                    
                        <div class="input-group">
                            <input type="text" name="search_text" autocomplete="off" class="form-control input-lg" value="{{ @$search_title }}" id="search_text" placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn sendButton" id="submit" onclick="getMessage()" id="button" type="submit"><img src="{{ asset('/backend/dist/img/search-icon.png') }}" class="mCS_img_loaded " /></button>
                            </span>
                        </div>
                    
                    </div>
                </form>
            </div>
        </div>
    </nav>
</header>
@section('requirejs')
<script type="text/javascript">
        $(document).ready(function(){

            $('.sendButton').attr('disabled',true);
            $('#search_text').keyup(function(){
            if($.trim($(this).val()).length !=0)
                $('.sendButton').attr('disabled', false);            
            else
                $('.sendButton').attr('disabled',true);
            })
        });
</script>
@endsection