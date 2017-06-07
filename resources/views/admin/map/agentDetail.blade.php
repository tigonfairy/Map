@extends('admin')
@push('style_head')

@endpush
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="col-md-9">
                <div class="page-title">
                    <h2>Chi tiết đại lý : {{$agent->name}}</h2>
                    <h4>Quản lý bởi: {{$agent->user->email}}</h4>
                </div>
            </div>
            {{--<div class="col-md-3">--}}
                {{--<div class="page-title button-change-size-map">--}}
                    {{--<input type="checkbox" id="change-button-size-map" name="checkbox" @if(Request::input('size-map') == 'fullscreen') checked @endif class="make-switch" data-on-text="&nbsp;FullScreen&nbsp;" data-off-text="&nbsp;Landscape&nbsp;">--}}
                {{--</div>--}}

            {{--</div>--}}
            <div class="clearfix"></div>

        </div>

    </div>

    <div class="page-container">
        <div class="content-wrapper">
            @include('admin.flash')

            <div class="row">

                    <div class="baomap col-xs-6">
                        <div id="map"></div>
                    </div>
                {{--số liệu--}}
                    <div class="col-xs-6">
                        @if($agent->product)
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="users-table">
                            <thead>
                            <tr>
                                <th>Nhóm sp</th>
                                <th>Doanh số kế hoạch</th>
                                <th>Doanh số thực tế</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($agent->product as $p)
                                <tr role="row" id="">
                                    <td>{{$p->product->name}}</td>
                                    <td>{{$p->sales_plan}}</td>
                                    <td>{{$p->sales_real}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @endif




                    </div>

            </div>
        </div>
    </div>

@endsection
@push('scripts_foot')
<script src="/assets/pages/scripts/components-bootstrap-switch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
@endpush

@push('scripts')
<script>
    $(document).ready(function(){

    var heightPageContent = $('.page-content').height();
    var heightPageHeader = $('.page-header-content').height();
    $('.baomap').height(heightPageContent-heightPageHeader);
    var polygonArray = [];
    map = new GMaps({
        div: '#map',
        lat: 21.0277644,
        lng: 105.83415979999995,
        width: "100%",
        height: '100%',
        zoom: 12,
        fullscreenControl:true
    });
        var contentString = '<div id="content">'+
                '<p id="name">'+"{{$agent->name}}"+'</p>'+
                '<p id="manager">'+'{{$agent->user->email}}'+'</p>'+

                '</div>';

        var infoWindow = new google.maps.InfoWindow({
            content: contentString
        });

        map.addMarker({
            lat: "{{$agent->lat}}",
            lng: "{{$agent->lng}}",
            click: function(e) {
                infoWindow.setPosition({lat: e.position.lat(),lng:e.position.lng()});
                infoWindow.open(map.map);
            }
        });


    });

</script>

@endpush
