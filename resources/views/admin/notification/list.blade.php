@extends('admin')

@section('content')
    <style>
        .unread{
            background: #eaedf2;
        }
        .item {
            padding: 15px;
        }
    </style>
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

                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="slimScrollDiv" style="position: relative; width: auto; "><div class="scroller" style="width: auto;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2" data-initialized="1">
                                <div class="general-item-list">
                                    @foreach($notifications as $notification)
                                    <div class="item @if($notification->unread) unread @endif" >
                                        <div class="item-head">
                                            <div class="item-details">
                                                <span class="item-label">{{$notification->created_at->format('s:i:H d/m/Y')}}</span>
                                            </div>

                                        </div>
                                        <div class="item-body">{{$notification->title}} </div>
                                    </div>
                                    @endforeach


                                </div>
                            </div>
                    </div>
                </div>
                <!-- END PORTLET -->
                    <div class="row" style="text-align: right">
                        {!! $notifications->links() !!}
                    </div>
            </div>
        </div>
        <!-- /main content -->
    </div>

    <!-- /page content -->

    <!-- /page container -->


@endsection

@push('scripts')

@endpush
