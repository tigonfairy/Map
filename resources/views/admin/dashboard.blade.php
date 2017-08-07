@extends('admin')
@section('content')
    <style>
        .ct {
            -webkit-column-count: 2; /* Chrome, Safari, Opera */
            -moz-column-count: 2; /* Firefox */
            column-count: 2;
        }
        @media (max-width: 768px) {
            .ct {
                -webkit-column-count: 1; /* Chrome, Safari, Opera */
                -moz-column-count: 1; /* Firefox */
                column-count: 1;
            }
        }
        @media (min-width: 992px) {
            .ct {
                -webkit-column-count: 2; /* Chrome, Safari, Opera */
                -moz-column-count: 2; /* Firefox */
                column-count: 2;
            }
        }
        .info {
            z-index: 99999;
        }
        .data {
            border: 1px solid yellow;
            background-color: yellow;
            color: red;
            font-size: 12px;
            float: left;
        }
        .info_user {
            list-style: none;
            font-size: 12px;
            margin-left: 10px;
            float: left;
        }
    </style>
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> Admin Dashboard</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->

    <div class="row">
        <div class="portlet light ">

            <form method="post" id="geocoding_form">
                <div class="row">
                    <div class="col-md-2">
                        <select class="search_type form-control">
                            <option value="">-- Chọn loại {{ trans('home.search') }} --</option>
                            <option value="1">Theo vùng</option>
                            <option value="2">Theo giám sát vùng</option>
                            <option value="3">Theo nhân viên kinh doanh</option>
                            <option value="4">Theo đại lý</option>
                        </select>
                    </div>
                    <input type="hidden" name="type_search" value="" id="type_search"/>
                    <div class="col-md-2">
                        <select name="data_search" class="data_search form-control" id="locations" style="width:100%">
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                    </div>
                </div>
            </form>

            <div class="portlet-body">
                <div id="map" style=" width: 100% ;height: 500px"></div>
            </div>
        </div>
    </div>

    <div class="row ct">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Bảng doanh số</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="portlet-body">
                    <div id="tableData"></div>
                </div>
            </div>
        </div>


        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-directions font-green hide"></i>
                    <span class="caption-subject bold font-dark uppercase "> Tiến độ doanh số</span>
                </div>
            </div>
            <div class="portlet-body">
                <div id="container" class="row"></div>
            </div>
        </div>


        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption ">
                    <span class="caption-subject font-dark bold uppercase">Biểu đồ</span>
                </div>
                <div class="clearfix" style="margin-bottom: 20px">
                    <div class="btn-group col-xs-12" data-toggle="buttons">
                        <label class="btn btn-default active col-xs-6 col-md-3">
                            <input type="radio" name="radio" class="toggle radioButton" value="1"> Tháng
                            gần nhất
                        </label>
                        <label class="btn btn-default col-xs-6 col-md-3">
                            <input type="radio" name="radio" class="toggle radioButton" value="2">Tháng có doanh số
                            cao nhất
                        </label>
                        <label class="btn  btn-default col-xs-6 col-md-3">
                            <input type="radio" name="radio" class="toggle radioButton" value="3"> Trung bình tháng
                        </label>
                        <label class="btn  btn-default col-xs-6 col-md-3">
                            <input type="radio" name="radio" class="toggle radioButton" value="4">Tổng sản lượng
                        </label>
                    </div>
                </div>


            </div>
            <div class="portlet-body">
                <div id="chartSp"></div>
            </div>
        </div>



    </div>

@endsection
@push('scripts_foot')
<script src="/js/highcharts.js"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
<script type="text/javascript" src="/js/gmaps.overlays.min.js"></script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>


