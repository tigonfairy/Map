@extends('admin')
@section('content')
<?php
    if (file_exists(public_path().'/config/config.json')) {
        $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);

    }
?>

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
            color: <?php echo $config['textColor']  ?>;
        }
        .data {
            z-index: 99999;
            border: 1px solid yellow;
            background-color: yellow;
            color: <?php echo $config['textColor']  ?>;
            font-size: <?php echo $config['fontSize'] . 'px' ?>;
            float: left;
        }
        .info_user {
            z-index: 99999;
            list-style: none;
            font-size: <?php echo $config['fontSize'] . 'px' ?>;
            /*margin-left: 10px;*/
            float: left;
        }
        .info_gsv{
            z-index: 99999;
            color: <?php echo $config['textColor']  ?>;
        }
        .data_gsv {
            z-index: 99999;
            border: 1px solid yellow;
            background-color: yellow;
            color: <?php echo $config['textColor']?>;
            font-size: <?php echo $config['fontSize'] . 'px' ?>;
            float: left;
        }
        .info_user_gsv {
            z-index: 99999;
            list-style: none;
            font-size: <?php echo $config['fontSize'] . 'px' ?>;
            /*margin-left: 10px;*/
            float: left;
        }
        .customBox {
            z-index: 99999;
            position: absolute;
            font-size: <?php echo $config['fontSize'] . 'px' ?>;
            background-color: #ffffff;
            margin-left: 10px;

        }
        /*.customBox .gsv {*/
        /*background-color: #00aaaa;*/
        /*}*/
        /*.customBox .ds {*/
        /*color: red;*/
        /*}*/
    </style>

    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> Admin Dashboard</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->

    <div class="row">
        <div class="portlet light ">

            <form method="post" id="geocoding_form">
                <input type="hidden" name="type_search" value="" id="type_search"/>
                <div class="row">
                    @if($user->position != \App\Models\User::NVKD)
                        <div class="col-md-2">
                                <select class="search_type form-control">
                                    <option value="">-- Chọn loại {{ trans('home.search') }} --</option>
                                    <option value="1">Theo đại lý</option>
                                    @if($user->position != \App\Models\User::GSV)
                                        <option value="2">Theo giám sát vùng </option>
                                    @endif
                                    @if($user->position != \App\Models\User::TV && $user->position != \App\Models\User::GSV)
                                        <option value="3">Theo trưởng vùng </option>
                                    @endif
                                    @if(($user->position != \App\Models\User::GĐV && $user->position != \App\Models\User::GSV && $user->position != \App\Models\User::TV))
                                        <option value="4">Theo giám đốc vùng</option>
                                    @endif
                                </select>

                        </div>

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
                    @else

                        <div class="col-md-3">
                            <input type="text" id="month" name="month" class="form-control monthPicker col-md-9"
                                   value="{{ old('month') ?: $month }}"/>
                        </div>

                        <div class="col-md-9">
                            <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                        </div>

                    @endif
                </div>
            </form>

            <div class="portlet-body">
                <div id="map" style=" width: 100% ;height: 500px"></div>
            </div>
        </div>
    </div>

    <div class="row ">

        <div class="col-md-6">
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
        </div>


        <div class="col-md-6">
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

    </div>

    <div class="row ">
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
    </div>

