@extends('admin')
@section('content')
    <style>
        .agent-info{
            cursor: pointer;
            font-family: Roboto, Arial, sans-serif;
            font-size: 11px;
            min-width: 250px;
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
        .search-input {
            width: 100%;
            padding: 10px;
        }
        .button-list {
            left:  0px !important;
            top: 70px !important;
        }
        .button-mode {
            left:  0px !important;
            top: 110px !important;
        }
    </style>
    <!-- Page header -->
    {{--<div class="page-header">--}}
        {{--<div class="page-header-content">--}}
            {{--<div class="page-title">--}}
                {{--<h2>Gui Search</h2>--}}
            {{--</div>--}}

        {{--</div>--}}
    {{--</div>--}}
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <!-- Main content -->
        <div class="content-wrapper">

            <div class="row">

                <div class="col-md-1 col-sm-1">

                </div>
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
    var infoWindows = [];
    var countAgent = '{{$agents->count()}}';
    countAgent = parseInt(countAgent);
    $(document).ready(function () {
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '600px',
            zoom: 11,
            streetViewControl: false,
            mapTypeControl:false,
            fullscreenControl: true,

        });

        var darkmode = [
                    {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                    {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                    {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                    {
                        featureType: 'administrative.locality',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#d59563'}]
                    },
                    {
                        featureType: 'poi',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#d59563'}]
                    },
                    {
                        featureType: 'poi.park',
                        elementType: 'geometry',
                        stylers: [{color: '#263c3f'}]
                    },
                    {
                        featureType: 'poi.park',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#6b9a76'}]
                    },
                    {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [{color: '#38414e'}]
                    },
                    {
                        featureType: 'road',
                        elementType: 'geometry.stroke',
                        stylers: [{color: '#212a37'}]
                    },
                    {
                        featureType: 'road',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#9ca5b3'}]
                    },
                    {
                        featureType: 'road.highway',
                        elementType: 'geometry',
                        stylers: [{color: '#746855'}]
                    },
                    {
                        featureType: 'road.highway',
                        elementType: 'geometry.stroke',
                        stylers: [{color: '#1f2835'}]
                    },
                    {
                        featureType: 'road.highway',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#f3d19c'}]
                    },
                    {
                        featureType: 'transit',
                        elementType: 'geometry',
                        stylers: [{color: '#2f3948'}]
                    },
                    {
                        featureType: 'transit.station',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#d59563'}]
                    },
                    {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{color: '#17263c'}]
                    },
                    {
                        featureType: 'water',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#515c6d'}]
                    },
                    {
                        featureType: 'water',
                        elementType: 'labels.text.stroke',
                        stylers: [{color: '#17263c'}]
                    }
                ]
           ;
        map.addControl({
            position: 'top_left',
            content:  '<a href="#" class="btn btn-info" id="swift" >Hide all</a>',
            classes : 'button-list'
        });
        map.addControl({
            position: 'top_left',
            content:  '<a href="#" class="btn btn-info" id="button-mode" >Dark mode</a>',
            classes : 'button-mode'
        });

        $(document).on('click', '#button-mode', function () {
            var text = $(this).text();
            if (text == 'Dark mode') {
                $(this).text('Night mode');
                map.set('styles' ,darkmode);

            }
            else {
                $(this).text('Dark mode');
                map.set('styles' ,[]);

            }
        });
        var tableSales = '<div class="agent-info"><div class="search"><input type="text" class="search-input form-control" placeholder="Tìm kiếm đại lý"></div>';
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
                tableSales+= '<div id="'+'{{$key}}'+'" class="agent_detail" data-search="{{str_slug($agent->name)}},{{$agent->name}}" >' +
                    '<p class="data-search-agent"  style="font-size:' +'{{$agent->user->fontSize}}' + 'px; color:' +'{{$agent->user->textColor}}' + '">'  + '{{$agent->name}}' + ' %TT ' + numberWithCommas('{{$sales_real}}')  + '/' + numberWithCommas('{{$capacity}}') + '=' +'{{$percent}}'+ '%</p></div>';

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


        var shapeOptions = {
            strokeWeight: 1,
            strokeOpacity: 1,
            fillOpacity: 0.2,
            editable: true,
            draggable: true,
            clickable: true,
            strokeColor: '#3399FF',
            fillColor: '#3399FF'
        };
        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    google.maps.drawing.OverlayType.CIRCLE,
                    google.maps.drawing.OverlayType.RECTANGLE,
                    google.maps.drawing.OverlayType.POLYGON
                ],
                polygonOptions: {
                    fillColor: '#ffff00',
                    fillOpacity: 1,
                    strokeWeight: 1,
                    strokeColor: '#ff0000',
                    clickable: true,
                    editable: true,
                    draggable: true
                },
                rectangleOptions: shapeOptions,
                circleOptions: shapeOptions,
            }
        });

        drawingManager.setMap(map.map);

        google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
            for (var i = 0; i < shapes.length; i++) {
                shapes[i].setMap(null);
            }
            shapes = [];
            var newShape = event.overlay;
            newShape.type = event.type;
            shapes.push(newShape);
            $('.agent-info').show();
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
            $('.search').show();
            $('#swift').show();
            $('.agent-info').show();
            $('.search-input').val('');
            for (var j = 0; j < countAgent; j++) {
                markers[j].setVisible(true);
                $('#'+j).show();
            }


        });


        google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
            $('#swift').hide();

            $('.agent-info').show();
