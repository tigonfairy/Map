@extends('admin')

@section('content')
    <style>
        .unread {
            background: #eaedf2;
        }

        .item {
            padding: 15px !important;
            border-bottom: 1px solid red;
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

    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">

            <div class="col-md-6">
                <!-- BEGIN Portlet PORTLET-->
                <div class="m-heading-1 border-green m-bordered">
                    <h3>{{$notification->title}}</h3>
                    <h4>{{$notification->created_at->format('H:i:s d/m/Y')}}</h4>
                    <p>

                       </p>
                </div>


            </div>



@endsection

@push('scripts')

@endpush
