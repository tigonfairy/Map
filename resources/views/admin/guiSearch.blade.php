@extends('admin')
@section('content')
    <style>
        .agent-info{
            cursor: pointer;
            font-family: Roboto, Arial, sans-serif;
            font-size: 11px;
            overflow-x: auto;
            max-height: 400px;
        }
        .agent-info p {
            padding: 5px ;
            margin: 5px !important;
            background-color: white;
        }
        .table-list {
            box-shadow: none !important;
            top:30% !important;
        }

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
    <div id="legend2"></div>
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
    var infoWindows = [];
    var countAgent = '{{$agents->count()}}';
    countAgent = parseInt(countAgent);
    $(document).ready(function () {
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11,
            streetViewControl: false,
            fullscreenControl: true
        });

        var tableSales = '<div class="agent-info">';

                @foreach($agents  as $key => $agent)
                @php
                    $image = '';
                        if ($agent->icon != "") {
                       $image = $agent->icon;

                   }
                   $capacity = $agent->capacity;
                    $sales_real = $agent->sales_real;
                    $percent = round(($sales_real / $capacity) * 100, 2);
                            @endphp
                    var contentString = '<div class="info" style="font-size:' + '{{$agent->user->fontSize}}' + 'px; color:' + '{{$agent->user->textColor}}' + '">' +
                            '<h5 class="address" style="font-size:' + '{{$agent->user->fontSize}}' + 'px; color:' + '{{$agent->user->textColor}}' + '">' + '{{$agent->name}}' + ' - ' + '{{$agent->address}}' + '</h5>' +
                            '<div class="user_data" style="font-size:' + '{{$agent->user->fontSize}}' + 'px; color:' + '{{$agent->user->textColor}}' + '">' +
                            '<p class="data" style="font-size:' + '{{$agent->user->fontSize}}' + 'px; color:' + '{{$agent->user->textColor}}' + '">%TT ' + numberWithCommas('{{$sales_real}}') + '/' + numberWithCommas('{{$capacity}}') + '=' + '{{$percent}}' + '%</p>' +
                            '<ul class="info_user" style="font-size:' + '{{$agent->user->fontSize}}' + 'px; color:' + '{{$agent->user->textColor}}' + '">' +
                            '<li>' + '{{$agent->user->name}}' + '</li>' +
                            '</ul>' +
                            '</div>' +
                            '</div>';

                {{--tableSales+= '<p class="data" id="'+'{{$key}}'+'" style="font-size:' + '{{$agent->user->fontSize}}' + 'px; color:' + '{{$agent->user->textColor}}' + '">%TT ' + numberWithCommas('{{$sales_real}}') + '/' + numberWithCommas('{{$capacity}}') + '=' + '{{$percent}}' + '%</p>';--}}
                tableSales+= '<div id="'+'{{$key}}'+'"><p class="" style="font-size:' +'{{$agent->user->fontSize}}' + 'px; color:' +'{{$agent->user->textColor}}' + '">'  + '{{$agent->name}}' + ' %TT ' + numberWithCommas('{{$sales_real}}')  + '/' + numberWithCommas('{{$capacity}}') + '=' +'{{$percent}}'+ '%</p></div>';
                    infoWindows['{{$key}}'] = new google.maps.InfoWindow({
                        content: contentString
                    });
                    markers['{{$key}}'] = map.addMarker({
                        lat: '{{$agent->lat}}',
                        lng: '{{$agent->lng}}',
                        title: '{{$agent->name}}',
                        icon: '{{$image}}',
                        infoWindow: infoWindows['{{$key}}'],
                        click: function (e) {
                            infoWindows['{{$key}}'].setPosition({lat: e.position.lat(), lng: e.position.lng()});
                            infoWindows['{{$key}}'].open(map.map);
                        }
                    });
                    @endforeach
                        tableSales+= '</div>';
                 map.addControl({
                        position: 'bottom_right',
                        content: tableSales,
                     classes : 'table-list'
                    });


        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON,
                ],
                polygonOptions: {
                    fillColor: '#ffff00',
                    fillOpacity: 1,
                    strokeWeight: 1,
                    strokeColor: '#ff0000',
                    clickable: false,
                    editable: true
                }
            }
        });

        drawingManager.setMap(map.map);

        google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
            var newShape = event.overlay;
            newShape.type = event.type;
            shapes.push(newShape);
//            if (drawingManager.getDrawingMode()) {
//                drawingManager.setDrawingMode(null);
//            }

        });


        google.maps.event.addListener(drawingManager, "drawingmode_changed", function () {

//            if (drawingManager.getDrawingMode() != null) {
                for (var i = 0; i < shapes.length; i++) {
                    shapes[i].setMap(null);
                }
                shapes = [];
//            }
            for (var j = 0; j < countAgent; j++) {
                markers[j].setVisible(true);
                $('#'+j).show();
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
            var len = bermudaTriangle.getPath().getLength();
            var test = [];
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0; i < len; i++) {
                var lng = bermudaTriangle.getPath().getAt(i).toUrlValue(5);
                lng = lng.split(",");
                bounds.extend(new google.maps.LatLng(lng[0], lng[1]));
                test.push(bermudaTriangle.getPath().getAt(i).toUrlValue(5));

            }

            for (var j = 0; j < countAgent; j++) {
                if (
                    google.maps.geometry.poly.containsLocation(markers[j].position, bermudaTriangle)
                ) {
                    markers[j].setVisible(true);
                    $('#'+j).show();
                } else {
                    $('#'+j).hide();
                    markers[j].setVisible(false);
                }

            }

            $('#coordinates').val(JSON.stringify(test));
        }


        //end in agent


    });

    function numberWithCommas(x) {
        var parts = x.toString().split(",");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(",");
    }
</script>


@endpush
