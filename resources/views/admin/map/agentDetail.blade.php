@extends('admin')
@push('style_head')

@endpush
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="col-md-9">
                <div class="page-title">
                    <h2>{{ trans('home.agency') }} : {{$agent->name}}</h2>
                    <h4>{{ trans('home.manager') }}: {{$agent->user->email}}</h4>
                </div>
            </div>
            {{--<div class="col-md-3">--}}
            {{--<div class="page-title button-change-size-map">--}}
            {{--<input type="checkbox" id="change-button-size-map" name="checkbox" @if(Request::input('size-map') == 'fullscreen') checked @endif class="make-switch" data-on-text="&nbsp;FullScreen&nbsp;" data-off-text="&nbsp;Landscape&nbsp;">--}}
            {{--</div>--}}

            {{--</div>--}}
            <div class="clearfix"></div>

        </div>

    </div>

    <div class="page-container">
        <div class="content-wrapper">
            @include('admin.flash')
            <div class="row">
                <div class="form-group {{ $errors->has('month') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold col-md-1">{{ trans('home.time') }}</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                       data-content="Thời gian"></i>
                    <div class="col-md-3">
                        <input type="text" id="month" name="month" class="form-control monthPicker col-md-9"
                               value="{{ old('month') ?: $month }}"/>
                    </div>
                    @if ($errors->has('month'))
                        <div class="form-control-feedback">
                            <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('month') }}</div>
                    @endif
                </div>
            </div>
            <br>
            <div class="row">

                <div class="baomap col-xs-12">
                    <div id="map"></div>

                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts_foot')
<script src="/assets/pages/scripts/components-bootstrap-switch.min.js" type="text/javascript"></script>
<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
@endpush

@push('scripts')
<script>
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
                var url = window.location.origin + window.location.pathname;
                url = url + '?month=' + $(this).val();
                window.location.href = url;
            }
        });

        var heightPageContent = $('.page-content').height();
        var heightPageHeader = $('.page-header-content').height();
        $('.baomap').height(heightPageContent - heightPageHeader);
        var polygonArray = [];
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '100%',
            zoom: 12,
            fullscreenControl: true
        });

        // a  div where we will place the buttons
        var ctrl = '<ul id="checkbox" class="checkboxList">' +
            '<li><label><input type="checkbox" name="select_all" value="0" id="select_all">Tất cả</label></li>' +
                @foreach($products as $product)
            '<li><label><input type="checkbox" class="checkbox" name="{{ $product->product->name }}" value="{{ $product->product->id }}">{{ $product->product->name }}</label></li>' +
                @endforeach
            '</ul>';

        map.addControl({
            position: 'bottom_right',
            content: ctrl,
        });

        var marker = map.addMarker({
            lat: "{{$agent->lat}}",
            lng: "{{$agent->lng}}",
//            click: function (e) {
//                infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
//                infoWindow.open(map.map);
//            }
        });

<<<<<<< HEAD
        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();

        map.setCenter("{{$agent->lat}}","{{$agent->lng}}");

        @if(count($products))
            var contentString = '<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="data-table">' +
                                '<thead><tr>' +
                                '<th>{{ trans('home.Product') }}</th>' +
                                '<th>{{ trans('home.sale_plan') }}</th>' +
                                '<th>{{ trans('home.sale_real') }}</th>'+
                                '</tr> </thead>'+
                            @foreach($products as $p)
                                '<tr role="row" class="tr_{{ $p->product->id }}" id="tr_{{ $p->product->id }}">' +
                                '<td>{{$p->product->name}}</td>' +
                                '<td>{{$p->sales_plan}}</td>' +
                                '<td>{{$p->sales_real}}</td>' +
                                '</tr>' +
                            @endforeach
                                '</table>';

            var infoWindow = new google.maps.InfoWindow({
                content: contentString
            });

            // khoi tao mang productIds
            var productIds = [];
                @foreach($products as $product)
                    productIds.push('{{$product->product->id}}');
                @endforeach

        // khoi tao mang checked
        var checked = [];
        var unchecked = [];

            $(document).on('click', '#select_all', function() {
                if(this.checked) {
                    $.each(productIds, function( index, value ) {
                        checked.push(value);
                    });
                    // Iterate each checkbox
                    $('#checkbox :checkbox').each(function() {
                        this.checked = true;
                    });

                    var unique = Array.from(new Set(checked));
                    $.each(unique, function( index, value ) {
                        $('#tr_' + value).show();
                    });
                    infoWindow.setPosition({lat:lat, lng: lng});
                    infoWindow.open(map.map);
                } else {
                    checked=[];
                    $('#checkbox :checkbox').each(function() {
                        this.checked = false;
                    });
                    infoWindow.setPosition({lat: "{{$agent->lat}}", lng:"{{$agent->lng}}"});
                    infoWindow.close(map.map);
                }

            });

            $(document).on('change', '.checkbox', function() {
                $('#select_all').attr('checked', false);
                if(this.checked) {
                    checked.push($(this).val());
                } else {
                    removeA(checked, $(this).val());
                }

                unchecked = arr_diff(productIds, checked);
                $.each(checked, function( index, value ) {
                    $('#tr_' + value).show();
                });

                $.each(unchecked, function( index, value ) {
                    $('#tr_' + value).hide();
                });

                infoWindow.setPosition({lat:lat, lng: lng});
                @endif
        var image = {
                    url: "{{$agent->icon}}", // image is 512 x 512
                    size: new google.maps.Size(22, 32)
                };

        map.addMarker({
            lat: "{{$agent->lat}}",
            lng: "{{$agent->lng}}"
            @if(isset($agent->icon))
            , icon: image
            @endif
            , click: function (e) {
                infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                infoWindow.open(map.map);
            }
        });
        @endif
            });
    });

    function removeA(arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax= arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
    }

    function arr_diff (a1, a2) {

        var a = [], diff = [];

        for (var i = 0; i < a1.length; i++) {
            a[a1[i]] = true;
        }

        for (var i = 0; i < a2.length; i++) {
            if (a[a2[i]]) {
                delete a[a2[i]];
            } else {
                a[a2[i]] = true;
            }
        }

        for (var k in a) {
            diff.push(k);
        }

        return diff;
    };
</script>

@endpush
