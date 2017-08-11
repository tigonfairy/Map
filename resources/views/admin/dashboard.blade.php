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
            color: #0a0a0a;
        }
        .data {
            z-index: 99999;
            border: 1px solid yellow;
            background-color: yellow;
            color: red;
            font-size: 12px;
            float: left;
        }
        .info_user {
            z-index: 99999;
            list-style: none;
            font-size: 14px;
            /*margin-left: 10px;*/
            float: left;
        }

        .info_gsv{
            z-index: 99999;
            color: #0a0a0a;
        }
        .data_gsv {
            z-index: 99999;
            border: 1px solid yellow;
            background-color: yellow;
            color: red;
            font-size: 12px;
            float: left;
        }
        .info_user_gsv {
            z-index: 99999;
            list-style: none;
            font-size: 14px;
            /*margin-left: 10px;*/
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
                            <option value="1">Theo đại lý</option>
                            <option value="2">Theo giám sát vùng </option>
                            <option value="3">Theo trưởng vùng </option>
                            <option value="4">Theo giám đốc vùng</option>
                        </select>
                    </div>
                    <input type="hidden" name="type_search" value="" id="type_search"/>
                    <div class="col-md-2">
                        <select name="data_search" class="data_search form-control" id="locations" style="width:100%">
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="text" id="month" name="month" class="form-control monthPicker col-md-9"
                               value="{{ old('month') ?: $month }}"/>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                    </div>
                </div>
            </form>

            {{--<div class="row">--}}
                {{--<div class="form-group {{ $errors->has('month') ? 'has-error has-feedback' : '' }}">--}}
                    {{--<label for="name" class="control-label text-semibold col-md-1">{{ trans('home.time') }}</label>--}}
                    {{--<i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"--}}
                       {{--data-content="Thời gian"></i>--}}
                    {{--<div class="col-md-3">--}}
                        {{--<input type="text" id="month" name="month" class="form-control monthPicker col-md-9"--}}
                               {{--value="{{ old('month') ?: $month }}"/>--}}
                    {{--</div>--}}
                    {{--@if ($errors->has('month'))--}}
                        {{--<div class="form-control-feedback">--}}
                            {{--<i class="icon-notification2"></i>--}}
                        {{--</div>--}}
                        {{--<div class="help-block">{{ $errors->first('month') }}</div>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}

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

        $('.monthPicker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'mm-yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            }
        });

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
        {{--var contentString = '<div id="content">' +--}}
                {{--'<p id="name">' + "{{$agent->name}}" + '</p>' +--}}
                {{--'<p id="manager">' + '{{$agent->user->email}}' + '</p>' +--}}
                {{--'</div>';--}}
//        var infoWindow = new google.maps.InfoWindow({
//            content: contentString
//        });
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
                getListAgents();
            } else if (search_type == 2) {
                getListGSV();
            } else if (search_type == 3) {
                getListTV();
            } else if (search_type == 4) {
                getListGDV();
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

                    if (type_search == 'agents') {
                        showDataAgents(data);
                    }
                    if (type_search == 'gsv') {
                        showDataSales(data);
                    }
                    if (type_search == 'tv') {

                    }
                    if (type_search == 'gdv' ) {
                        showDataAreas(data);
                    }
                }
            });
        });

        var listSelectProducts = [];

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

        function getListGSV() {
            $("#type_search").val('gsv');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getGSV')}}",
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
            var area_name = '';
            var polygonArray = [];
            $.map(data.locations, function (location, index) {

                    var item = location.area;
                    var c = item.coordinates;
                    var coordinate = JSON.parse(c);
                    var border_color = location.border_color;
                    var background_color = location.background_color;
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
                    area_name += item.name;
                    if (index < data.locations.length - 1) {
                        area_name += '-';
                    }
            });

            $.map(data.listAgents, function (item) {
                var agent = item.agent;
                var user = agent.user;

                var contentString = '<div class="info">' +
                    '<h5>' + agent.address + '</h5>' +
                    '<div class="user_data">' +
                    '<p class="data">%TT ' + item.totalSales + '/' + item.capacity + '=' +  item.percent + '%</p>' +
                    '<ul class="info_user">' +
                    '<li> NVKD:'  + user.name + '</li>' +
                    '<li> GS :'  + data.user.name + '</li>' +
                    '<li> GĐ :'  + data.director + '</li>' +
                    '</ul>' +
                    '</div>' +
                    '</div>';

                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });

                map.addMarker({
                    lat:  agent.lat,
                    lng:  agent.lng,
                    title:   agent.name,
                    infoWindow : infoWindow,
                    click: function (e) {
                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                        infoWindow.open(map.map);
                    }
                });

                map.drawOverlay({
                    lat: agent.lat,
                    lng: agent.lng,
                    content: '<div class="info">' +
                                '<h5>' + agent.name + '</h5>' +
                            '</div>'
                });
           });

            var tableSales =
                '<div class="info_gsv">' +
                '<h3>' + area_name + '</h3>' +
                '<div class="user_data_gsv">' +
                '<p class="data_gsv">%TT ' + data.totalSales + '/' + data.capacity + '=' +  data.percent + '%</p>' +
                '<ul class="info_user_gsv">' +
                '<li> GS :'  + data.user.name + '</li>' +
                '<li> GĐ :'  + data.director + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';

            map.addControl({
                position: 'top_left',
                content: tableSales,
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
            listSelectProdcuts = [];
            map = new GMaps({
                div: '#map',
                lat:  data.agents.lat,
                lng: data.agents.lng,
                width: "100%",
                height: '500px',
                zoom: 13,
                fullscreenControl: true,
            });

            var user = data.user;
            var list_products = data.listProducts;

            // info cho 1 marker
            var contentString = '<div class="info">' +
                '<h5>' + data.agents.address + '</h5>' +
                '<div class="user_data">' +
                '<p class="data" id="data">%'+ list_products[0].code + ' ' + list_products[0].totalSales +'/'+ list_products[0].capacity +  '=' + list_products[0].percent + '%</p>' +
                '<ul class="info_user">' +
                '<li>'  + user.name + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';

            var infoWindow = new google.maps.InfoWindow({
                content: contentString
            });

            map.addMarker({
                lat:  data.agents.lat,
                lng:  data.agents.lng,
                title:   data.agents.name,
                infoWindow : infoWindow,
                click: function (e) {
                    infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                    infoWindow.open(map.map);
                }
            });

            map.drawOverlay({
                lat: data.agents.lat,
                lng: data.agents.lng,
                content: '<div class="info">' +
                '<h5>' + data.agents.name + '</h5>' +
                '</div>'
            });



            var tableSales = '<table class="table table-striped table-bordered table-products" cellspacing="0" width="100%" id="data-table">' +
                '<thead>' +
                '<tr>' +
                '<th>Sản phẩm</th>' +
                '<th>Mã</th>' +
                '<th>Sản lượng</th>'+
                '<th>Dung lượng</th>'+
                '</tr>' +
                '</thead>'+
                '<tbody>' +
                    '<tr>' +
                    '<td>' +
                    '<select id="choose_product">';
                        $.map(list_products, function (product) {
                            tableSales += '<option value="' + product.code + '">' + product.code + '</option>'
                        });

                tableSales += '</select>' +
                    '</td>' +
                    '<td id="code">' + list_products[0].code +'</td>'+
                    '<td id="totalSales">' + list_products[0].totalSales +'</td>'+
                    '<td id="capacity">' + list_products[0].capacity +'</td>' +
                    '</tr>' +
                '</tbody>' +
                    '</table>';

            map.addControl({
                position: 'top_left',
                content: tableSales,
            });

            $.each(list_products, function( index, value ) {
                listSelectProducts.push(value);
            });
        }

        $(document).on('change', '#choose_product', function() {
            var code = $(this).val();

            var data = $.grep(listSelectProducts, function(e){
                return e.code == code;
            });

            var item = data[0];
            $("#code").text(item.code);
            $("#totalSales").text(item.totalSales);
            $("#capacity").text(item.capacity);
            $("#data").text('%'+ item.code + ' ' + item.totalSales +'/'+ item.capacity +  '=' + item.percent + '%');
        });
    });


</script>
@endpush
