@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Tạo vùng quản lý theo nhân viên</h2>
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
                <div class="col-md-offset-2 col-md-8">
                    @if (session('success'))
                        <div class="alert bg-success alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            {{ session('success') }}
                        </div>
                    @endif
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="POST" action="{{route('Admin::map@addMapUserPost')}}">
                                    {{ csrf_field() }}

                                        <!---------- Manager ID------------>
                                        <div class="form-group {{ $errors->has('user_id') ? 'has-error has-feedback' : '' }}">
                                            <label for="name" class="control-label text-semibold">Nhân viên quản Lý</label>
                                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhân viên quản Lý"></i>
                                            <select name="user_id" class="users">
                                                <option value="">-- Chọn nhân viên quản lý --</option>
                                                @foreach($users as $key => $value)
                                                    <option value="{{ $value->id }}" >{{ $value->email }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('user_id'))
                                                <div class="form-control-feedback">
                                                    <i class="icon-notification2"></i>
                                                </div>
                                                <div class="help-block">{{ $errors->first('user_id') }}</div>
                                            @endif
                                        </div>

                                        <!---------- Place ID------------>
                                        <div class="form-group {{ $errors->has('place') ? 'has-error has-feedback' : '' }}">
                                            <label for="name" class="control-label text-semibold">Vùng quản lý</label>
                                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Vùng quản lý"></i>
                                            <select name="place[]" class="places" multiple style="width:100%" onChange="getSelectedOptions(this)">
                                                <option value="">-- Chọn vùng quản lý --</option>
                                                @foreach($places as $key => $value)
                                                    <option data-coordinate="{{ $value->coordinates }}" value="{{  $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('place'))
                                                <div class="form-control-feedback">
                                                    <i class="icon-notification2"></i>
                                                </div>
                                                <div class="help-block">{{ $errors->first('place') }}</div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="panel panel-flat">
                                                <div class="table-responsive">
                                                    <div id="map"></div>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    </div>
                                </form>
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
        $(".users").select2();
        var places = $(".places").select2({});
        var opts = [];

//        $(places).change(function () {
//            var selections = $(this).select2('data');
//            $.each(selections, function (idx, obj) {
//                if (!selected[obj.id]) {
//                    selected[obj.id] = obj;
//                    alert(obj);
//                }
//            })
//        });

        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
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
