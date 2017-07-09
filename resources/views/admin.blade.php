<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    <meta charset="utf-8"/>
    <title>Admin Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="Preview page of Metronic Admin Theme #1 for statistics, charts, recent events and reports"
          name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>

    <link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet"
          type="text/css"/>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/pages/css/blog.min.css" rel="stylesheet" type="text/css"/>

    <link href="/assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

    <link href="/assets/global/plugins/jquery-nestable/jquery.nestable.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css"/>
    <link href="/assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css"/>

    <link href="/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css" />

    <!-- END THEME LAYOUT STYLES -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="favicon.ico"/>
    <link href="/css/custom.css" rel="stylesheet" type="text/css"/>
    <style>
        .select2-container {
            width: auto !important;
        }
        .ui-datepicker-calendar {
            display: none !important;
        }
        td.details-control {
            background: url('/assets/images/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.details td.details-control {
            background: url('/assets/images/details_close.png') no-repeat center center;
        }
        tfoot {
            display: table-header-group;
        }
        .caret{
            padding-top: 10px !important;
        }


    </style>
@stack('style_head')
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md">
<div class="page-wrapper">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">

                <div class="menu-toggler sidebar-toggler">
                    <span></span>
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
               data-target=".navbar-collapse">
                <span></span>
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li style="margin-top: 9px;">
                        <select class="selectpicker" data-width="fit">
                            <option value="{{URL::asset('')}}language/en" data-content='<span class="flag-icon flag-icon-us" ></span> English' {{ Auth::user()->lang == 'en' ? "selected=selected" : ""}}></option>
                            <option  value="{{URL::asset('')}}language/vn" data-content='<span class="flag-icon flag-icon-vn" ></span> Việt Nam' {{ Auth::user()->lang == 'vn' ? "selected=selected" : ""}}></option>
                        </select>
                    </li>

                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <img alt="" class="img-circle" src="{{ url('images/avatar.jpg') }}" style="height: 29px; width: 29px"/>
                            <span class="username username-hide-on-mobile">{{ Auth::user()->email }}</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">

                            <li>
                                <a href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                                    Đăng xuất
                                </a>
                                <form id="logout-form"
                                      action="{{ url('/logout') }}"
                                      method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"></div>
    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">

    @include('admin.nav')
    <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content" style="background: #eef1f5 !important;">

                @yield('content')


                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->


        </div>
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner"> 2016 &copy; Admin Crawler Amazone

        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->
</div>

<!--[if lt IE 9]>
<script src="/assets/global/plugins/respond.min.js"></script>
<script src="/assets/global/plugins/excanvas.min.js"></script>
<script src="/assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script>
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>

<script src="/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
        type="text/javascript"></script>

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>

{{--<script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"--}}
        {{--type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"--}}
        {{--type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/horizontal-timeline/horizontal-timeline.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>--}}

<script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>


<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

{{--<script src="/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>--}}

<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<script src="/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<script src="/js/admin/ckeditor/ckeditor.js"></script>
<script src="/assets/global/plugins/jquery-nestable/jquery.nestable.js" type="text/javascript"></script>


<script src="/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js" type="text/javascript"></script>

<script src="/assets/pages/scripts/components-color-pickers.min.js" type="text/javascript"></script>

<script>
    var baseUrl = '{{url('/admin')}}';
    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
    });

</script>
<script src="/assets/pages/scripts/ui-modals.min.js" type="text/javascript"></script>

<!-- App scripts -->

<script>

    function strip_and_string(content) {
        var regex = /(<([^>]+)>)/ig
        var body = content + '';
        var result = body.replace(regex, "");

        var result2 = result + '';

        return result2.split('.').join("");
    }

    function numberWithDots(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    jQuery.fn.dataTableExt.oSort["string-nbr-asc"] = function (x, y) {
        x = strip_and_string(x);
        y = strip_and_string(y);
        return ((parseInt(x.split('.').join("")) < parseInt(y.split('.').join(""))) ? -1 : ((parseInt(x.split('.').join("")) > parseInt(y.split('.').join(""))) ? 1 : 0));
    };

    jQuery.fn.dataTableExt.oSort["string-nbr-desc"] = function (x, y) {
        x = strip_and_string(x);
        y = strip_and_string(y);
        return ((parseInt(x.split('.').join("")) < parseInt(y.split('.').join(""))) ? 1 : ((parseInt(x.split('.').join("")) > parseInt(y.split('.').join(""))) ? -1 : 0));
    };

    $(document).ready(function () {
        $(function(){
            $('.selectpicker').selectpicker();
        });

        jQuery(".selectpicker").change(function () {
            location.href = jQuery(this).val();
        })

        $('#time_range').change(function () {

            var data = $(this).val();

            $.ajax({

                url: '{{ url('time-range') }}',
                type: 'get',
                data: {time_range: data},
                dataType: 'json',
                success: function (response) {
                    $('#start_time').val(response.start_time);
                    $('#end_time').val(response.end_time);
                }

            });
        });

        $('.select2').select2();
    });

    {{--$(document).on('click', '.delete-btn', function (e) {--}}
        {{--e.preventDefault();--}}

        {{--var url = $(this).attr('href');--}}

        {{--bootbox.confirm({--}}
            {{--message: "Bạn có chắc chắn muốn xóa",--}}
            {{--buttons: {--}}
                {{--confirm: {--}}
                    {{--label: 'Có',--}}
                    {{--className: 'btn-success'--}}
                {{--},--}}
                {{--cancel: {--}}
                    {{--label: 'Không',--}}
                    {{--className: 'btn-danger'--}}
                {{--}--}}
            {{--},--}}


            {{--callback: function (result) {--}}
                {{--if (result == true) {--}}
                    {{--window.location.href = url;--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}
    {{--});--}}


</script>

@stack('scripts_foot')
@stack('scripts')

</body>


</html>