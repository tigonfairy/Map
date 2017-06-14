@extends('admin')

@section('content')
    <style>
        #map {
            width: 800px;
            height: 300px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Dashboard</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="content-wrapper">
            <div class="row">
                {{--<div class="col-md-12">--}}
                    {{--<form method="post" action="">--}}
                            {{--{{ csrf_field() }}--}}
                            {{--<div class="row">--}}

                                {{--<div class="col-md-2">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<input type="text" id="month" name="month" class="form-control monthPicker"--}}
                                               {{--value="{{ old('month') ?: $month }}"/>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="col-md-2">--}}
                                    {{--<select name="area_id" class="areas form-control"  style="width:100%">--}}
                                        {{--<option value="">{{ ' -- '. trans('home.select'). ' '. trans('home.place') . ' -- ' }}</option>--}}
                                        {{--@foreach($areas as $key => $value)--}}
                                            {{--<option value="{{$value->id}}" @if(old('area_id') == $value->id) selected @endif>{{ $value->name }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}

                                {{--<div class="col-md-4">--}}
                                    {{--<button type="submit" class="btn btn-info">{{ trans('home.statistic') }}</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                {{--</div>--}}

                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>
    </div>
        <!-- /main content -->
@endsection
@push('scripts_foot')
<script src="/js/highcharts.js"></script>
{{--<script src="https://code.highcharts.com/highcharts.js"></script>--}}
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

        Highcharts.chart('container', {
            chart: {
                type: 'column'
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
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'DTKH',
                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

            }, {
                name: 'DTTT',
                data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

            }]
        });

    });
</script>
@endpush