@endsection
@push('scripts_foot')
<script src="/js/highcharts.js"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
<script type="text/javascript" src="/js/gmaps.overlays.min.js"></script>
<script type="text/javascript" src="/js/maplabel-compiled.js"></script>
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

        //chart cot
        Highcharts.chart('container', {
            chart: {
                type: 'column',
                style: {
                    fontFamily: 'serif'
                }
            },
            title: {
                text: 'Tiến độ doanh số'
            },
            subtitle: {
                //                text: 'Source: WorldClimate.com'
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Doanh số '
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series: {
                    dataLabels: {
                        enabled: true,
                        crop: false,
                        overflow: 'none',
                        formatter: function () {
                            return this.point.y;
                        }
                    }
                }
            },
            series: [{
                name: 'DTKH',
                data: {{json_encode($sales_plan)}}
            }, {
                name: 'DTTT',
                data: {{json_encode($sales_real)}}
            }]
        });
        var chartSp = Highcharts.chart('chartSp', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                style: {
                    fontFamily: 'serif'
                }
            },
            title: {
                text: 'Biểu đổ'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true
                }
            },
            series: []
        });
        $.ajax({
            method: "post",
            url: "{{route('Admin::chart')}}",
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                type: 1
            },
            dataType: 'json',
            success: function (data) {
                if (data.title) {
                    chartSp.setTitle({
                        text: 'Biểu đô tháng ' + data.title
                    });
                }
                if (data.chart) {
                    var seriesLength = chartSp.series.length;
                    for (var i = seriesLength - 1; i > -1; i--) {
                        //chart.series[i].remove();
                        if (chartSp.series[i].name == document.getElementById("series_name").value)
                            chartSp.series[i].remove();
                    }
                    chartSp.addSeries(
                        {
                            name: 'S/p',
                            colorByPoint: true,
                            data: data.chart
                        }
                    )
                }
                if (data.table) {
                    var table = data.table;
                    var string = '<table class="table table-striped table-bordered" cellspacing="0" width="100%">' +
                        ' <thead> <tr> <th>Sản phẩm</th> <th>Doanh số</th></tr></thead><tbody>';
                    table.forEach(function (value) {
                        string += '<tr>';
                        string += '<td>';
                        string += value.name;
                        string += '</td>';
                        string += '<td>';
                        string += value.y;
                        string += '</td>';
                        string += '</tr>';
                    });
                    string += '</tbody></table>';
                    $('#tableData').html(string);
                }
            },
            error: function (err) {
                console.log(err);
                alert('Lỗi, hãy thử lại sau');
            }
        });
        $('.radioButton').change(function () {
            var type = $(this).val();
            $.ajax({
                method: "post",
                url: "{{route('Admin::chart')}}",
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: {
                    type: type
                },
                dataType: 'json',
                success: function (data) {
                    if (data.title) {
                        chartSp.setTitle({
                            text: 'Biểu đô ' + data.title
                        });
                    }
                    if (data.chart) {
                        while (chartSp.series.length > 0) {
                            chartSp.series[0].remove(false);
                        }
                        chartSp.redraw();
                        chartSp.addSeries(
                            {
                                name: 'S/p',
                                colorByPoint: true,
                                data: data.chart
                            }
                        )
                    }
                    if (data.table) {
                        $('#tableData').html('');
                        var table = data.table;
                        var string = '<table class="table table-striped table-bordered" cellspacing="0" width="100%">' +
                            ' <thead> <tr> <th>Sản phẩm</th> <th>Doanh số</th></tr></thead><tbody>';
                        table.forEach(function (value) {
                            string += '<tr>';
                            string += '<td>';
                            string += value.name;
                            string += '</td>';
                            string += '<td>';
                            string += value.y;
                            string += '</td>';
                            string += '</tr>';
                        });
                        string += '</tbody></table>';
                        $('#tableData').html(string);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });

        //map
        var type_search = '';

        @if ($user->position == \App\Models\User::NVKD)
            type_search = 'nvkd';
        @elseif($user->position == \App\Models\User::GSV)
            type_search = 'gsv';
        @elseif($user->position == \App\Models\User::TV)
            type_search = 'tv';
        @elseif($user->position == \App\Models\User::GĐV)
            type_search = 'gdv';
        @else
            type_search = 'admin';
        @endif

        $.ajax({
            type: "GET",
            url: "{{ route('Admin::map@dataSearch') }}",
            data: {
                type_search : type_search,
                data_search : '{{  $user->id }}',
                month : '{{ $month }}',
            },
            cache: false,
            success: function(data){

                map = new GMaps({
                    div: '#map',
                    lat: 21.0277644,
                    lng: 105.83415979999995,
                    width: "100%",
                    height: '500px',
                    zoom: 8,
                    fullscreenControl: true,
                });

                if (type_search == 'nvkd') {
                    showDataAgents(data);
                }
                if (type_search == 'gsv') {
                    showDataSales(data);
                }
                if (type_search == 'tv') {
                    showDataSales(data);
                }
                if (type_search == 'gdv' ) {
                    showDataSaleGDV(data);
                }
                if (type_search == 'admin' ) {
                    showDataSaleAdmin(data);
                }
            }
        });



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
                        zoom: 7,
                        fullscreenControl: true,
                    });
                    var button ='<button id="swift" class="btn btn-primary">Full mode</button>';
                    map.addControl({
                        position: 'bottom_left',
                        content: button,
                    });

                    if (type_search == 'agents' || type_search === undefined || type_search == null || type_search.length <= 0) {

                       showDataAgents(data);
                    }
                    if (type_search == 'gsv') {
                        showDataSales(data);
                    }
                    if (type_search == 'tv') {
                        showDataSales(data);
                    }
                    if (type_search == 'gdv' ) {
                        showDataSaleGDV(data);
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
        function getListTV() {
            $("#type_search").val('tv');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getListTV')}}",
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
//                    map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
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
            var postion = '';
            if (data.user.position == 3) {
                postion = 'TV';
            } else {
                postion = 'GS';
            }

            $.map(data.listAgents, function (item) {
                var agent = item.agent;
                var user = agent.user;
                var contentString = '<div class="info">' +
                    '<h5 class="address" style="display:none">' + agent.address + '</h5>' +
                    '<div class="user_data">' +
                    '<p class="data">%TT ' + item.totalSales + '/' + item.capacity + '=' +  item.percent + '%</p>' +
                    '<ul class="info_user">' +
                    '<li> NVKD:'  + user.name + '</li>' +
                    '<li class="gsv" style="display: none">' + postion + ':'  + data.user.name + '</li>' +
                    '<li class="gdv" style="display: none"> GĐ :'  + data.director + '</li>' +
                    '</ul>' +
                    '</div>' +
                    '</div>';
                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });
                var marker = map.addMarker({
                    lat:  agent.lat,
                    lng:  agent.lng,
                    title:   agent.name,
                    infoWindow : infoWindow,
                    click: function (e) {
                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                        infoWindow.open(map.map);
                    }
                });
            });

            var tableSales =
                '<div class="info_gsv">' +
                '<h3>' + area_name + '</h3>' +
                '<div class="user_data_gsv">' +
                '<p class="data_gsv">%TT ' + data.totalSales + '/' + data.capacity + '=' +  data.percent + '%</p>' +
                '<ul class="info_user_gsv">' +
                '<li>' + postion + ':'  + data.user.name + '</li>' +
                '<li class="gdv" style="display: none"> GĐ :'  + data.director + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
            map.addControl({
                position: 'top_left',
                content: tableSales,
            });
        }
        function getListGDV() {
            $("#type_search").val('gdv');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getListGDV')}}",
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
        function showDataSaleGDV(data) {

            var polygonArray = [];
            var position = '';
            var center = new google.maps.LatLng(21.0277644, 105.83415979999995);
            var options = {
                'zoom': 8,
                'center': center,
                'mapTypeId': google.maps.MapTypeId.ROADMAP,
                fullscreenControl: true,
            };
            var map = new google.maps.Map(document.getElementById("map"), options);
            $.map(data.locations, function (location, index) {
                var item = location.area;
                var c = item.coordinates;
                var coordinate = JSON.parse(c);
                var border_color = location.border_color;
                var background_color = location.background_color;
                if (coordinate) {
                    var bounds = new google.maps.LatLngBounds();
                    var path = [];
                    for (i = 0; i < coordinate.length; i++) {
                        var c = coordinate[i];
                        bounds.extend(new google.maps.LatLng(c[0], c[1]));
                        path.push(new google.maps.LatLng(c[0], c[1]))
                    }
                    position = new google.maps.LatLng(bounds.getCenter().lat(), bounds.getCenter().lng());
//                    var path = coordinate;
//                    map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                    polygon = new google.maps.Polygon({
                        paths: path,
                        strokeColor: border_color,
                        strokeOpacity: 1,
                        strokeWeight: 1,
                        fillColor: background_color,
                        fillOpacity: 0.4,
                    });
                    polygon.setMap(map);
                    polygonArray[item.id] = polygon;
                }
            });
            $.map(data.result, function (item) {
                var agents = item.agents;
                var markers = [];
                $.map(agents, function (agent) {
                    var latLng = new google.maps.LatLng(agent.lat,
                        agent.lng);
                    var marker = new google.maps.Marker({'position': latLng});
                    markers.push(marker);
                });
                var markerCluster = new MarkerClusterer(map, markers, {
                    maxZoom: 15,
                    imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                });

                var customTxt =
                    '<div class="customBox">' +
                        '<p class="data_gsv">%TT ' + item.totalSales + '/' + item.capacity + '=' +  item.percent + '%</p>' +
                    '<ul class="info_user_gsv">' +
                        '<li>' + item.gsv + '</li>' +
                    '</ul>' +
                    '</div>';
                txt = new TxtOverlay(new google.maps.LatLng(markers[0].getPosition().lat(),  markers[0].getPosition().lng()), customTxt, "customBox", map);
            });

            var myTitle = document.createElement('h3');
            myTitle.style.color = 'red';
            myTitle.innerHTML = data.user.name + ' - %TT ' + data.totalSales + '/' + data.capacity + '=' +  data.percent  + "%";
            var myTextDiv = document.createElement('div');
            myTextDiv.appendChild(myTitle);
            map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(myTextDiv);
        }
        function TxtOverlay(pos, txt, cls, map) {
            // Now initialize all properties.
            this.pos = pos;
            this.txt_ = txt;
            this.cls_ = cls;
            this.map_ = map;
            // We define a property to hold the image's
            // div. We'll actually create this div
            // upon receipt of the add() method so we'll
            // leave it null for now.
            this.div_ = null;
            // Explicitly call setMap() on this overlay
            this.setMap(map);
        }
        TxtOverlay.prototype = new google.maps.OverlayView();
        TxtOverlay.prototype.onAdd = function() {
            // Note: an overlay's receipt of onAdd() indicates that
            // the map's panes are now available for attaching
            // the overlay to the map via the DOM.
            // Create the DIV and set some basic attributes.
            var div = document.createElement('DIV');
            div.className = this.cls_;
            div.innerHTML = this.txt_;
            // Set the overlay's div_ property to this DIV
            this.div_ = div;
            var overlayProjection = this.getProjection();
            var position = overlayProjection.fromLatLngToDivPixel(this.pos);
            div.style.left = position.x + 'px';
            div.style.top = position.y + 'px';
            // We add an overlay to a map via one of the map's panes.
            var panes = this.getPanes();
            panes.floatPane.appendChild(div);
        }
        TxtOverlay.prototype.draw = function() {
            var overlayProjection = this.getProjection();
            // Retrieve the southwest and northeast coordinates of this overlay
            // in latlngs and convert them to pixels coordinates.
            // We'll use these coordinates to resize the DIV.
            var position = overlayProjection.fromLatLngToDivPixel(this.pos);
            var div = this.div_;
            div.style.left = position.x + 'px';
            div.style.top = position.y + 'px';
        }
        //Optional: helper methods for removing and toggling the text overlay.
        TxtOverlay.prototype.onRemove = function() {
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        }
        TxtOverlay.prototype.hide = function() {
            if (this.div_) {
                this.div_.style.visibility = "hidden";
            }
        }
        TxtOverlay.prototype.show = function() {
            if (this.div_) {
                this.div_.style.visibility = "visible";
            }
        }
        TxtOverlay.prototype.toggle = function() {
            if (this.div_) {
                if (this.div_.style.visibility == "hidden") {
                    this.show();
                } else {
                    this.hide();
                }
            }
        }
        TxtOverlay.prototype.toggleDOM = function() {
            if (this.getMap()) {
                this.setMap(null);
            } else {
                this.setMap(this.map_);
            }
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
            var postion = '';
            if (data.gsv.position == 3) {
                postion = 'TV';
            } else {
                postion = 'GS';
            }
            // info cho 1 marker
            var contentString = '<div class="info">' +
                '<h5 class="address" style="display:none">' + data.agents.address + '</h5>' +
                '<div class="user_data">' +
                '<p class="data" id="data">%'+ list_products[0].code + ' ' + list_products[0].totalSales +'/'+ list_products[0].capacity +  '=' + list_products[0].percent + '%</p>' +
                '<ul class="info_user">' +
                '<li>'  + user.name + '</li>' +
                '<li class="gsv" style="display:none">' + postion + ':'  + data.gsv.name + '</li>' +
                '<li class="gdv" style="display:none"> GĐ :'  + data.gdv.name + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
            var infoWindow = new google.maps.InfoWindow({
                content: contentString
            });
            var myMarker = map.addMarker({
                lat:  data.agents.lat,
                lng:  data.agents.lng,
                title:   data.agents.name,
                infoWindow : infoWindow,
                click: function (e) {
                    infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                    infoWindow.open(map.map);
                }
            });
            infoWindow.setPosition(myMarker.position);
            infoWindow.open(map.map);
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
                '<th>Tên Sản phẩm</th>' +
                '<th>Mã Sản phẩm</th>' +
                '<th>Sản lượng</th>'+
                '<th>Dung lượng</th>'+
                '</tr>' +
                '</thead>'+
                '<tbody>' +
                '<tr>' +
                '<td>' +
                '<select id="choose_product">';
            $.map(list_products, function (product) {
                tableSales += '<option value="' + product.code + '">' + product.name + '</option>'
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
            var button ='<button id="swift" class="btn btn-primary">Full mode</button>';
            map.addControl({
                position: 'bottom_left',
                content: button,
            });
            $.each(list_products, function( index, value ) {
                listSelectProducts.push(value);
            });
        }

        function showDataSaleAdmin(data) {

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
//                    map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
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

            $.map(data.agents, function (item) {

                var image =  "";

                if (item.icon != "") {
//                    icon = host + '/' +item.icon;
                    var url = "http://" + window.location.hostname + "/" + item.icon;
                    console.log(url);
                    image = {
                        url: url,
                        // This marker is 20 pixels wide by 32 pixels high.
                        size: new google.maps.Size(20, 32),
                        // The origin for this image is (0, 0).
                        origin: new google.maps.Point(0, 0),
                        // The anchor for this image is the base of the flagpole at (0, 32).
                        anchor: new google.maps.Point(0, 0)
                    };
                    console.log(image);
                }



                var marker = map.addMarker({
                    lat:  item.lat,
                    lng:  item.lng,
                    title:   item.name,
                    icon : image,
//                    infoWindow : infoWindow,
//                    click: function (e) {
//                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
//                        infoWindow.open(map.map);
//                    }
                });
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
        $(document).on('click', '#swift', function() {
            var text = $(this).text();
            if (text == 'Full Mode') {
                $(this).text('Compact Mode');
                $('.gsv').show();
                $('.gdv').show();
                $('.address').show();
            }
            else {
                $(this).text('Full Mode');
                $('.gsv').hide();
                $('.gdv').hide();
                $('.address').hide();
            }
        });
    });
</script>
@endpush