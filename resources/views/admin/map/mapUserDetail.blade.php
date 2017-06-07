@extends('admin')
@push('style_head')

@endpush
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="col-md-9">
                <div class="page-title">
                    <h2>Chi tiết vùng kinh doanh : {{$area->name}}</h2>
                    <h4>Quản lý : {{$area->user->email}}</h4>
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

                    <div class="baomap" style="width: 50%" >
                        <div id="map"></div>
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
        zoom: 8,
        fullscreenControl:true,
    });

    @foreach($locations as $location)
    var c = "{{$location->coordinates}}";
    var coordinate = JSON.parse(c);
    @php
    $border_color = '#333';
    $background_color = '#333';
            if($area->border_color){
            $border_color = $area->border_color;
            }
     if($area->background_color){
            $background_color = $area->background_color;
            }
            @endphp
    if (coordinate) {
        var bounds = new google.maps.LatLngBounds();
        for (i = 0; i < coordinate.length; i++) {
            var c = coordinate[i];
            bounds.extend(new google.maps.LatLng(c[0], c[1]));
        }
        var path = coordinate;
        map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
        var infoWindow{{$location->id}} = new google.maps.InfoWindow({
            content: "<p>{{$location->name}}</p>"
        });
        polygon = map.drawPolygon({
            paths: path,
            strokeColor: "{{$border_color}}",
            strokeOpacity: 1,
            strokeWeight: 1,
            fillColor: "{{$background_color}}",
            fillOpacity: 0.6,
            mouseover: function (clickEvent) {
                var position = clickEvent.latLng;
                infoWindow{{$location->id}}.setPosition(position);
                infoWindow{{$location->id}}.open(map.map);
            },
            mouseout: function (clickEvent) {
                if (infoWindow{{$location->id}}) {
                    infoWindow{{$location->id}}.close();
                }
            }
        });
        polygonArray["{{$location->id}}"] = polygon;
    }
    @endforeach

    });
//    $('#change-button-size-map').on('change.bootstrapSwitch', function(e, state) {
//        if(e.target.checked){
//            var url  = window.location.origin + window.location.pathname;
//            console.log(url);
//            url = url+'?size-map=fullscreen';
//            window.location.href = url;
//        }else{
//            var url  = window.location.origin + window.location.pathname;
//            url = url+'?size-map=landscape';
//            window.location.href = url;
//        }
//    });
</script>

@endpush
