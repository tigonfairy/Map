@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ trans('home.editLocation') }}</h2>
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

                    <form method="post" id="geocoding_form">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                <input type="text" id="address" name="address" placeholder="{{ trans('home.import_position') }}" class="form-control">
                                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="panel panel-flat">
                        <div class="table-responsive">
                            <div id="map"></div>
                        </div>

                    </div>
                </div>
                <div class="col-md-5">
                    <form action="{{route('Admin::map@editMapPost',$addressGeojson->id )}}" method="POST">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-4 control-label">{{ trans('home.name_location') }}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control name" value="{{ old('name') ?: @$addressGeojson->name }}" name="name" placeholder="{{ trans('home.name_location') }}">
                        </div>
                        <input type="hidden" class="form-control " id="coordinates" name="coordinates" >
                        <div class="clearfix"></div>
                    </div>
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <div class=" btn-submit-add-map">
                                    <button type="submit" class="btn btn-info">{{ trans('home.update') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{--<button id="search" class="btn green">Tìm kiếm</button>--}}
            </div>


        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
@endsection
@push('scripts_foot')
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
@endpush

@push('scripts')

<script type="text/javascript">
            var map;
            var drawingManager;
            var shapes = [];
            var patch = [];
            var markers = [];
    $(document).ready(function () {
            var coordinates = '{{ $addressGeojson->coordinates }}';
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
                    zoom: 8,
                    fullscreenControl:true
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


        $('#geocoding_form').submit(function(e){
            e.preventDefault();
            GMaps.geocode({
                address: $('#address').val().trim(),
                callback: function(results, status){
                    if(status=='OK'){
                        var latlng = results[0].geometry.location;
                        map.setCenter(latlng.lat(), latlng.lng());
                        map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng()
                        });
                    }
                }
            });
        });

        var drawingManager = new google.maps.drawing.DrawingManager({
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON,
                    ],
                    polygonOptions: {
                        strokeColor: '#333',
                        strokeOpacity: 0.5,
                        strokeWeight: 0.5,
                        fillColor: '#ffcccc',
                        fillOpacity: 0.6,
                        editable:true,
                        draggable: true
                    }
                }});
            drawingManager.setMap(map.map);

            // Add a listener for creating new shape event.
            google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
                polygon.setMap(null);
                var newShape = event.overlay;
                newShape.type = event.type;
                shapes.push(newShape);
                if (drawingManager.getDrawingMode()) {
                    drawingManager.setDrawingMode(null);
                }

            });

// add a listener for the drawing mode change event, delete any existing polygons
            google.maps.event.addListener(drawingManager, "drawingmode_changed", function () {
                if (drawingManager.getDrawingMode() != null) {
                    for (var i = 0; i < shapes.length; i++) {
                        shapes[i].setMap(null);
                    }
                    shapes = [];
                }
            });

            // Add a listener for the "drag" event.
            google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
                overlayDragListener(event.overlay);
                getPolygonCoords(event.overlay);
            });

//        });


        function overlayDragListener(overlay) {
            google.maps.event.addListener(overlay.getPath(), 'set_at', function(event){
                $('#vertices').val(overlay.getPath().getArray());
            });
            google.maps.event.addListener(overlay.getPath(), 'insert_at', function(event){
                $('#vertices').val(overlay.getPath().getArray());
            });
        }

        function getPolygonCoords(bermudaTriangle) {
            var len = bermudaTriangle.getPath().getLength();
            var test = [];
            for (var i = 0; i < len; i++) {
                test.push(bermudaTriangle.getPath().getAt(i).toUrlValue(5));
            }
            $('#coordinates').val(JSON.stringify(test));
        }
    });
</script>
@endpush
