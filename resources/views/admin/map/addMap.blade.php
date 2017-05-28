@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Tạo vùng địa lý</h2>
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
                            <div class="col-md-5">
                                <input type="text" id="address" name="address" placeholder="Nhập vị trí" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info">Search</button>
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
                    <form action="{{route('Admin::map@addMapPost')}}" method="POST">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tên</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control name" name="name" placeholder="Nhập tên vùng địa lý">
                        </div>
                        <input type="hidden" class="form-control " id="coordinates" name="coordinates" >
                    </div>
                        <button type="submit" class="btn btn-info">Tạo</button>
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
<script type="text/javascript" src="//maps.google.com/maps/api/js??key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&&sensor=true&libraries=drawing"></script>
<script type="text/javascript" src="https://hpneo.github.io/gmaps/gmaps.js"></script>
<script type="text/javascript" src="https://hpneo.github.io/gmaps/prettify/prettify.js"></script>
@endpush

@push('scripts')

<script type="text/javascript">
    var map;
    var drawingManager;
    var shapes = [];
    var patch = [];
    var markers = [];
    $(document).ready(function () {
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
        });

//        map.addListener('click', function (e) {
//
//            var ll = {lat: e.latLng.lat(), lng: e.latLng.lng()};
//
//            map.removeMarkers();
//            markers = [];
//            map.addMarker({
//                lat: ll.lat,
//                lng: ll.lng,
//                title: 'Lima',
//                click: function(e) {
//                    alert('You clicked in this marker');
//                }
//            });
//
//        });
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