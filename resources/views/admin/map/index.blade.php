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
                <div class="col-md-4 col-md-offset-3">
                    <select id="province">
                        <option value=""> Chọn tỉnh </option>
                        @foreach($provinces as $provine)
                            <option value="{{ $provine }}">{{ $provine }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="district">
                        <option value="">Chọn huyện</option>
                    </select>
                </div>
                <button id="search" class="btn green">Tìm kiếm</button>
            </div>

            <div class="panel panel-flat">
                <div class="table-responsive">
                    <div id="map"></div>
                </div>

            </div>
        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
@endsection

@push('scripts')

<script type="text/javascript">
    var map;
    var drawingManager;
    var shapes = [];
    var patch = [];
    $(document).ready(function () {
        $('#province').select2();
        $('#district').select2();
        $('#province').on('change', '', function (e) {
            var province = this.value;
            $.ajax({
                url: '{{ url('maps/province/districts') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    province: province
                },
                success: function (districts) {
                    $("#district").html('');
                    $.each(districts, function (key, district) {
                        $("#district").append('<option value="' + district + '">' + district + '</option>')
                    })
                },
                error: function () {

                }
            });
        });

        $("#search").click(function () {
            var province = $("#province").val();
            var district = $("#district").val();
            $.ajax({
                url: '{{ url('maps/province/district/coordinates') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    province: province,
                    district: district
                },
                success: function (coordinates) {
                    var coordinate = JSON.parse(coordinates[0]);
                    var middle = coordinate[Math.round((coordinate.length - 1) / 2)];
                    map = new GMaps({
                        div: '#map',
                        lat: middle[0],
                        lng: middle[1],
                        width: "100%",
                        height: '500px',
                        zoom: 11,
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
                        fillColor: '#ffcccc',
                        fillOpacity: 0.6,
                        mouseover: function(clickEvent) {
                            var position = clickEvent.latLng;

                            infoWindow.setPosition(position);
                            infoWindow.open(map.map);
                        }
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
                                strokeWeight: 1,
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
                        $('#vertices').val(event.overlay.getPath().getArray());
                    });

                },
                error: function () {

                }
            });
        });

        function overlayDragListener(overlay) {
            google.maps.event.addListener(overlay.getPath(), 'set_at', function(event){
                $('#vertices').val(overlay.getPath().getArray());
            });
            google.maps.event.addListener(overlay.getPath(), 'insert_at', function(event){
                $('#vertices').val(overlay.getPath().getArray());
            });
        }
    });
</script>

@endpush
