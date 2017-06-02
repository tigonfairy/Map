@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Chi tiết vùng kinh doanh</h2>
            </div>
        </div>
    </div>

    <div class="page-container">
        <div class="content-wrapper">
            @include('admin.flash')
            <div class="row">
                <div class="col-xs-6">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts_foot')

<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
@endpush

@push('scripts')
<script>
    var polygonArray = [];
    map = new GMaps({
        div: '#map',
        lat: 21.0277644,
        lng: 105.83415979999995,
        width: "100%",
        height: '500px',
        zoom: 11
    });
    @foreach($locations as $location)
            var c = "{{$location->coordinates}}";
    var coordinate = JSON.parse(c);
    if (coordinate) {
        var bounds = new google.maps.LatLngBounds();
        for (i = 0; i < coordinate.length; i++) {
            var c = coordinate[i];
            bounds.extend(new google.maps.LatLng(c[0], c[1]));
        }
        var path = coordinate;
        map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
        var infoWindow = new google.maps.InfoWindow({
            content: 'you clicked a polyline'
        });
        polygon = map.drawPolygon({
            paths: path,
            strokeColor: '#333',
            strokeOpacity: 0.5,
            strokeWeight: 1,
            fillColor: '#333',
            fillOpacity: 0.6,
            mouseover: function (clickEvent) {
                var position = clickEvent.latLng;
                infoWindow.setPosition(position);
                infoWindow.open(map.map);
            },
            mouseout: function (clickEvent) {
                if (infoWindow) {
                    infoWindow.close();
                }
            }
        });
        polygonArray["{{$location->id}}"] = polygon;
    }
    @endforeach

</script>

@endpush
