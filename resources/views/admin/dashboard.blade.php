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


                <div id="container" class="row" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                <div class="col-xs-12">
                    <div class="col-xs-6">
                        <div class="col-xs-12">
                        <div class="col-xs-6">
                            <label><input type="radio" name="radio2" checked class="icheck radioButton"  value="1">Tháng
                                gần nhất </label>
                        </div>
                        <div class="col-xs-6">
                            <label><input type="radio" name="radio2" class="icheck radioButton"
                                          value="2">Tháng có doanh số cao nhất</label>
                        </div>


                        <div class="col-xs-6">
                            <label>
                                <input type="radio" name="radio2" class="icheck radioButton"
                                       value="3">Trung bình tháng</label>
                        </div>
                        <div class="col-xs-6">
                            <label>
                                <input type="radio" name="radio2"  class="icheck radioButton"
                                       value="4">Tổng sản lượng</label>
                        </div>
                        </div>
                        <div class="col-xs-12" id="tableData">

                        </div>

                </div>
                <div class="col-xs-6" id="chartSp" style="min-width: 310px; height: 400px; margin: 0 auto">

                </div>
            </div>

        </div>
    </div>

    </div>



    <!-- /main content -->
@endsection
@push('scripts_foot')
<script src="/js/highcharts.js"></script>

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
            method:"post",
            url: "{{route('Admin::chart')}}",
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                type : 1
            },
            dataType:'json',
            success: function (data) {
                if(data.title){
                    chartSp.setTitle({
                        text : 'Biểu đô tháng '+data.title
                    });
                }

                if(data.chart){
                    var seriesLength = chartSp.series.length;
                    for(var i = seriesLength - 1; i > -1; i--)
                    {
                        //chart.series[i].remove();
                        if(chartSp.series[i].name ==document.getElementById("series_name").value)
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
                if(data.table){
                    var table = data.table;
                    var string = '<table class="table table-striped table-bordered" cellspacing="0" width="100%">' +
                            ' <thead> <tr> <th>Sản phẩm</th> <th>Doanh số</th></tr></thead><tbody>';

                    table.forEach(function(value){
                        string += '<tr>';
                        string += '<td>';
                        string += value.name;
                        string += '</td>';
                        string += '<td>';
                        string += value.y;
                        string += '</td>';

                        string += '</tr>';

                    });
                    string +='</tbody></table>';
                    $('#tableData').html(string);
                }



            },
            error: function (err) {
                console.log(err);
                alert('Lỗi, hãy thử lại sau');
            }
        });

        $('.radioButton').change(function(){

            var type = $(this).val();
            $.ajax({
                method:"post",
                url: "{{route('Admin::chart')}}",
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                data: {
                    type : type
                },
                dataType:'json',
                success: function (data) {
                    if(data.title){
                        chartSp.setTitle({
                            text : 'Biểu đô '+data.title
                        });
                    }
                    if(data.chart){
                        while( chartSp.series.length > 0 ) {
                            chartSp.series[0].remove( false );
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
                    if(data.table){
                        $('#tableData').html('');
                        var table = data.table;
                        var string = '<table class="table table-striped table-bordered" cellspacing="0" width="100%">' +
                                ' <thead> <tr> <th>Sản phẩm</th> <th>Doanh số</th></tr></thead><tbody>';

                        table.forEach(function(value){
                            string += '<tr>';
                            string += '<td>';
                            string += value.name;
                            string += '</td>';
                            string += '<td>';
                            string += value.y;
                            string += '</td>';

                            string += '</tr>';

                        });
                        string +='</tbody></table>';
                        $('#tableData').html(string);
                    }



                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });
</script>
@endpush


