@extends('admin')
@push('style_head')

@endpush
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="col-md-9">
                <div class="page-title">
                    <h2>{{ trans('home.show') . ' ' . trans('home.area_sale') }} : {{$area->name}}</h2>
                    <h4>{{ trans('home.manager') }} : {{$area->user->email}}</h4>
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

                <div class="baomap col-xs-6" >
                    <div id="map"></div>
                </div>

                {{--số liệu--}}
                <div class="col-xs-6">
                    <div class="form-group {{ $errors->has('month') ? 'has-error has-feedback' : '' }}">
                        <label for="name" class="control-label text-semibold col-md-3">{{ trans('home.time') }}</label>
                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                           data-content="Thời gian"></i>
                        <div class="col-md-9">
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

                    @if(count($products))
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="users-table">
                            <thead>
                            <tr>
                                <th>{{ trans('home.Product') }}</th>
                                <th>{{ trans('home.sale_plan') }}</th>
                                <th>{{ trans('home.sale_real') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $p)
                                <tr role="row" id="">
                                    <td>{{$p->name}}</td>
                                    <td>{{$p->sales_plan}}</td>
                                    <td>{{$p->sales_real}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif


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
            zoom: 11,
            fullscreenControl: true
        });
        var Totalbounds = new google.maps.LatLngBounds();
                @foreach($locations as $location)
        var c = "{{$location->coordinates}}";
        var coordinate = JSON.parse(c);
        @php
            $border_color = '#333';
            $background_color = '#333';
             if($area->border_color){
                    $border_color = $area->border_color;
              }
             if($area->background_color){
                    $background_color = $area->background_color;
                }
        @endphp
        if (coordinate) {
            var bounds = new google.maps.LatLngBounds();
            for (i = 0; i < coordinate.length; i++) {
                var c = coordinate[i];
                bounds.extend(new google.maps.LatLng(c[0], c[1]));
                Totalbounds.extend(new google.maps.LatLng(c[0], c[1]));
            }
            var path = coordinate;
//            map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
            map.drawOverlay({
                lat: bounds.getCenter().lat(),
                lng: bounds.getCenter().lng(),
                content: '<div class="overlay">{{$locat->name}}</div>'
            });
            var infoWindow{{$location->id}} = new google.maps.InfoWindow({
                content: "<p>{{$location->name}}</p>"
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
                    infoWindow{{$location->id}}.setPosition(position);
                    infoWindow{{$location->id}}.open(map.map);
                },
                mouseout: function (clickEvent) {
                    if (infoWindow{{$location->id}}) {
                        infoWindow{{$location->id}}.close();
                    }
                }
            });

            polygonArray["{{$location->id}}"] = polygon;
        }
        @endforeach
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
            title:  "{{$agent->name}}",
            click: function (e) {
                infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                infoWindow.open(map.map);
            }
        });
        @endforeach
       map.fitBounds(Totalbounds);
        map.panToBounds(Totalbounds);
    });
    //    $('#change-button-size-map').on('change.bootstrapSwitch', function(e, state) {
    //        if(e.target.checked){
    //            var url  = window.location.origin + window.location.pathname;
    //            console.log(url);
    //            url = url+'?size-map=fullscreen';
    //            window.location.href = url;
    //        }else{
    //            var url  = window.location.origin + window.location.pathname;
    //            url = url+'?size-map=landscape';
    //            window.location.href = url;
    //        }
    //    });
</script>

@endpush
