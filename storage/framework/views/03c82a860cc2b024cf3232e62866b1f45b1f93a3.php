<div class="page-sidebar-wrapper">

    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">

            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <li class="sidebar-search-wrapper">

            </li>

            <li class="nav-item">
                <a href="<?php echo e(url('/admin')); ?>" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title"><?php echo e(trans('home.Dashboard')); ?></span>
                    <span class="arrow"></span>
                </a>
            </li>

            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title"><?php echo e(trans('home.Map')); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" >
                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::map@index')); ?>" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title"><?php echo e(trans('home.managerMap')); ?></span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::map@addMap')); ?>" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title"><?php echo e(trans('home.addLocation')); ?></span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::map@listAgency')); ?>" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title"><?php echo e(trans('home.managerAgency')); ?></span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::map@listMapUser')); ?>" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title"><?php echo e(trans('home.listBusinessArea')); ?></span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title"><?php echo e(trans('home.Product')); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" >

                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::product@add')); ?>" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title"><?php echo e(trans('home.addProduct')); ?></span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::product@index')); ?>" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title"><?php echo e(trans('home.listProduct')); ?></span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-user"></i>

                    <span class="title">Dữ liệu của đại lý</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" >

                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::saleAgent@add')); ?>" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Thêm Doanh Số cho Đại lý</span>
                            <span class="arrow"></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::saleAgent@index')); ?>" class="nav-link nav-toggle">
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

                    <span class="title"><?php echo e(trans('home.managerAccount')); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" >
                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::user@index')); ?>" class="nav-link ">
                            <i class="icon-lock"></i>
                            <span class="title"><?php echo e(trans('home.listAccount')); ?></span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::role@index')); ?>" class="nav-link" >
                            <i class="icon-lock"></i>
                            <span class="title"><?php echo e(trans('home.role')); ?></span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="<?php echo e(route('Admin::permission@index')); ?>" class="nav-link" >
                            <i class="icon-lock"></i>
                            <span class="title"><?php echo e(trans('home.permission')); ?></span>
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