//            overlayDragListener(event.overlay);
            if(event.type =='polygon') {
                getPolygonCoords(event.overlay);
            }

            if(event.type =='circle') {
                getCircle(event.overlay);
            }
            if(event.type == 'rectangle') {
                getRectangle(event.overlay);
            }
        });

//        function overlayDragListener(overlay) {
//            google.maps.event.addListener(overlay.getPath(), 'set_at', function (event) {
//                $('#vertices').val(overlay.getPath().getArray());
//            });
//            google.maps.event.addListener(overlay.getPath(), 'insert_at', function (event) {
//                $('#vertices').val(overlay.getPath().getArray());
//            });
//        }
        function getCircle(circle) {

            for (var j = 0; j < countAgent; j++) {
                var latLng = new google.maps.LatLng( markers[j].position.lat() , markers[j].position.lng() );
                if (circle.getBounds().contains(latLng) && google.maps.geometry.spherical.computeDistanceBetween(circle.getCenter(), latLng) <= circle.getRadius()) {
                    markers[j].setVisible(true);
                    $('#'+j).show();
                } else {
                    $('#'+j).hide();
                    markers[j].setVisible(false);
                }

            }
            $('.search').hide();
        }
        function getRectangle(rectangle) {

            for (var j = 0; j < countAgent; j++) {
                if (rectangle.getBounds().contains(new google.maps.LatLng( markers[j].position.lat() , markers[j].position.lng() ) )) {
                    markers[j].setVisible(true);
                    $('#'+j).show();
                } else {
                    $('#'+j).hide();
                    markers[j].setVisible(false);
                }

            }
            $('.search').hide();
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
            $('.search').hide();
//            $('#coordinates').val(JSON.stringify(test));
        }


        //end in agent
        $(document).on('click','.agent_detail',function(e) {
            var id = $(this).attr('id');
            var latLng = markers[id].getPosition();

            map.setCenter(latLng.lat(),latLng.lng());
            for (var i=0;i<infoWindows.length;i++) {
                infoWindows[i].close();
            }
            infoWindows[id].setPosition({lat: latLng.lat(), lng: latLng.lng()});
            infoWindows[id].open(map.map);
        });



        $(document).on('input','.search-input',function(){
            var key = $(this).val();
            var items = Array();
            if(key == '') {
                $('.agent_detail').show();
            } else {
                $('.agent_detail').hide();
                var str = key.toLowerCase();
                $('.agent_detail').each(function(){
                    var title = $(this).attr('data-search');
                    title=title.toLowerCase();
                    var pos = title.search(str);
                    if(pos >= 0){
                        items.push($(this).attr('id'));
                    }
                });
                if(items.length > 0 ){
                    for (var i = 0; i < items.length; i++) {
                        var id = items[i];
                        $('#'+id).show();
                    }
                }
            }

        });

        $(document).on('click', '#swift', function () {
            var text = $(this).text();
            if (text == 'Show all') {
                $(this).text('Hide all');
                for (var i=0;i<infoWindows.length;i++) {
                    infoWindows[i].close();
                    markers[i].setVisible(true);
                }

                $('.agent-info').show();
            }
            else {
                $(this).text('Show all');
                for (var i=0;i<infoWindows.length;i++) {
                    infoWindows[i].close();
                    markers[i].setVisible(false);
                }
                $('.agent-info').hide();
            }
        });
    });

    function numberWithCommas(x) {
        var parts = x.toString().split(",");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(",");
    }
</script>


@endpush
