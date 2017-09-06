@extends('admin')
@section('content')
    <?php
    if (file_exists(public_path() . '/config/config.json')) {
        $config = json_decode(file_get_contents(public_path() . '/config/config.json'), true);

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
            color: {{ (auth()->user()->textColor) ? auth()->user()->textColor : $config['textColor']  }};
        }

        .data {
            z-index: 99999;
            border: 1px solid yellow;
            background-color: yellow;
            color: {{ (auth()->user()->textColor) ? auth()->user()->textColor : $config['textColor']  }};
            font-size: {{ (auth()->user()->fontSize) ? auth()->user()->fontSize :  $config['fontSize']  }}px;
            float: left;
        }

        .info_user {
            z-index: 99999;
            list-style: none;
            font-size: {{ (auth()->user()->fontSize) ? auth()->user()->fontSize :  $config['fontSize']  }}px;
            color: {{ (auth()->user()->textColor) ? auth()->user()->textColor : $config['textColor']  }};
            /*margin-left: 10px;*/
            float: left;
        }

        .info_gsv {
            z-index: 99999;
            color: {{ (auth()->user()->textColor) ? auth()->user()->textColor : $config['textColor']  }};
        }

        .data_gsv {
            z-index: 99999;
            border: 1px solid yellow;
            background-color: yellow;
            color: {{ (auth()->user()->textColor) ? auth()->user()->textColor : $config['textColor']  }};
            font-size: {{ (auth()->user()->fontSize) ? auth()->user()->fontSize :  $config['fontSize']  }}px;
            float: left;
        }

        .info_user_gsv {
            z-index: 99999;
            list-style: none;
            font-size: {{ (auth()->user()->fontSize) ? auth()->user()->fontSize :  $config['fontSize']  }}px;
            /*margin-left: 10px;*/
            float: left;
        }

        .customBox {
            position: absolute;
            background-color: #ffffff;
            margin-left: 10px;
            margin-top: 5px;
            vertical-align: middle;
            width: 35%;
            text-align: center;

        }

        #legend {
            font-family: Arial, sans-serif;
            background: #fff;
            padding: 10px;
            margin: 10px;
        }

        #legend h3 {
            margin-top: 0;
        }

        #legend img {
            vertical-align: middle;
        }

        #legend2 {
            font-family: Arial, sans-serif;
            background: #fff;
            padding: 10px;
            margin: 10px;
        }

        #legend2 h3 {
            margin-top: 0;
        }

        #legend2 img {
            vertical-align: middle;
        }

    </style>

    <div class="row">
        <div class="portlet light ">
            <div class="row">
                <form method="post" id="geocoding_form">
                    <input type="hidden" name="type_search" value="" id="type_search"/>

                    @if($user->position != \App\Models\User::NVKD)
                        <div class="col-md-2">
                            <select class="search_type form-control" name="type_data_search">
                                <option value="">-- Chọn loại {{ trans('home.search') }} --</option>
                                <option value="1">Theo đại lý</option>
                                <option value="5">Theo nhân viên kinh doanh</option>
                                @if($user->position != \App\Models\User::GSV)
                                    <option value="2">Theo giám sát vùng</option>
                                @endif
                                @if($user->position != \App\Models\User::TV && $user->position != \App\Models\User::GSV)
                                    <option value="3">Theo trưởng vùng</option>
                                @endif
                                @if(($user->position != \App\Models\User::GĐV && $user->position != \App\Models\User::GSV && $user->position != \App\Models\User::TV))
                                    <option value="4">Theo giám đốc vùng</option>
                                @endif
                            </select>
                            <p id="type_date_search" style="color:red;"></p>
                        </div>

                        <div class="col-md-2">
                            <select name="data_search" id="select_data_search" class="data_search form-control" id="locations"
                                    style="width:100%">
                            </select>
                            <p id="date_search" style="color:red;"></p>
                        </div>



                            <div class="col-md-2">
                                <input type="text" name="startMonth"  class="form-control startMonth" value="{{ old('startMonth') ?: $month }}" placeholder="Thời gian bắt đầu"/>
                                <p id="startMonth" style="color:red;"></p>
                            </div>


                            <div class="col-md-2">
                                <input type="text" name="endMonth"  class="form-control endMonth" value="{{ old('endMonth') ?: $month }}" placeholder="Thời gian kết  thúc"/>
                                <p id="endMonth" style="color:red;"></p>
                            </div>


                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                        </div>
                    @else

                        {{--<div class="col-md-3">--}}



                            <div class="col-md-2">
                                <input type="text" name="startMonth"  class="form-control startMonth" value="{{ old('startMonth') ?: $month }}" placeholder="Thời gian bắt đầu"/>
                                <p id="startMonth" style="color:red;"></p>
                            </div>


                            <div class="col-md-2">
                                <input type="text" name="endMonth"  class="form-control endMonth" value="{{ old('endMonth') ?: $month }}" placeholder="Thời gian kết  thúc"/>
                                <p id="endMonth" style="color:red;"></p>
                            </div>



                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                        </div>

                    @endif

                    <div class="col-md-2">
                        <a href="#" class="btn btn-info" id="exportDashboard" >Export excel</a>
                    </div>
                </form>

                <div class="clearfix"></div>
            </div>



            {{--<div class="modal fade bs-modal-lg" id="export-product" tabindex="-1" role="dialog" aria-hidden="true">--}}
                {{--<div class="modal-dialog modal-lg">--}}
                    {{--<div class="modal-content">--}}
                        {{--<div class="modal-header">--}}
                            {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>--}}
                            {{--<h4 class="modal-title">Export Excel</h4>--}}
                        {{--</div>--}}
                        {{--<form method="POST" action="{{ route('Admin::export') }}"--}}
                              {{--enctype="multipart/form-data" id="import_form">--}}
                            {{--{{ csrf_field() }}--}}
                            {{--<div class="modal-body">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-md-10" style="margin-bottom:10px">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label class="control-label col-md-3">Loại</label>--}}
                                            {{--<div class="col-md-8">--}}

                                                {{--<select name="type" class="typeExport form-control">--}}
                                                    {{--<option value="">-- Chọn loại {{ trans('home.search') }} --</option>--}}
                                                    {{--<option value="1">Theo đại lý</option>--}}
                                                    {{--<option value="5">Theo nhân viên kinh doanh</option>--}}
                                                    {{--@if($user->position != \App\Models\User::GSV)--}}
                                                        {{--<option value="2">Theo giám sát vùng</option>--}}
                                                    {{--@endif--}}
                                                    {{--@if($user->position != \App\Models\User::TV && $user->position != \App\Models\User::GSV)--}}
                                                        {{--<option value="3">Theo trưởng vùng</option>--}}
                                                    {{--@endif--}}
                                                    {{--@if(($user->position != \App\Models\User::GĐV && $user->position != \App\Models\User::GSV && $user->position != \App\Models\User::TV))--}}
                                                        {{--<option value="4">Theo giám đốc vùng</option>--}}
                                                    {{--@endif--}}
                                                {{--</select>--}}
                                                {{--<span id="typeExport" class="error-import" style="color:red;"></span>--}}
                                            {{--</div>--}}
                                            {{--<div class="clearfix"></div>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label class="control-label col-md-3">Chọn nhân sự</label>--}}
                                            {{--<div class="col-md-8">--}}
                                                {{--<select name="user" class="dataExport form-control"--}}
                                                        {{--style="width:100%">--}}
                                                {{--</select>--}}

                                                {{--<span id="humanExport" class="error-import" style="color:red;"></span>--}}
                                            {{--</div>--}}
                                            {{--<div class="clearfix"></div>--}}
                                        {{--</div>--}}





                                        {{--<div class="form-group">--}}
                                            {{--<label class="control-label col-md-3">Thời gian bắt đầu</label>--}}
                                            {{--<div class="col-md-8">--}}
                                                {{--<input type="text" name="startMonth"  class="form-control startMonth" value="" />--}}
                                                {{--<span id="startMonth" class="error-import" style="color:red;"></span>--}}
                                            {{--</div>--}}
                                            {{--<div class="clearfix"></div>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label class="control-label col-md-3">Thời gian kết thúc</label>--}}
                                            {{--<div class="col-md-8">--}}
                                                {{--<input type="text" name="endMonth"  class="form-control endMonth" value="" />--}}
                                                {{--<span id="endMonth" class="error-import" style="color:red;"></span>--}}
                                            {{--</div>--}}
                                            {{--<div class="clearfix"></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                {{--</div>--}}


                            {{--</div>--}}
                            {{--<div class="modal-footer">--}}
                                {{--<button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>--}}
                                {{--<button type="submit" class="btn green" id="export">Export</button>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                    {{--<!-- /.modal-content -->--}}
                {{--</div>--}}
                {{--<!-- /.modal-dialog -->--}}
            {{--</div>--}}




            <div class="portlet-body">
                <div id="map" style=" width: 100% ;height: 500px"></div>
                <div id="legend"></div>
                <div id="legend2"></div>
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
                                <input type="radio" name="radio" class="toggle radioButton" value="2">Tháng có doanh
                                số
                                cao nhất
                            </label>
                            <label class="btn  btn-default col-xs-6 col-md-3">
                                <input type="radio" name="radio" class="toggle radioButton" value="3"> Trung bình
                                tháng
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
<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
<script type="text/javascript" src="/js/gmaps.overlays.min.js"></script>
<script type="text/javascript" src="/js/maplabel-compiled.js"></script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>


<script type="text/javascript">
    $('.startMonth').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm-yy',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            console.log(month);
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));

            $(".endMonth").datepicker("option", "minDate", new Date(year, month, 1));
            $(".endMonth").datepicker("option", "maxDate",  new Date(year, 11, 1));
            $('.endMonth').datepicker('setDate', new Date(year, month, 1));
        }
    });
    $('.endMonth').datepicker( {
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
                name: 'DTTT',
                data: {{ json_encode($sales_real) }}
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
            },
            error: function (err) {
                console.log(err);cais
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
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });

        // export

        $('#export').on('click',function(e){
            var startMonth =  $('.startMonth-export').val();
            var type = $('.typeExport option:selected').val();
            if(type == '') {
                e.preventDefault();
                $('#typeExport').text('Vui lòng chọn loại để export');
            }
            var type1 = $('.dataExport option:selected').val();
            if(type1 == undefined) {
                e.preventDefault();
                $('#humanExport').text('Vui lòng chọn quản lý để export');
            }
        });
        $('#exportDashboard').click(function(e){
            $('#type_date_search').text('');
            $('#date_search').text('');
            $('.startMonth').text('');
            $('.endMonth').text('');
//            e.preventDefault();
            if($('.search_type').val() == '') {
                $('#type_date_search').text('Vui lòng chọn loại search');
            } else if($('#select_data_search').val() == null) {
                $('#date_search').text('Vui lòng chọn đối tượng');
            }else if($('.startMonth').val() == '') {
                $('.startMonth').text('Vui lòng chọn thời gian bắt đầu');
            } else if($('.endMonth').val() == '') {
                $('.endMonth').text('Vui lòng chọn thời gian kết thúc');
            } else {
                $.ajax({
                    method: "post",
                    url: "{{route('Admin::export')}}",
                data : $('#geocoding_form').serialize(),
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function (data) {
                        if(data.status == 1) {
                            alert(data.message);
                        }
                        if(data.status == 0) {
                            $.each(data.errors, function (index, value) {
                                $("#" + index).text(value);
                            });
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }







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
                type_search: type_search,
                data_search: '{{  $user->id }}',
                month: '{{ $month }}',
            },
            cache: false,
            success: function (data) {

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
                    showDataSaleNVKD(data);
                }
                if (type_search == 'gsv') {
                    showDataSales(data);
                }
                if (type_search == 'tv') {
                    showDataSales(data);
                }
                if (type_search == 'gdv') {
                    showDataSaleGDV(data);
                }
                if (type_search == 'admin') {
                    showDataSaleAdmin(data);
                }
            }
        });


        // search

        $(".search_type").change(function () {
            var search_type = $(this).val();
            if (search_type == 1) {
                getListAgents(0);
            } else if (search_type == 2) {
                getListGSV(0);
            } else if (search_type == 3) {
                getListTV(0);
            } else if (search_type == 4) {
                getListGDV(0);
            } else if (search_type == 5) {
                getListNVKD(0);
            }
        });

        $(".typeExport").change(function () {
            var search_type = $(this).val();
            if (search_type == 1) {
                getListAgents(1);
            } else if (search_type == 2) {
                getListGSV(1);
            } else if (search_type == 3) {
                getListTV(1);
            } else if (search_type == 4) {
                getListGDV(1);
            } else if (search_type == 5) {
                getListNVKD(1);
            }
        });



        $('#geocoding_form').submit(function (e) {
            e.preventDefault();
            var type_search = $("#type_search").val();

            $.ajax({
                type: "GET",
                url: "{{ route('Admin::map@dataSearch') }}",
                data: $('#geocoding_form').serialize(),
                cache: false,
                success: function (data) {
                    map = new GMaps({
                        div: '#map',
                        lat: 21.0277644,
                        lng: 105.83415979999995,
                        width: "100%",
                        height: '500px',
                        zoom: 7,
                        fullscreenControl: true,
                    });
                    var button = '<button id="swift" class="btn btn-primary">Full mode</button>';
                    map.addControl({
                        position: 'top_left',
                        content: button,
                    });

                    if (type_search == 'agents') {

                        showDataAgents(data);
                    }
                    if (type_search == 'gsv') {
                        showDataSales(data);
                    }
                    if (type_search == 'tv') {
                        showDataSales(data);
                    }
                    if (type_search == 'gdv') {
                        showDataSaleGDV(data);
                    }
                    if (type_search == 'nvkd' || type_search === undefined || type_search == null || type_search.length <= 0) {
                        showDataSaleNVKD(data);
                    }
                }
            });
        });

        var listSelectProducts = [];

        function getListGSV(type) {
            if(type == 0) {
                $("#type_search").val('gsv');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getGSV')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
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
                    escapeMarkup: function (m) {
                        return m;
                    }
                }
            });
        }

        function getListTV(type) {
            if(type == 0) {
                $("#type_search").val('tv');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getListTV')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
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
                    escapeMarkup: function (m) {
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

            var listAgents = '<div id="legend">';
            $.map(data.listAgents, function (item) {
                var agent = item.agent;
                var user = agent.user;
                var contentString = '<div class="info" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                    '<h5 class="address" style="display:none; font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' + agent.name + ' - ' + agent.address + '</h5>' +
                    '<div class="user_data" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                    '<p class="data" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">%TT ' + numberWithCommas(item.totalSales) + '/' + numberWithCommas(item.capacity) + '=' + item.percent + '%</p>' +
                    '<ul class="info_user" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                    '<li> NVKD:' + user.name + '</li>' +
                    '<li class="gsv" style="display: none; font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' + postion + ':' + data.user.name + '</li>' +
                    '<li class="gdv" style="display: none; font-size:' + user.fontSize + 'px; color:' + user.textColor + '"> GĐ :' + data.director + '</li>' +
                    '</ul>' +
                    '</div>' +
                    '</div>';
                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });
                var image = "";
                if (agent.icon != "") {
                    image = 'http://' + window.location.hostname + '/' + agent.icon;
                }

                var marker = map.addMarker({
                    lat: agent.lat,
                    lng: agent.lng,
                    title: agent.name,
                    icon: image,
                    infoWindow: infoWindow,
                    click: function (e) {
                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                        infoWindow.open(map.map);
                    }
                });
                listAgents += '<div><p class="" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">'  + agent.name + ' %TT ' + item.totalSales + '/' + numberWithCommas(item.capacity) + '=' + numberWithCommas(item.percent) + '%</p><br></div>';
            });

            listAgents+= '</div>';
            map.addControl({
                position: 'top_right',
                content: listAgents,
            });

            var list_products = data.listProducts;

            var tableSales = '<table class="table table-striped table-bordered table-products" cellspacing="0" width="100%" id="data-table">' +
                '<thead>' +
                '<tr>' +
                '<th>Tên Sản phẩm</th>' +
                '<th>Mã Sản phẩm</th>' +
                '<th>Sản lượng</th>' +
                '<th>Dung lượng</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '<tr>' +
                '<td>' +
                '<select id="choose_product">';
            $.map(list_products, function (product) {
                tableSales += '<option style="font-weight: bold" value="' + product.code + '">' + product.name + '</option>';
                var productChildren = product.listProducts;

                $.map(productChildren, function (productChild) {
                    tableSales += '<option value="' + productChild.code + '">' + productChild.name + '</option>';
                });

            });
            tableSales += '</select>' +
                '</td>' +
                '<td id="code">' + list_products[0].code + '</td>' +
                '<td id="totalSales">' + numberWithCommas(list_products[0].totalSales) + '</td>' +
                '<td id="capacity">' + numberWithCommas(list_products[0].capacity) + '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>';
            map.addControl({
                position: 'top_left',
                content: tableSales,
            });

            var listCodes = '<div style="background-color: white"><h3>Sản phẩm đang bán</h3><span>';
            $.map(data.listCodes, function (code) {
                listCodes +=  code + ' , ';
            });
            listCodes += '</span></div>';
            map.addControl({
                position: 'bottom_right',
                content: listCodes,
            });

            listSelectProducts = [];
            $.each(list_products, function (index, value) {
                listSelectProducts.push(value);
                var productChildren = value.listProducts;
                $.each(productChildren, function (index, val) {
                    listSelectProducts.push(val);
                });
            });


            var tableSales =
                '<div class="info_gsv" style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '" >' +
                '<h3 style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '">' + area_name + '</h3>' +
                '<div class="user_data_gsv" style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '">' +
                '<p class="data_gsv" id="data" style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '">%TT ' + numberWithCommas(data.totalSales) + '/' + numberWithCommas(data.capacity) + '=' + data.percent + '%</p>' +
                '<ul class="info_user_gsv" style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '">' +
                '<li>' + postion + ':' + data.user.name + '</li>' +
                '<li class="gdv" style="display: none; font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '"> GĐ :' + data.director + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
            map.addControl({
                position: 'top_center',
                content: tableSales,
            });

            if (data.table) {
                $('#tableData').html('');
                $('#tableData').html(data.table);
            }
        }

        function getListNVKD(type) {

            if(type == 0) {
                $("#type_search").val('nvkd');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- Chọn nhân viên kinh doanh --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getListNVKD')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
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
                    escapeMarkup: function (m) {
                        return m;
                    }
                }
            });
        }

        function showDataSaleNVKD(data) {
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

            if (data.userParent.position == 3) {
                postion = 'TV';
            } else {
                postion = 'GS';
            }

            var listAgents = '<div id="legend">';
            $.map(data.listAgents, function (item) {
                var agent = item.agent;
                var user = agent.user;
                var contentString = '<div class="info" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                    '<h5 class="address" style="display:none; font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' + agent.name + ' - ' + agent.address + '</h5>' +
                    '<div class="user_data" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                    '<p class="data" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">%TT ' + numberWithCommas(item.totalSales) + '/' + numberWithCommas(item.capacity) + '=' + item.percent + '%</p>' +
                    '<ul class="info_user" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                    '<li> NVKD:' + user.name + '</li>' +
                    '<li class="gsv" style="display: none; font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' + postion + ':' + data.userParent.name + '</li>' +
                    '</ul>' +
                    '</div>' +
                    '</div>';
                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });
                var image = "";
                if (agent.icon != "") {
                    image = 'http://' + window.location.hostname + '/' + agent.icon;
                }

                var marker = map.addMarker({
                    lat: agent.lat,
                    lng: agent.lng,
                    title: agent.name,
                    icon: image,
                    infoWindow: infoWindow,
                    click: function (e) {
                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                        infoWindow.open(map.map);
                    }
                });
                listAgents += '<div><p class="" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">'  + agent.name + ' %TT ' + numberWithCommas(item.totalSales) + '/' + numberWithCommas(item.capacity) + '=' + numberWithCommas(item.percent) + '%</p><br></div>';
            });

            listAgents+= '</div>';
            map.addControl({
                position: 'top_right',
                content: listAgents,
            });

            var list_products = data.listProducts;

            var tableSales = '<table class="table table-striped table-bordered table-products" cellspacing="0" width="100%" id="data-table">' +
                '<thead>' +
                '<tr>' +
                '<th>Tên Sản phẩm</th>' +
                '<th>Mã Sản phẩm</th>' +
                '<th>Sản lượng</th>' +
                '<th>Dung lượng</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '<tr>' +
                '<td>' +
                '<select id="choose_product">';
            $.map(list_products, function (product) {
                tableSales += '<option style="font-weight: bold" value="' + product.code + '">' + product.name + '</option>';
                var productChildren = product.listProducts;

                $.map(productChildren, function (productChild) {
                    tableSales += '<option value="' + productChild.code + '">' + productChild.name + '</option>';
                });

            });
            tableSales += '</select>' +
                '</td>' +
                '<td id="code">' + list_products[0].code + '</td>' +
                '<td id="totalSales">' + numberWithCommas(list_products[0].totalSales) + '</td>' +
                '<td id="capacity">' + numberWithCommas(list_products[0].capacity) + '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>';
            map.addControl({
                position: 'top_left',
                content: tableSales,
            });

            var listCodes = '<div style="background-color: white"><h3>Sản phẩm đang bán</h3><span>';
            $.map(data.listCodes, function (code) {
                listCodes +=  code + ' , ';
            });
            listCodes += '</span></div>';
            map.addControl({
                position: 'bottom_right',
                content: listCodes,
            });

            listSelectProducts = [];
            $.each(list_products, function (index, value) {
                listSelectProducts.push(value);
                var productChildren = value.listProducts;
                $.each(productChildren, function (index, val) {
                    listSelectProducts.push(val);
                });
            });


            var tableSales =
                '<div class="info_gsv" style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '" >' +
                '<h3 style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '">' + area_name + '</h3>' +
                '<div class="user_data_gsv" style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '">' +
                '<p class="data_gsv" id="data" style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '">%TT ' + numberWithCommas(data.totalSales) + '/' + numberWithCommas(data.capacity) + '=' + data.percent + '%</p>' +
                '<ul class="info_user_gsv" style="font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '">' +
                '<li>' + postion + ':' + data.user.name + '</li>' +
                '<li class="gdv" style="display: none; font-size:' + data.user.fontSize + 'px; color:' + data.user.textColor + '"> GĐ :' + data.director + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
            map.addControl({
                position: 'top_center',
                content: tableSales,
            });

            if (data.table) {
                $('#tableData').html('');
                $('#tableData').html(data.table);
            }
        }

        function getListGDV(type) {
            if(type == 0) {
                $("#type_search").val('gdv');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getListGDV')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
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
                    escapeMarkup: function (m) {
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
            var legend = document.getElementById('legend2');

            $.map(data.result, function (item) {
                var agents = item.agents;
                var markers = [];
                $.map(agents, function (agent) {
                    var latLng = new google.maps.LatLng(agent.lat,
                        agent.lng);
                    var image = "";
                    if (agent.icon != "") {
                        image = 'http://' + window.location.hostname + '/' + agent.icon;
                    }

                    var contentString = '<div class="info" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">' +
                        '<h5 class="address" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">' + agent.name + ' - ' + agent.address + '</h5>' +
                        '<div class="user_data" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">' +
                        '<p class="data" id="data" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">%TT ' + numberWithCommas(agent.totalSales) + '/' + numberWithCommas(agent.capacity) + '=' + agent.percent + '%</p>' +
                        '<ul class="info_user" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">' +
                        '<li> NVKD:' + agent.user.name + '</li>' +
                        '</ul>' +
                        '</div>' +
                        '</div>';

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    var marker = new google.maps.Marker({
                        'position': latLng,
                        icon: image,
                    });
                    marker.addListener('click', function () {
                        infowindow.open(map, marker);
                    });

                    markers.push(marker);
                });
                var markerCluster = new MarkerClusterer(map, markers, {
                    maxZoom: 15,
                    imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                });

                var div = document.createElement('div');
                div.style.color = item.gsv.textColor;
                div.innerHTML = item.gsv.name + ' - %TT ' + numberWithCommas(item.totalSales) + '/' + numberWithCommas(item.capacity) + '=' + item.percent + "%";

                legend.appendChild(div);

                var customTxt =
                    '<div class="customBox" style="display:none; font-size:' + item.gsv.fontSize + 'px; color:' + item.gsv.textColor + '">' +
                    '<span class="data_gsv" style="font-size:' + item.gsv.fontSize + 'px; color:' + item.gsv.textColor + '">%TT ' + numberWithCommas(item.totalSales) + '/' + numberWithCommas(item.capacity) + '=' + item.percent + '%</span>' +
                    '<span class="info_user_gsv" style="font-size:' + item.gsv.fontSize + 'px; color:' + item.gsv.textColor + '">' + item.gsv.name + '</span>' +
                    '</div>';
                txt = new TxtOverlay(new google.maps.LatLng(markers[0].getPosition().lat(), markers[0].getPosition().lng()), customTxt, "customBox", map);
            });

            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

            $.map(data.resultGdv, function (item) {
                var agent = item.agents;
                var latLng = new google.maps.LatLng(agent.lat,
                    agent.lng);

                var contentString = '<div class="info" style="font-size:' + item.gsv.fontSize + 'px; color:' + item.gsv.textColor + '">' +
                    '<h5 class="address" style="font-size:' + item.gsv.fontSize + 'px; color:' + item.gsv.textColor + '">' + agent.name + ' - ' + agent.address + '</h5>' +
                    '<div class="user_data" style="font-size:' + item.gsv.fontSize + 'px; color:' + item.gsv.textColor + '">' +
                    '<p class="data" id="data" style="font-size:' + item.gsv.fontSize + 'px; color:' + item.gsv.textColor + '">%TT ' + numberWithCommas(item.totalSales) + '/' + numberWithCommas(item.capacity) + '=' + item.percent + '%</p>' +
                    '<ul class="info_user" style="font-size:' + item.gsv.fontSize + 'px; color:' + item.gsv.textColor + '">' +
                    '<li>' + item.gsv.name + '</li>' +
                    '</ul>' +
                    '</div>' +
                    '</div>';

                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                var image = "";
                if (agent.icon != "") {
                    image = 'http://' + window.location.hostname + '/' + agent.icon;
                }
                var marker = new google.maps.Marker({
                    'position': latLng,
                    map: map,
                    icon: image,
                });
                marker.addListener('click', function () {
                    infowindow.open(map, marker);
                });
            });

            var list_products = data.listProducts;

            var tableSales = '<table class="table table-striped table-bordered table-products" cellspacing="0" width="100%" id="data-table">' +
                '<thead>' +
                '<tr>' +
                '<th>Tên Sản phẩm</th>' +
                '<th>Mã Sản phẩm</th>' +
                '<th>Sản lượng</th>' +
                '<th>Dung lượng</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '<tr>' +
                '<td>' +
                '<select id="choose_product">';

            $.map(list_products, function (product) {
                tableSales += '<option style="font-weight: bold" value="' + product.code + '">' + product.name + '</option>';
                var productChildren = product.listProducts;

                $.map(productChildren, function (productChild) {
                    tableSales += '<option value="' + productChild.code + '">' + productChild.name + '</option>';
                });

            });

            tableSales += '</select>' +
                '</td>' +
                '<td id="code">' + list_products[0].code + '</td>' +
                '<td id="totalSales">' + numberWithCommas(list_products[0].totalSales) + '</td>' +
                '<td id="capacity">' + numberWithCommas(list_products[0].capacity) + '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>';

            listSelectProducts = [];
            $.each(list_products, function (index, value) {
                listSelectProducts.push(value);
                var productChildren = value.listProducts;
                $.each(productChildren, function (index, val) {
                    listSelectProducts.push(val);
                });
            });

            // products table
            var table = document.createElement('div');
            table.innerHTML = tableSales;
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(table);

            // products code
            var listCodes = '<div style="background-color: white"><h3>Sản phẩm đang bán</h3><span>';
            $.map(data.listCodes, function (code) {
                listCodes +=  code + ' , ';
            });
            listCodes += '</span></div>';

            var codes = document.createElement('div');
            codes.innerHTML = listCodes;
            map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(codes);


            // info total
            var info = '<h3 id="data" >' + data.user.name + ' - %TT ' + numberWithCommas(data.totalSales) + '/' + numberWithCommas(data.capacity) + '=' + data.percent + '%' + '</h3>'
            var myTitle = document.createElement('div');
            myTitle.style.color = data.user.textColor;
            myTitle.innerHTML = info;
            var myTextDiv = document.createElement('div');
            myTextDiv.appendChild(myTitle);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(myTextDiv);

            //button compact mode
            var button = document.createElement('div');
            button.innerHTML = '<button id="swift" class="btn btn-primary">Full mode</button>';
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(button);

            if (data.table) {
                $('#tableData').html('');
                $('#tableData').html(data.table);
            }
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
        TxtOverlay.prototype.onAdd = function () {
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
        TxtOverlay.prototype.draw = function () {
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
        TxtOverlay.prototype.onRemove = function () {
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        }
        TxtOverlay.prototype.hide = function () {
            if (this.div_) {
                this.div_.style.visibility = "hidden";
            }
        }
        TxtOverlay.prototype.show = function () {
            if (this.div_) {
                this.div_.style.visibility = "visible";
            }
        }
        TxtOverlay.prototype.toggle = function () {
            if (this.div_) {
                if (this.div_.style.visibility == "hidden") {
                    this.show();
                } else {
                    this.hide();
                }
            }
        }
        TxtOverlay.prototype.toggleDOM = function () {
            if (this.getMap()) {
                this.setMap(null);
            } else {
                this.setMap(this.map_);
            }
        }
        function getListAgents(type) {
            if(type == 0) {
                $("#type_search").val('agents');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.agency') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getListAgents')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
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
                    escapeMarkup: function (m) {
                        return m;
                    }
                }
            });
        }

        function showDataAgents(data) {
            listSelectProdcuts = [];
            map = new GMaps({
                div: '#map',
                lat: data.agents.lat,
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
            var contentString = '<div class="info" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                '<h5 class="address" style="display:none; font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' + data.agents.name + ' - ' + data.agents.address + '</h5>' +
                '<div class="user_data" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                '<p class="data" id="data" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">%' + list_products[0].code + ' ' + numberWithCommas(list_products[0].totalSales) + '/' + numberWithCommas(list_products[0].capacity) + '=' + list_products[0].percent + '%</p>' +
                '<ul class="info_user" style="font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' +
                '<li> NVKD:' + user.name + '</li>' +
                '<li class="gsv" style="display:none; font-size:' + user.fontSize + 'px; color:' + user.textColor + '">' + postion + ':' + data.gsv.name + '</li>' +
                '<li class="gdv" style="display:none; font-size:' + user.fontSize + 'px; color:' + user.textColor + '"> GĐ :' + data.gdv.name + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
            var infoWindow = new google.maps.InfoWindow({
                content: contentString
            });
            var image = "";
            if (data.agents.icon != "") {
                image = 'http://' + window.location.hostname + '/' + data.agents.icon;
            }
            var myMarker = map.addMarker({
                lat: data.agents.lat,
                lng: data.agents.lng,
                title: data.agents.name,
                infoWindow: infoWindow,
                icon: image,
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
                content: '<div class="info" >' +
                '<h5>' + data.agents.name + '</h5>' +
                '</div>'
            });
            var tableSales = '<table class="table table-striped table-bordered table-products" cellspacing="0" width="100%" id="data-table">' +
                '<thead>' +
                '<tr>' +
                '<th>Tên Sản phẩm</th>' +
                '<th>Mã Sản phẩm</th>' +
                '<th>Sản lượng</th>' +
                '<th>Dung lượng</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '<tr>' +
                '<td>' +
                '<select id="choose_product">';
            $.map(list_products, function (product) {
                tableSales += '<option style="font-weight: bold" value="' + product.code + '">' + product.name + '</option>';
                var productChildren = product.listProducts;

                $.map(productChildren, function (productChild) {
                        tableSales += '<option value="' + productChild.code + '">' + productChild.name + '</option>';
                });

            });
            tableSales += '</select>' +
                '</td>' +
                '<td id="code">' + list_products[0].code + '</td>' +
                '<td id="totalSales">' + numberWithCommas(list_products[0].totalSales) + '</td>' +
                '<td id="capacity">' + numberWithCommas(list_products[0].capacity) + '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>';

            map.addControl({
                position: 'top_left',
                content: tableSales,
            });

            var button = '<button id="swift" class="btn btn-primary">Full mode</button>';
            map.addControl({
                position: 'top_right',
                content: button,
            });

            var listCodes = '<div style="background-color: white"><h3>Sản phẩm đang bán</h3><span>';
            $.map(data.listCodes, function (code) {
                listCodes +=  code + ' , ';
            });
            listCodes += '</span></div>';
            map.addControl({
                position: 'bottom_right',
                content: listCodes,
            });


            listSelectProducts = [];
            $.each(list_products, function (index, value) {
                listSelectProducts.push(value);
                var productChildren = value.listProducts;
                $.each(productChildren, function (index, val) {
                        listSelectProducts.push(val);
                });
            });

            if (data.table) {
                $('#tableData').html('');
                $('#tableData').html(data.table);
            }


        }

        function showDataSaleAdmin(data) {
            var polygonArray = [];
            var position = '';
            var center = new google.maps.LatLng(21.0277644, 105.83415979999995);
            var options = {
                'zoom': 5,
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
                var legend = document.getElementById('legend');
                $.map(agents, function (agent) {
                    var latLng = new google.maps.LatLng(agent.lat,
                        agent.lng);
                    var image = "";
                    if (agent.icon != "") {
                        image = 'http://' + window.location.hostname + '/' + agent.icon;
                    }

                    var contentString = '<div class="info" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">' +
                        '<h5 class="address" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">' + agent.name + ' - ' + agent.address + '</h5>' +
                        '<div class="user_data" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">' +
                        '<p class="data" id="data" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">%TT ' + numberWithCommas(agent.totalSales) + '/' + numberWithCommas(agent.capacity) + '=' + agent.percent + '%</p>' +
                        '<ul class="info_user" style="font-size:' + agent.user.fontSize + 'px; color:' + agent.user.textColor + '">' +
                        '<li>' + agent.user.name + '</li>' +
                        '</ul>' +
                        '</div>' +
                        '</div>';

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    var marker = new google.maps.Marker({
                        'position': latLng,
                        icon: image,
                    });

                    marker.addListener('click', function () {
                        infowindow.open(map, marker);
                    });

                    markers.push(marker);
                });
                var markerCluster = new MarkerClusterer(map, markers, {
                    maxZoom: 15,
                    imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                });

                var customTxt =
                    '<div class="customBox" style="font-size:' + item.gdv.fontSize + 'px; color:' + item.gdv.textColor + '">' +
                    '<span style="font-size:' + item.gdv.fontSize + 'px; color:' + item.gdv.textColor + '">' + item.gdv.name + '</span>' +
                    '</div>';
                txt = new TxtOverlay(new google.maps.LatLng(markers[0].getPosition().lat(), markers[0].getPosition().lng()), customTxt, "customBox", map);

                var div = document.createElement('div');
                div.style.color = item.gdv.textColor;
                div.innerHTML = item.gdv.name + ' - %TT ' + numberWithCommas(item.totalSales) + '/' + numberWithCommas(item.capacity) + '=' + item.percent + "%";
                legend.appendChild(div);
            });

            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
        }


        $(document).on('change', '#choose_product', function () {
            var code = $(this).val();
            var data = $.grep(listSelectProducts, function (e) {
                return e.code == code;
            });
            var item = data[0];
            $("#code").text(item.code);
            $("#totalSales").text(numberWithCommas(item.totalSales));
            $("#capacity").text(numberWithCommas(item.capacity));
            $("#data").text('%' + item.code + ' ' + item.totalSales + '/' + item.capacity + '=' + item.percent + '%');
        });
        $(document).on('click', '#swift', function () {
            var text = $(this).text();
            if (text == 'Full Mode') {
                $(this).text('Compact Mode');
                $('.gsv').show();
                $('.gdv').show();
                $('.address').show();
                $('.customBox').show();
            }
            else {
                $(this).text('Full Mode');
                $('.gsv').hide();
                $('.gdv').hide();
                $('.address').hide();
                $('.customBox').hide();
            }
        });

        function numberWithCommas(x) {
            var parts = x.toString().split(",");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(",");
        }
    });
</script>
@endpush