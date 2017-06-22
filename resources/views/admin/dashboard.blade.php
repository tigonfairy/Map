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


    </style>
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> Admin Dashboard</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
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
                            <input type="radio" name="radio" class="toggle radioButton" value="2">Tháng có doanh số cao nhất
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

        <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-directions font-green hide"></i>
                        <span class="caption-subject bold font-dark uppercase"> Bản đồ tổng quan</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group">
                            <a class="btn blue btn-outline btn-circle btn-sm" href="javascript:;" data-toggle="dropdown"
                               data-hover="dropdown" data-close-others="true"> Actions
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;"> Action 1</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:;">Action 2</a>
                                </li>
                                <li>
                                    <a href="javascript:;">Action 3</a>
                                </li>
                                <li>
                                    <a href="javascript:;">Action 4</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="portlet-body">
                    <div id="map" style=" width: 100% ;height: 400px"></div>
                </div>

            </div>

    </div>

@endsection
@push('scripts_foot')
<script src="/js/highcharts.js"></script>
<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
<script type="text/javascript" src="/js/gmaps.overlays.min.js"></script>


<script type="text/javascript">
    $(document).ready(function () {

        $('.monthPicker').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'mm-yy',
            onClose: function (dateText, inst) {
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
        var polygonArray = [];
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            zoom: 11
        });
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
            }
            var path = coordinate;
            map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
            var infoWindow{{$locat->id}} = new google.maps.InfoWindow({
                content: "<p>{{$locat->name}}</p>"
            });
            polygon = map.drawPolygon({
                paths: path,
                strokeColor: "{{$border_color}}",
                strokeOpacity: 1,
                strokeWeight: 1,
                fillColor: "{{$background_color}}",
                fillOpacity: 0.4,
                mouseover: function (clickEvent) {
                    var position = clickEvent.latLng;
                    infoWindow{{$locat->id}}.setPosition(position);
                    infoWindow{{$locat->id}}.open(map.map);
                },
                mouseout: function (clickEvent) {
                    if (infoWindow{{$locat->id}}) {
                        infoWindow{{$locat->id}}.close();
                    }
                }
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

                @foreach($agents as $agent)
        var contentString = '<div id="content">' +
                        '<p id="name">' + "{{$agent->name}}" + '</p>' +
                        '<p id="manager">' + '{{$agent->user->email}}' + '</p>' +

                        '</div>';
        var infoWindow = new google.maps.InfoWindow({
            content: contentString
        });

        map.addMarker({
            lat: "{{$agent->lat}}",
            lng: "{{$agent->lng}}",
            visible: true,
            title: "{{$agent->name}}",
            click: function (e) {
                infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                infoWindow.open(map.map);
            }
        });

        @endforeach

    });
</script>
@endpush


