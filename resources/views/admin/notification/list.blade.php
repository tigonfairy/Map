@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Danh sách thông báo</h2>
            </div>

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
            <div class="col-md-6 col-offset-md-3">
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                    <div class="scroller" style="overflow: hidden; width: auto;" data-always-visible="1"
                         data-rail-visible1="0" data-handle-color="#D7DCE2" data-initialized="1">
                        <div class="general-item-list">

                            @foreach($notifications as $notification)
                                <div class="item">
                                    <div class="item-head">
                                        <div class="item-details">
                                            <a href="#" class="item-name primary-link">Larry</a>
                                            <span class="item-label">4 hrs ago</span>
                                        </div>

                                    </div>
                                    <div class="item-body"> {{$notification->title}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

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
