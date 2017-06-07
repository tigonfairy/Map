<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <li class="sidebar-search-wrapper">

            </li>

            <li class="nav-item">
                <a href="{{ url('/admin') }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ trans('home.Dashboard') }}</span>
                    <span class="arrow"></span>
                </a>
            </li>

            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">{{ trans('home.Map') }}</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" >
                    <li class="nav-item  ">
                        <a href="{{route('Admin::map@index')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">{{ trans('home.managerMap') }}</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="{{route('Admin::map@addMap')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">{{ trans('home.addLocation') }}</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="{{route('Admin::map@listAgency')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">{{ trans('home.managerAgency') }}</span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{route('Admin::map@listMapUser')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">{{ trans('home.listBusinessArea') }}</span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">{{ trans('home.Product') }}</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" >

                    <li class="nav-item  ">
                        <a href="{{route('Admin::product@add')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Thêm Sản Phẩm</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="{{route('Admin::product@index')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Danh Sách</span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">Doanh số</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" >

                    <li class="nav-item  ">
                        <a href="{{route('Admin::saleAgent@add')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Thêm Doanh Số cho Đại lý</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="{{route('Admin::saleAgent@index')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Danh Sách</span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">Quản lý Tài Khoản</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" >
                    <li class="nav-item  ">
                        <a href="{{route('Admin::user@index')}}" class="nav-link ">
                            <i class="icon-lock"></i>
                            <span class="title">Danh sách tài khoản</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{route('Admin::role@index')}}" class="nav-link" >
                            <i class="icon-lock"></i>
                            <span class="title">Role</span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="{{route('Admin::permission@index')}}" class="nav-link" >
                            <i class="icon-lock"></i>
                            <span class="title">Permission</span>
                        </a>
                    </li>

                </ul>
            </li>

        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>