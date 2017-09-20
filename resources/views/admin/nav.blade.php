<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <a href="{{ url('/') }}">
            <img src="{{ url('images/hongha.png') }}" alt="logo" class="logo-default"/> </a>
        <ul class="page-sidebar-menu  page-header-fixed  page-sidebar-menu-closed" data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">

            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <li class="sidebar-search-wrapper">

            </li>

            <li class="nav-item">
                <a href="{{ url('/admin') }}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">{{ trans('home.Dashboard') }}</span>
                    <span class="arrow"></span>
                </a>
            </li>

            {{--<li class="nav-item">--}}
            {{--<a href="javascript:;" class="nav-link nav-toggle">--}}
            {{--<i class="icon-user"></i>--}}
            {{--<span class="title">{{ trans('home.Product') }}</span>--}}
            {{--<span class="arrow"></span>--}}
            {{--</a>--}}
            {{--<ul class="sub-menu" >--}}
            {{--<li class="nav-item  ">--}}
            {{--<a href="{{route('Admin::group_product@index')}}" class="nav-link nav-toggle">--}}
            {{--<i class="icon-settings"></i>--}}
            {{--<span class="title">{{ trans('home.parent_product') }}</span>--}}
            {{--<span class="arrow"></span>--}}
            {{--</a>--}}
            {{--</li>--}}
            {{--<li class="nav-item  ">--}}
            {{--<a href="{{route('Admin::product@index')}}" class="nav-link nav-toggle">--}}
            {{--<i class="icon-settings"></i>--}}
            {{--<span class="title">{{ trans('home.Product') }}</span>--}}
            {{--<span class="arrow"></span>--}}
            {{--</a>--}}
            {{--</li>--}}
            {{--</ul>--}}
            {{--</li>--}}

            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-database"></i>
                    <span class="title">{{trans('home.dataManager')}}</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(auth()->user()->position == \App\Models\User::ADMIN)
                        <li class="nav-item  ">
                            <a href="{{route('Admin::saleAgent@index')}}" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">{{trans('home.listSaleAgent')}}</span>
                                <span class="arrow"></span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item  ">
                        <a href="{{route('Admin::saleAgent@filter')}}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Lọc dữ liệu</span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                    @if(auth()->user()->position == \App\Models\User::ADMIN)
                        <li class="nav-item  ">
                            <a href="{{route('Admin::group_product@index')}}" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">{{ trans('home.parent_product') }}</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item  ">
                            <a href="{{route('Admin::product@index')}}" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">{{ trans('home.Product') }}</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item  ">
                            <a href="{{route('Admin::user@index')}}" class="nav-link ">
                                <i class="icon-lock"></i>
                                <span class="title">{{ trans('home.listAccount') }}</span>
                            </a>
                        </li>
                    @endif
                    {{--<li class="nav-item  ">--}}
                    {{--<a href="{{route('Admin::role@index')}}" class="nav-link">--}}
                    {{--<i class="icon-lock"></i>--}}
                    {{--<span class="title">{{ trans('home.role') }}</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}

                    {{--<li class="nav-item  ">--}}
                    {{--<a href="{{route('Admin::permission@index')}}" class="nav-link">--}}
                    {{--<i class="icon-lock"></i>--}}
                    {{--<span class="title">{{ trans('home.permission') }}</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}

                </ul>
            </li>

            @if(auth()->user()->position == \App\Models\User::ADMIN  || auth()->user()->position == \App\Models\User::SALE_ADMIN)
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-map-o"></i>
                        <span class="title">{{ trans('home.Map') }}</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        {{--<li class="nav-item  ">--}}
                        {{--<a href="{{route('Admin::map@index')}}" class="nav-link nav-toggle">--}}
                        {{--<i class="icon-settings"></i>--}}
                        {{--<span class="title">{{ trans('home.managerMap') }}</span>--}}
                        {{--<span class="arrow"></span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        @if(auth()->user()->position == \App\Models\User::ADMIN )
                            <li class="nav-item  ">
                                <a href="{{route('Admin::map@listLocation')}}" class="nav-link nav-toggle">
                                    <i class="icon-settings"></i>
                                    <span class="title">{{ trans('home.managerMap') }}</span>
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
                        @endif
                            <li class="nav-item  ">
                                <a href="{{route('Admin::guiSearch')}}" class="nav-link nav-toggle">
                                    <i class="icon-settings"></i>
                                    <span class="title">{{ trans('home.search') }}</span>
                                    <span class="arrow"></span>
                                </a>
                            </li>

                            {{--<li class="nav-item  ">--}}
                            {{--<a href="{{route('Admin::map@search')}}" class="nav-link nav-toggle">--}}
                            {{--<i class="icon-settings"></i>--}}
                            {{--<span class="title">{{ trans('home.search') }}</span>--}}
                            {{--<span class="arrow"></span>--}}
                            {{--</a>--}}
                            {{--</li>--}}

                    </ul>
                </li>

            @endif

            @if(auth()->user()->position == \App\Models\User::ADMIN)
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ trans('home.config') }}</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">

                        <li class="nav-item  ">
                            <a href="{{route('Admin::config@index')}}" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">{{ trans('home.config') }}</span>
                                <span class="arrow"></span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="{{route('Admin::config@globalConfig')}}" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">{{ trans('home.configInterface') }}</span>
                                <span class="arrow"></span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>