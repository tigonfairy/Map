@extends('admin')
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Map</h2>
            </div>

        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <!-- Main content -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <select id="locations">
                            <option value=""> Chọn vùng</option>
                            @foreach($locations as $location)
                                <option value="{{$location->coordinates}}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="panel panel-flat">
                            <div class="table-responsive">
                                <div id="map"></div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="col-md-5">

                </div>
            </div>


        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
@endsection
@push('scripts_foot')
<script type="text/javascript"
        src="//maps.google.com/maps/api/js??key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&&sensor=true&libraries=drawing"></script>
<script type="text/javascript" src="https://hpneo.github.io/gmaps/gmaps.js"></script>
<script type="text/javascript" src="https://hpneo.github.io/gmaps/prettify/prettify.js"></script>
@endpush

@push('scripts')

<script type="text/javascript">
    var map;
    var drawingManager;
    var shapes = [];
    var patch = [];
    $(document).ready(function () {
        $('#locations').select2();

        $('#locations').on('change', '', function (e) {
            var coordinates = $(this).val();

            var coordinate = JSON.parse(coordinates);
            var bounds = new google.maps.LatLngBounds();
            for (i = 0; i < coordinate.length; i++) {
                var c = coordinate[i];
                bounds.extend(new google.maps.LatLng(c[0], c[1]));
            }
            map = new GMaps({
                div: '#map',
                lat: bounds.getCenter().lat(),
                lng:bounds.getCenter().lng(),
                width: "100%",
                height: '500px',
                zoom: 8
            });
            var path = coordinate;
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
                }
            });

        });

    });
</script>

@endpush
