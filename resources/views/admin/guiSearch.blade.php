@extends('admin')
@section('content')
    <style>

    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Gui Search</h2>
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
                <div class="col-md-12">

                    <div class="panel panel-flat">
                        <div class="table-responsive">
                            <div id="map"></div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
@endsection
@push('scripts_foot')
<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>

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
    var countAgent = '{{$agents->count()}}';
    countAgent = parseInt(countAgent);
    $(document).ready(function () {
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '1000px',
            zoom: 11,
            fullscreenControl: true
        });


                @foreach($agents  as $key => $agent)
                @php
                    $image = '';
                        if ($agent->icon != "") {
                       $image = $agent->icon;

                   }

                @endphp
                    var infoWindow{{$key}} = new google.maps.InfoWindow({
                        content: 'hello'
                    });
                      markers['{{$key}}'] = map.addMarker({
                    lat: '{{$agent->lat}}',
                    lng: '{{$agent->lng}}',
                    title: '{{$agent->name}}',
                    icon: '{{$image}}',
                    infoWindow: infoWindow{{$key}},
                    click: function (e) {
                        infoWindow{{$key}}.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                        infoWindow{{$key}}.open(map.map);
                    }
            });
        @endforeach


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
                    editable: true,
                    draggable: true
                }
            }
        });

        drawingManager.setMap(map.map);

        google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
            var newShape = event.overlay;
            newShape.type = event.type;
            shapes.push(newShape);
            if (drawingManager.getDrawingMode()) {
                drawingManager.setDrawingMode(null);
            }

        });


        google.maps.event.addListener(drawingManager, "drawingmode_changed", function () {
            if (drawingManager.getDrawingMode() != null) {
                for (var i = 0; i < shapes.length; i++) {
                    shapes[i].setMap(null);
                }
                shapes = [];
            }
            for(var j = 0;j < countAgent ;j++) {
                    markers[j].setVisible(true);
            }

        });


        google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
            overlayDragListener(event.overlay);
            getPolygonCoords(event.overlay);
        });


        function overlayDragListener(overlay) {
            google.maps.event.addListener(overlay.getPath(), 'set_at', function (event) {
                $('#vertices').val(overlay.getPath().getArray());
            });
            google.maps.event.addListener(overlay.getPath(), 'insert_at', function (event) {
                $('#vertices').val(overlay.getPath().getArray());
            });
        }

        function getPolygonCoords(bermudaTriangle) {
            console.log(bermudaTriangle);
            var len = bermudaTriangle.getPath().getLength();
            var test = [];
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0; i < len; i++) {
                var lng = bermudaTriangle.getPath().getAt(i).toUrlValue(5);
                    lng = lng.split(",");
                bounds.extend(new google.maps.LatLng(lng[0],lng[1]));
                test.push(bermudaTriangle.getPath().getAt(i).toUrlValue(5));

            }

            for(var j = 0;j < countAgent ;j++) {
                if(
                    google.maps.geometry.poly.containsLocation( markers[j].position, bermudaTriangle)
                ){
                    markers[j].setVisible(true);
                } else {
                    markers[j].setVisible(false);
                }

            }

            $('#coordinates').val(JSON.stringify(test));
        }
    });
</script>


@endpush
