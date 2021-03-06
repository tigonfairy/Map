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
        .customBox {
            position: absolute;
            font-size: 16px;
            background-color: yellow;
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
                <div class="row">
                    <div class="col-md-2">
                        {{--<select class="search_type form-control">--}}
                            {{--<option value="">-- Chọn loại {{ trans('home.search') }} --</option>--}}
                            {{--<option value="1">Theo đại lý</option>--}}
                            {{--@if(auth()->user()->position != \App\Models\User::NVKD || auth()->user()->email == 'admin@gmail.com')--}}
                                {{--<option value="2">Theo giám sát vùng </option>--}}
                                {{--@if(auth()->user()->position == \App\Models\User::GDV || auth()->user()->position == \App\Models\User::TV  || auth()->user()->email == 'admin@gmail.com')--}}
                                    {{--<option value="3">Theo trưởng vùng </option>--}}
                                {{--@endif--}}
                                {{--@if(auth()->user()->position == \App\Models\User::GDV || auth()->user()->email == 'admin@gmail.com')--}}
                                    {{--<option value="4">Theo giám đốc vùng</option>--}}
                                {{--@endif--}}
                            {{--@endif--}}
                        {{--</select>--}}


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
                    zoom: 8,
                    fullscreenControl: true,
                });
                var button ='<button id="swift" class="btn btn-primary">Full mode</button>';
                map.addControl({
                    position: 'bottom_left',
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
                if (type_search == 'gdv' ) {
                    showDataSaleGDV(data);
                }
            }
        });

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