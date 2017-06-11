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
                <form method="post" action="">
                    {{ csrf_field() }}
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" id="month" name="month" class="form-control monthPicker"
                                           value="{{ old('month') ?: $month }}"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <select name="area_id" class="areas form-control">
                                <option value="">-- Chọn vùng trực thuộc --</option>
                                @foreach($areas as $key => $value)
                                    <option value="{{$value->id}}" @if(old('area_id') == $value->id) selected @endif>{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                        </div>
                    </div>
                </form>

                <table class="columns">
                    <tr>
                        @if(count($dataSales) > 0)
                            @foreach($dataSales as $key => $dataSale)
                                <td><div id="{{ $dataSale['id'] }}" style="border: 1px solid #ccc"></div></td>
                            @endforeach
                        @endif
                    </tr>
                </table>
            </div>
        </div>
    </div>
        <!-- /main content -->
@endsection
@push('scripts_foot')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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

        // Load Charts and the corechart package.
        google.charts.load('current', {'packages':['bar']});
        // Draw the pie chart
        google.charts.setOnLoadCallback(drawChart);

        // Callback that draws the pie chart for Sarah's pizza.
        function drawChart() {
            @if(count($dataSales) > 0)
                @foreach($dataSales as $key => $dataSale)
                    var id = "{{ $dataSale['id'] }}";
                    var title = "{{  $dataSale['area'] }}";
                    var dataSales =  {!!  $dataSale['data'] !!};
                    var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Product');
                        data.addColumn('number', 'Total sales Real');
                        data.addColumn('number', 'Total sales Plan');

                    $.each(dataSales, function( index, value ) {
                        data.addRows([
                            [value.product_name, parseInt(value.total_sales_real),  parseInt(value.total_sales_plan)]
                        ]);
                    });

                    // Set options for Sarah's pie chart.
                    var options = {
                        chart: {
                            title: title,
                            width:400,
                            height:300
                        }
                    };

                    var chart = new google.charts.Bar(document.getElementById(id));
                    chart.draw(data, google.charts.Bar.convertOptions(options));
                @endforeach
            @endif
        }
    });
</script>
@endpush


