@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            {{--<div class="page-title">--}}
                {{--<h2>Danh sách thông báo</h2>--}}
            {{--</div>--}}

            <div class="heading-elements">
                <div class="heading-btn-group">
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">
            <div class="col-md-6 col-md-offset-3">
                <!-- BEGIN PORTLET -->
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase">Danh sách thông báo</span>
                            <span class="caption-helper">45 pending</span>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; "><div class="scroller" style=" overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2" data-initialized="1">
                                <div class="general-item-list">
                                    @foreach($notifications as $notification)
                                    <div class="item">
                                        <div class="item-head">
                                            <div class="item-details">
                                                <span class="item-label">{{$notification->created_at->format('s:i:H d/m/Y')}}</span>
                                            </div>

                                        </div>
                                        <div class="item-body">{{$notification->title}} </div>
                                    </div>
                                    @endforeach


                                </div>
                            </div><div class="slimScrollBar" style="background: rgb(215, 220, 226); width: 7px; position: absolute; top: 12px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 142.24px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                    </div>
                </div>
                <!-- END PORTLET -->
            </div>
        </div>
        <!-- /main content -->
    </div>

    <!-- /page content -->

    <!-- /page container -->


@endsection

@push('scripts')

@endpush
