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

            <div class="col-md-6 col-md-offset-3">
                <!-- BEGIN Portlet PORTLET-->
                <div class="m-heading-1 border-green m-bordered">
                    <h3>{{$notification->title}}</h3>
                    <h5>{{$notification->created_at->format('H:i:s d/m/Y')}}</h5>
                    @if(isset($notification->content['agent']))
                        @php $agent =$notification->content['agent'];
                            $agent = implode($agent,',');
                        @endphp
                    <p>
                        Mã đại lý chưa tồn tại : {{$agent}}
                    </p>
                    @endif

                     @if(isset($notification->content['agentImport']))
                        @php $agent =$notification->content['agentImport'];
                            $agent = implode($agent,',');
                        @endphp
                    <p>
                         đại lý chưa có quản lý : {{$agent}}
                    </p>
                    @endif
                    @if(isset($notification->content['notFound']))
                        @php $agent =$notification->content['notFound'];
                            $agent = implode($agent,',');
                        @endphp
                        <p>
                            Mã đại lý mà địa chỉ không tìm thấy : {{$agent}}
                        </p>
                    @endif

                </div>


            </div>



@endsection

@push('scripts')

@endpush
