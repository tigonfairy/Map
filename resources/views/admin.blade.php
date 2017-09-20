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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>

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
    <link rel="stylesheet" type="text/css" media="screen"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">

    <link href="/assets/global/plugins/jquery-nestable/jquery.nestable.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css"/>
    <link href="/assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css"/>

    <link href="/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css"/>

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

        .caret {
            padding-top: 10px !important;
        }
        #ui-datepicker-div { display: none; }

    </style>
    @stack('style_head')
    <style>
        .item-notification {
            background: #eaedf2 !important;
        }
        .notification-sub {
            border-bottom: 1px solid red;
        }
    </style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md page-sidebar-closed">
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


                        {{--<a href="{{ url('/') }}" class="" style="display: inline-block; padding: 8px 8px ">--}}
                            {{--<img alt="" class="img-circle " src="{{ url('images/hongha.png') }}"--}}
                                 {{--style="height: 29px; width: 29px"/>--}}
                            {{--<span style="display: inline-block; vertical-align: middle; color: white; padding: 3px 0 0 2px;"> {{ trans('home.company_name') }}</span>--}}
                        {{--</a>--}}


            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">

                    @if(Auth::user()->position == \App\Models\User::ADMIN)

                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                               data-close-others="true" aria-expanded="true">
                                <i class="icon-bell"></i>
                                <span class="badge badge-default" id="count_notification"> </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external" style="border-bottom: 1px solid red;">

                                    <h3><a href="{{route('Admin::notification@getAll')}}"> Tất cả</a></h3>
                                </li>
                                <li>
                                    <div class="slimScrollDiv"
                                         style="position: relative; overflow: hidden; width: auto; max-height: 250px;">
                                        <ul class="dropdown-menu-list scroller" id="list_notifications"
                                            style="max-height: 250px; overflow-y: scroll; width: auto;"
                                            data-handle-color="#637283" data-initialized="1">

                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    @endif


                    <li style="margin-top: 9px;">
                        <select class="selectpicker" data-width="fit">
                            <option value="{{URL::asset('')}}language/en"
                                    data-content='<span class="flag-icon flag-icon-us" ></span> English' {{ Auth::user()->lang == 'en' ? "selected=selected" : ""}}></option>
                            <option value="{{URL::asset('')}}language/vn"
                                    data-content='<span class="flag-icon flag-icon-vn" ></span> Việt Nam' {{ Auth::user()->lang == 'vn' ? "selected=selected" : ""}}></option>
                        </select>
                    </li>

                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <img alt="" class="img-circle" src="{{ url('images/avatar.jpg') }}"
                                 style="height: 29px; width: 29px"/>
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
        <div class="page-footer-inner">
            <span style="display: inline-block; vertical-align: middle; color: white; padding: 3px 0 0 2px; font-weight: bold;"> {{ trans('home.company_name') }}</span>
            <span style="display: block; vertical-align: middle; color: white; padding: 5px 0 0 5px;font-weight: bold;"> Địa chỉ: Lô C, KCN Đồng Văn, Duy Tiên, Hà Nam</span>
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

@if(Auth::user()->position == \App\Models\User::ADMIN)
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>


    <script type="text/template" id="notifications-item-template">
        <li class="notification-sub">
            <a href="#" class="js-notification-link">
                <p class="time js-notification-time"> </p>

                <p class="message js-notification-content"> </p>
            </a>
        </li>
    </script>


    <script>
        $(document).ready(function () {
            (function () {
                $('<audio id="notificationSound"><source src="{{ asset('assets/sounds/new-notification.ogg') }}" type="audio/ogg"><source src="{{ asset('assets/sounds/new-notification.mp3') }}" type="audio/mpeg"><source src="{{ asset('assets/sounds/new-notification.wav') }}" type="audio/wav"></audio>').appendTo('body');

                function loadNotifications(since) {
                    var $notifications = $('#notifications');
                    var maxItemShow = 10;

                    $.ajax({
                        url: '{{ route('Admin::notification@getNotification') }}',
                        data: since ? {since: since} : {limit: maxItemShow}
                    }).then(function (response) {
                        var unreadCount = response.metadata.unreadCount;
                        $('#count_notification').text((unreadCount > 0 ? unreadCount : ''));
                        var data = response.data.reverse();

//
//                        $notifications.find('.js-notification-time').each(function (child) {
//                            var $this = $(this),
//                                time = moment($this.data('time'));
//
//                            $this.text(time.fromNow());
//                        });
//
                        if (data.length) {
                            if (since) {
                                $('#notificationSound')[0].play();
                            }

                            data.forEach(function (notification) {

                                var $template = $('#notifications-item-template'),
                                    $item = $($template.html());
                                if(notification.unread == 1) {
                                    $item.find('.js-notification-link').addClass('item-notification');
                                }
                                $item.find('.js-notification-link').attr('href', notification.link);
                                $item.find('.js-notification-content').html(notification.title);
                                var time = moment(notification.created_at);
                                $item.find('.js-notification-time').text(time.fromNow());
                                since = time.utc().unix() + 1;
                                $item.prependTo($('#list_notifications'));
                            });
//
//                            var total = $notifications.children().length;
//
//                            if (total > maxItemShow) {
//                                $notifications.children(':nth-last-child(-n+' + (total - maxItemShow) + ')').remove();
//                            }
                        }

                        setTimeout(function () {
                            loadNotifications(since);
                        }, 1000 * 15);
                    });
                }

                loadNotifications();
            })();

            $('.js-help-icon').popover({
                container: "body",
                html: true,
                trigger: "hover",
                delay: {"hide": 1000}
            });


        });
    </script>







@endif


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
        $(function () {
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