<script type="text/javascript">
    $(document).ready(function () {
        {{--$('.monthPicker').datepicker({--}}
        {{--changeMonth: true,--}}
        {{--changeYear: true,--}}
        {{--showButtonPanel: true,--}}
        {{--dateFormat: 'mm-yy',--}}
        {{--onClose: function (dateText, inst) {--}}
        {{--var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();--}}
        {{--var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();--}}
        {{--$(this).datepicker('setDate', new Date(year, month, 1));--}}
        {{--}--}}
        {{--});--}}
        {{--//chart cot--}}
        {{--Highcharts.chart('container', {--}}
        {{--chart: {--}}
        {{--type: 'column',--}}
        {{--style: {--}}
        {{--fontFamily: 'serif'--}}
        {{--}--}}
        {{--},--}}
        {{--title: {--}}
        {{--text: 'Tiến độ doanh số'--}}
        {{--},--}}
        {{--subtitle: {--}}
        {{--//                text: 'Source: WorldClimate.com'--}}
        {{--},--}}
        {{--xAxis: {--}}
        {{--categories: [--}}
        {{--'Jan',--}}
        {{--'Feb',--}}
        {{--'Mar',--}}
        {{--'Apr',--}}
        {{--'May',--}}
        {{--'Jun',--}}
        {{--'Jul',--}}
        {{--'Aug',--}}
        {{--'Sep',--}}
        {{--'Oct',--}}
        {{--'Nov',--}}
        {{--'Dec'--}}
        {{--],--}}
        {{--crosshair: true--}}
        {{--},--}}
        {{--yAxis: {--}}
        {{--min: 0,--}}
        {{--title: {--}}
        {{--text: 'Doanh số '--}}
        {{--}--}}
        {{--},--}}
        {{--tooltip: {--}}
        {{--headerFormat: '<span style="font-size:10px">{point.key}</span><table>',--}}
        {{--pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +--}}
        {{--'<td style="padding:0"><b>{point.y} </b></td></tr>',--}}
        {{--footerFormat: '</table>',--}}
        {{--shared: true,--}}
        {{--useHTML: true--}}
        {{--},--}}
        {{--plotOptions: {--}}
        {{--column: {--}}
        {{--pointPadding: 0.2,--}}
        {{--borderWidth: 0--}}
        {{--},--}}
        {{--series: {--}}
        {{--dataLabels: {--}}
        {{--enabled: true,--}}
        {{--crop: false,--}}
        {{--overflow: 'none',--}}
        {{--formatter: function () {--}}
        {{--return this.point.y;--}}
        {{--}--}}
        {{--}--}}
        {{--}--}}
        {{--},--}}
        {{--series: [{--}}
        {{--name: 'DTKH',--}}
        {{--data: {{json_encode($sales_plan)}}--}}
        {{--}, {--}}
        {{--name: 'DTTT',--}}
        {{--data: {{json_encode($sales_real)}}--}}
        {{--}]--}}
        {{--});--}}
        {{--var chartSp = Highcharts.chart('chartSp', {--}}
        {{--chart: {--}}
        {{--plotBackgroundColor: null,--}}
        {{--plotBorderWidth: null,--}}
        {{--plotShadow: false,--}}
        {{--type: 'pie',--}}
        {{--style: {--}}
        {{--fontFamily: 'serif'--}}
        {{--}--}}
        {{--},--}}
        {{--title: {--}}
        {{--text: 'Biểu đổ'--}}
        {{--},--}}
        {{--tooltip: {--}}
        {{--pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'--}}
        {{--},--}}
        {{--plotOptions: {--}}
        {{--pie: {--}}
        {{--allowPointSelect: true,--}}
        {{--cursor: 'pointer',--}}
        {{--dataLabels: {--}}
        {{--enabled: true--}}
        {{--},--}}
        {{--showInLegend: true--}}
        {{--}--}}
        {{--},--}}
        {{--series: []--}}
        {{--});--}}
        {{--$.ajax({--}}
        {{--method: "post",--}}
        {{--url: "{{route('Admin::chart')}}",--}}
        {{--headers: {--}}
        {{--'X-CSRF-Token': "{{ csrf_token() }}"--}}
        {{--},--}}
        {{--data: {--}}
        {{--type: 1--}}
        {{--},--}}
        {{--dataType: 'json',--}}
        {{--success: function (data) {--}}
        {{--if (data.title) {--}}
        {{--chartSp.setTitle({--}}
        {{--text: 'Biểu đô tháng ' + data.title--}}
        {{--});--}}
        {{--}--}}
        {{--if (data.chart) {--}}
        {{--var seriesLength = chartSp.series.length;--}}
        {{--for (var i = seriesLength - 1; i > -1; i--) {--}}
        {{--//chart.series[i].remove();--}}
        {{--if (chartSp.series[i].name == document.getElementById("series_name").value)--}}
        {{--chartSp.series[i].remove();--}}
        {{--}--}}
        {{--chartSp.addSeries(--}}
        {{--{--}}
        {{--name: 'S/p',--}}
        {{--colorByPoint: true,--}}
        {{--data: data.chart--}}
        {{--}--}}
        {{--)--}}
        {{--}--}}
        {{--if (data.table) {--}}
        {{--var table = data.table;--}}
        {{--var string = '<table class="table table-striped table-bordered" cellspacing="0" width="100%">' +--}}
        {{--' <thead> <tr> <th>Sản phẩm</th> <th>Doanh số</th></tr></thead><tbody>';--}}
        {{--table.forEach(function (value) {--}}
        {{--string += '<tr>';--}}
        {{--string += '<td>';--}}
        {{--string += value.name;--}}
        {{--string += '</td>';--}}
        {{--string += '<td>';--}}
        {{--string += value.y;--}}
        {{--string += '</td>';--}}
        {{--string += '</tr>';--}}
        {{--});--}}
        {{--string += '</tbody></table>';--}}
        {{--$('#tableData').html(string);--}}
        {{--}--}}
        {{--},--}}
        {{--error: function (err) {--}}
        {{--console.log(err);--}}
        {{--alert('Lỗi, hãy thử lại sau');--}}
        {{--}--}}
        {{--});--}}
        {{--$('.radioButton').change(function () {--}}
        {{--var type = $(this).val();--}}
        {{--$.ajax({--}}
        {{--method: "post",--}}
        {{--url: "{{route('Admin::chart')}}",--}}
        {{--headers: {--}}
        {{--'X-CSRF-Token': "{{ csrf_token() }}"--}}
        {{--},--}}
        {{--data: {--}}
        {{--type: type--}}
        {{--},--}}
        {{--dataType: 'json',--}}
        {{--success: function (data) {--}}
        {{--if (data.title) {--}}
        {{--chartSp.setTitle({--}}
        {{--text: 'Biểu đô ' + data.title--}}
        {{--});--}}
        {{--}--}}
        {{--if (data.chart) {--}}
        {{--while (chartSp.series.length > 0) {--}}
        {{--chartSp.series[0].remove(false);--}}
        {{--}--}}
        {{--chartSp.redraw();--}}
        {{--chartSp.addSeries(--}}
        {{--{--}}
        {{--name: 'S/p',--}}
        {{--colorByPoint: true,--}}
        {{--data: data.chart--}}
        {{--}--}}
        {{--)--}}
        {{--}--}}
        {{--if (data.table) {--}}
        {{--$('#tableData').html('');--}}
        {{--var table = data.table;--}}
        {{--var string = '<table class="table table-striped table-bordered" cellspacing="0" width="100%">' +--}}
        {{--' <thead> <tr> <th>Sản phẩm</th> <th>Doanh số</th></tr></thead><tbody>';--}}
        {{--table.forEach(function (value) {--}}
        {{--string += '<tr>';--}}
        {{--string += '<td>';--}}
        {{--string += value.name;--}}
        {{--string += '</td>';--}}
        {{--string += '<td>';--}}
        {{--string += value.y;--}}
        {{--string += '</td>';--}}
        {{--string += '</tr>';--}}
        {{--});--}}
        {{--string += '</tbody></table>';--}}
        {{--$('#tableData').html(string);--}}
        {{--}--}}
        {{--},--}}
        {{--error: function (err) {--}}
        {{--console.log(err);--}}
        {{--}--}}
        {{--});--}}
        {{--});--}}
        //map
        var polygonArray = [];
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            zoom: 11,
            fullscreenControl: true,
            markerClusterer: function(map) {
                markerCluster = new MarkerClusterer(map, [], {
                    maxZoom: 11,
                    imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                });
//                markerCluster.onClick = function(clickedClusterIcon) {
//                    alert(1);
//                    return multiChoice(clickedClusterIcon.cluster_);
//                }
                return markerCluster;
            }
        });
        var TotalBounds = new google.maps.LatLngBounds();
                @if($locations)
                @foreach($locations as $key => $location)
                @php
                    $locat = $location['address'];
                    $border_color = '#333';
                    $background_color = '#333';
                    if($location['border_color']){
                         $border_color = $location['border_color'];
                    }
                    if($location['background_color']){
                         $background_color = $location['background_color'];
                    }
                @endphp
        var c = "{{$locat->coordinates}}";
        var coordinate = JSON.parse(c);
        if (coordinate) {
            var bounds = new google.maps.LatLngBounds();
            for (i = 0; i < coordinate.length; i++) {
                var c = coordinate[i];
                bounds.extend(new google.maps.LatLng(c[0], c[1]));
                TotalBounds.extend(new google.maps.LatLng(c[0], c[1]));
            }
            var path = coordinate;
            map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
            {{--var infoWindow{{$locat->id}} = new google.maps.InfoWindow({--}}
                    {{--content: "<p>{{$locat->name}}</p>"--}}
                    {{--});--}}
                polygon = map.drawPolygon({
                paths: path,
                strokeColor: "{{$border_color}}",
                strokeOpacity: 1,
                strokeWeight: 1,
                fillColor: "{{$background_color}}",
                fillOpacity: 0.2,
                {{--mouseover: function (clickEvent) {--}}
                {{--var position = clickEvent.latLng;--}}
                {{--infoWindow{{$locat->id}}.setPosition(position);--}}
                {{--infoWindow{{$locat->id}}.open(map.map);--}}
                {{--},--}}
                {{--mouseout: function (clickEvent) {--}}
                {{--if (infoWindow{{$locat->id}}) {--}}
                {{--infoWindow{{$locat->id}}.close();--}}
                {{--}--}}
                {{--}--}}
            });
            polygonArray["{{$key}}"] = polygon;
        }
        map.drawOverlay({
            lat: bounds.getCenter().lat(),
            lng: bounds.getCenter().lng(),
            content: '<div class="overlay">{{$locat->name}}</div>'
        });
                @endforeach
                @endif
        var markers = [];
                @foreach($agents as $agent)
        var contentString = '<div id="content">' +
                '<p id="name">' + "{{$agent->name}}" + '</p>' +
                '<p id="manager">' + '{{$agent->user->email}}' + '</p>' +
                '</div>';
        var infoWindow = new google.maps.InfoWindow({
            content: contentString
        });
        var marker = map.addMarker({
            lat: "{{$agent->lat}}",
            lng: "{{$agent->lng}}",
            title: "{{$agent->name}}",
        });
        map.drawOverlay({
            lat: "{{$agent->lat}}",
            lng: "{{$agent->lng}}",
            content: '<div class="overlay_agents">{{$agent->name}}</div>'
        });
        markers.push(marker);
        /* Change markers on zoom */
        @endforeach
        map.fitBounds(TotalBounds);
        map.panToBounds(TotalBounds);
        $('.overlay_agents').css({"display":"none"});
        map.addListener('zoom_changed', function () {
            var zoom = map.getZoom();
            if (zoom < 10) {
                $('.overlay_agents').css({"display":"none"});
                $.each(map.markers,function(){this.setMap(null)});
            } else {
                $('.overlay_agents').css({"display":"block"});
                $.each(map.markers,function(){this.setMap(map.map)});
            }
        });
//        //cluster function to do stuff
//        function multiChoice(clickedCluster)
//        {
//            //clusters markers
//            var markers = clickedCluster.getMarkers();
//            //console check
//            console.log(clickedCluster);
//            console.log(markers);
//            if (markers.length > 1)
//            {
//                //content of info window
//                var infowindow = new google.maps.InfoWindow({
//                    content: ''+
//                    '<p>'+markers.length+' = length</p>'+
//                    '<p>testing blah blah</p>',
//                    position: clickedCluster.center_
//                });
//
//                //show the window
//                infowindow.open(clickedCluster.map_);
//
//                return false;
//            }
//            return true;
//        }
        // search
        $( ".search_type" ).change(function() {
            var search_type = $(this).val();
            if (search_type == 1) {
                getListAreas();
            } else if (search_type == 2) {
                getListSaleAdmins();
            } else if (search_type == 3) {
                getListSaleMans();
            } else if (search_type == 4) {
                getListAgents();
            }
        });
        $('#geocoding_form').submit(function(e){
            e.preventDefault();
            var type_search = $("#type_search").val();
            $.ajax({
                type: "GET",
                url: "{{ route('Admin::map@dataSearch') }}",
                data: $('#geocoding_form').serialize(),
                cache: false,
                success: function(data){
                    map = new GMaps({
                        div: '#map',
                        lat: 21.0277644,
                        lng: 105.83415979999995,
                        width: "100%",
                        height: '500px',
                        zoom: 11,
                        fullscreenControl: true,
                    });
                    if (type_search == 'areas' ) {
                        showDataAreas(data);
                    }
                    if (type_search == 'sale_admins' || type_search == 'sale_mans') {
                        showDataSales(data);
                    }
                    if (type_search == 'agents') {
                        showDataAgents(data);
                    }
                }
            });
        });
        function getListAreas() {
            $("#type_search").val('areas');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.place') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::area@getListAreas')}}",
                    dataType:'json',
                    delay:500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function(data, page) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    slug: item.slug,
                                    id: item.id,
                                    coordinates:item.coordinates
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function(m) {
                        return m;
                    }
                }
            });
        }
        function showDataAreas(data) {
            var polygonArray = [];
            $.map(data.locations, function (item) {
                var c = item.coordinates;
                var coordinate = JSON.parse(c);
                var border_color = '#333';
                var background_color = '#333';
                if(data.area.border_color){
                    border_color = data.area.border_color;
                }
                if(data.area.background_color){
                    background_color = data.area.background_color;
                }
                if (coordinate) {
                    var bounds = new google.maps.LatLngBounds();
                    for (i = 0; i < coordinate.length; i++) {
                        var c = coordinate[i];
                        bounds.extend(new google.maps.LatLng(c[0], c[1]));
                    }
                    var path = coordinate;
                    map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                    var infoWindow = new google.maps.InfoWindow({
                        content: "<p>" + item.name + "</p>"
                    });
                    polygon = map.drawPolygon({
                        paths: path,
                        strokeColor: border_color,
                        strokeOpacity: 1,
                        strokeWeight: 1,
                        fillColor: background_color,
                        fillOpacity: 0.4,
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
                    polygonArray[item.id] = polygon;
                }
            });
            $.map(data.agents, function (item) {
                var contentString = '<div id="content">' +
                    '<p id="name">' + item.name + '</p>' +
                    '</div>';
                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });
                map.addMarker({
                    lat: item.lat,
                    lng: item.lng,
                    title:  item.name,
                    click: function (e) {
                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                        infoWindow.open(map.map);
                    }
                });
            });
        }
        function getListSaleAdmins() {
            $("#type_search").val('sale_admins');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getListAdmins')}}",
                    dataType:'json',
                    delay:500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function(data, page) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function(m) {
                        return m;
                    }
                }
            });
        }
        function getListSaleMans() {
            $("#type_search").val('sale_mans');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getListmans')}}",
                    dataType:'json',
                    delay:500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function(data, page) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function(m) {
                        return m;
                    }
                }
            });
        }
        function showDataSales(data) {
            var polygonArray = [];
            $.map(data.locations, function (location) {
                $.map(location, function (item) {
                    var c = item.coordinates;
                    var coordinate = JSON.parse(c);
                    var border_color = '#333';
                    var background_color = '#333';
                    if (coordinate) {
                        var bounds = new google.maps.LatLngBounds();
                        for (i = 0; i < coordinate.length; i++) {
                            var c = coordinate[i];
                            bounds.extend(new google.maps.LatLng(c[0], c[1]));
                        }
                        var path = coordinate;
                        map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                        polygon = map.drawPolygon({
                            paths: path,
                            strokeColor: border_color,
                            strokeOpacity: 1,
                            strokeWeight: 1,
                            fillColor: background_color,
                            fillOpacity: 0.4,
                        });
                        polygonArray[item.id] = polygon;
                    }
                });
            });
            $.map(data.agents, function (item) {
                var contentString = '<div id="content">' +
                    '<p id="name">' + item.name + '</p>' +
                    '</div>';
                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });
                map.addMarker({
                    lat: item.lat,
                    lng: item.lng,
                    title:  item.name,
//                    click: function (e) {
//                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
//                        infoWindow.open(map.map);
//                    }
                });
                var area = item.area;
                var user = item.user;
                map.drawOverlay({
                    lat: item.lat,
                    lng: item.lng,
                    content: '<div class=""><ul class="">' +
                    '<li>'  + user.name + '</li>' +
                    '<li>'  + area.name + '</li>' +
                    '</ul></div>'
                });
            });
        }
        function getListAgents() {
            $("#type_search").val('agents');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.agency') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getListAgents')}}",
                    dataType:'json',
                    delay:500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function(data, page) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function(m) {
                        return m;
                    }
                }
            });
        }
        function showDataAgents(data) {
            var polygonArray = [];
            $.map(data.locations, function (item) {
                var c = item.coordinates;
                var coordinate = JSON.parse(c);
                var border_color = '#333';
                var background_color = '#333';
                if(data.area.border_color){
                    border_color = data.area.border_color;
                }
                if(data.area.background_color){
                    background_color = data.area.background_color;
                }
                if (coordinate) {
                    var bounds = new google.maps.LatLngBounds();
                    for (i = 0; i < coordinate.length; i++) {
                        var c = coordinate[i];
                        bounds.extend(new google.maps.LatLng(c[0], c[1]));
                    }
                    var path = coordinate;
                    map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                    polygon = map.drawPolygon({
                        paths: path,
                        strokeColor: border_color,
                        strokeOpacity: 1,
                        strokeWeight: 1,
                        fillColor: background_color,
                        fillOpacity: 0.4,
                    });
                    polygonArray[item.id] = polygon;
                }
            });
            var contentString = '<div id="content">' +
                '<p id="name">' + data.agents.name + '</p>' +
                '</div>';
            var infoWindow = new google.maps.InfoWindow({
                content: contentString
            });
            map.addMarker({
                lat:  data.agents.lat,
                lng:  data.agents.lng,
                title:   data.agents.name,
            });
            var area = data.area;
            var user = data.user;
            map.drawOverlay({
                lat: data.agents.lat,
                lng: data.agents.lng,
                content: '<div class="info">' +
                '<h5>' + data.agents.name + '</h5>' +
                '<div class="user_data">' +
                '<p class="data">%TT 100/10000 = 18%</p>' +
                '<ul class="info_user">' +
                '<li>'  + user.name + '</li>' +
                '<li>'  + area.name + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>'
            });
        }
    });
</script>
@endpush
