@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2> {{(isset($agent) ? trans('home.edit'). "  ". trans('home.agency') . " : ".$agent->name : trans('home.create').' '. trans('home.agency') )}}</h2>
            </div>

        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <!-- Main content -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-7">

                    <form method="post" id="geocoding_form">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" id="address" name="address" placeholder="{{ trans('home.import_position') }}" class="form-control">
                                @if ($errors->has('lat') or $errors->has('lng'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('lng') }}</div>
                                @endif

                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                            </div>
                        </div>
                    </form>
                    <div class="panel panel-flat">
                        <div class="table-responsive">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <form
                            @if(isset($agent))
                            action="{{route('Admin::map@editAgent',['id' => $agent->id])}}"
                            @else
                            action="{{route('Admin::map@addMapAgencyPost')}}"
                            @endif


                            method="POST">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ trans('home.name') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control name" name="name" value="{{(isset($agent) ? @$agent->name : old('name'))}}" placeholder="Nhập tên vùng địa lý">
                        </div>
                        @if ($errors->has('name'))
                            <div class="form-control-feedback">
                                <i class="icon-notification2"></i>
                            </div>
                            <div class="help-block">{{ $errors->first('name') }}</div>
                        @endif
                        <input type="hidden" class="form-control " id="lat" name="lat" value="{{(isset($agent) ? @$agent->lat : old('lat'))}}">
                        <input type="hidden" class="form-control " id="lng" name="lng" value="{{(isset($agent) ? @$agent->lng : old('lng'))}}">
                        <div class="clearfix"></div>
                    </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('home.address') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control " name="address" value="{{(isset($agent) ? @$agent->address : old('address'))}}" placeholder="Nhập địa chỉ">
                            </div>
                            @if ($errors->has('address'))
                                <div class="form-control-feedback">
                                    <i class="icon-notification2"></i>
                                </div>
                                <div class="help-block">{{ $errors->first('address') }}</div>
                            @endif

                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('home.code') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control code" name="code" value="{{(isset($agent) ? @$agent->code : old('code'))}}" placeholder="Nhập mã đại lý">
                            </div>
                            @if ($errors->has('code'))
                                <div class="form-control-feedback">
                                    <i class="icon-notification2"></i>
                                </div>
                                <div class="help-block">{{ $errors->first('code') }}</div>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group {{ $errors->has('user_id') ? 'has-error has-feedback' : '' }}">
                            <label for="name" class="control-label text-semibold col-md-3">{{ trans('home.manager') }}</label>
                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Nhân viên quản Lý"></i>
                            <div class="col-md-9">
                            <select name="manager_id" class="users form-control">
                                <option value="">{{ '--'. trans('home.select'). ' '. trans('home.manager') .'--' }}</option>
                                @foreach($users as $key => $value)
                                    <option value="{{$value->id}}" @if( isset($agent) and $agent->manager_id == $value->id) selected  @elseif(old('manager_id') == $value->id) selected @endif >{{ $value->name}}-{{$value->positionText}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('manager_id'))
                                <div class="form-control-feedback">
                                    <i class="icon-notification2"></i>
                                </div>
                                <div class="help-block">{{ $errors->first('manager_id') }}</div>
                            @endif
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        {{--<div class="form-group {{ $errors->has('area_id') ? 'has-error has-feedback' : '' }}">--}}
                            {{--<label for="name" class="control-label text-semibold col-md-3">{{ trans('home.place') }}</label>--}}
                            {{--<div class="col-md-9">--}}
                            {{--<select name="area_id" class="places form-control" id ="locations" style="width:100%">--}}
                                {{--@if(isset($areas))--}}
                                    {{--@foreach($areas as $key => $value)--}}
                                        {{--<option value="{{$value->id}}" @if(isset($agent) and $agent->area_id == $value->id) selected @elseif(old('area_id') == $value->id) selected @endif>{{ $value->name }}</option>--}}
                                    {{--@endforeach--}}
                                {{--@endif--}}
                            {{--</select>--}}

                            {{--@if ($errors->has('area_id'))--}}
                                {{--<div class="form-control-feedback">--}}
                                    {{--<i class="icon-notification2"></i>--}}
                                {{--</div>--}}
                                {{--<div class="help-block">{{ $errors->first('area_id') }}</div>--}}
                            {{--@endif--}}

                            {{--</div>--}}
                            {{--<div class="clearfix"></div>--}}
                        {{--</div>--}}
                        <div class="form-group {{ $errors->has('rank') ? 'has-error has-feedback' : '' }}">
                            <label for="name" class="control-label text-semibold col-md-3">{{ trans('home.rank') }}</label>
                            <div class="col-md-9">
                                <select name="rank" class="form-control" style="width:100%">
                                    <option value="0">{{ '--'. trans('home.select'). ' '. trans('home.rank') .'--' }}</option>
                                        @foreach(\App\Models\Agent::$rankText as $key => $value)
                                            <option value="{{$key}}" @if(isset($agent) and $agent->rank == $key) selected @endif>{{ $value }}</option>
                                        @endforeach

                                </select>

                                @if ($errors->has('rank'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('rank') }}</div>
                                @endif

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group {{ $errors->has('attribute') ? 'has-error has-feedback' : '' }}">
                            <label for="name" class="control-label text-semibold col-md-3">{{ trans('home.attribute') }}</label>
                            <div class="col-md-9">
                                <select name="attribute" class="form-control" style="width:100%">
                                    <option value="0">{{ '--'. trans('home.select'). ' '. trans('home.attribute') .'--' }}</option>
                                        <option value="{{\App\Models\Agent::agentNew}}" @if(isset($agent) and $agent->attribute == \App\Models\Agent::agentNew) selected @elseif(old('attribute') == \App\Models\Agent::agentNew) selected @endif>Đại lý mới</option>
                                        <option value="{{\App\Models\Agent::agentRival}}" @if(isset($agent) and $agent->attribute == \App\Models\Agent::agentRival) selected @elseif(old('attribute') == \App\Models\Agent::agentRival) selected @endif>Đại lý đối thủ</option>

                                </select>

                                @if ($errors->has('attribute'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('attribute') }}</div>
                                @endif

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        @if(isset($agent))
                            <div class="form-group ">
                                <label for="name" class="control-label text-semibold col-md-3">Icon</label>
                                <div class="col-md-9">
                                    <img src="{{asset($agent->icon)}}"  alt="" width="100">


                                </div>
                                <div class="clearfix"></div>
                            </div>

                            @endif
                        {{--<div class="form-group {{ $errors->has('area_id') ? 'has-error has-feedback' : '' }}">--}}
                            {{--<label for="name" class="control-label text-semibold col-md-3">Icon</label>--}}
                            {{--<div class="col-md-9">--}}

                                {{--<input type="text" style='display:none'  @if(isset($agent) and $agent->icon) value="{{$agent->icon}}" @endif name="icon" id="Image" />--}}
                                {{--<div  value="Duyệt ảnh" class='button_chooseImage btn btn-info' onclick="BrowseServer();">Chọn ảnh</div>--}}
                                {{--@if(isset($agent) and $agent->icon)--}}
                                {{--<img src="{{asset($agent->icon)}}" alt=""   class='col-md-4' id='imageAvatar'>--}}
                                {{--@else--}}
                                    {{--<img src="" alt="" style="display:none"  class='col-md-4' id='imageAvatar'>--}}
                                    {{--@endif--}}

                                {{--@if ($errors->has('area_id'))--}}
                                    {{--<div class="form-control-feedback">--}}
                                        {{--<i class="icon-notification2"></i>--}}
                                    {{--</div>--}}
                                    {{--<div class="help-block">{{ $errors->first('area_id') }}</div>--}}
                                {{--@endif--}}

                            {{--</div>--}}
                            {{--<div class="clearfix"></div>--}}
                        {{--</div>--}}



                        <div class="col-md-9 col-md-offset-3 btn-submit-add-map">
                            <button type="submit" class="btn btn-info">{{isset($agent) ? trans('home.update') : trans('home.create')}}</button>
                        </div>

                    </form>
                </div>

                {{--<button id="search" class="btn green">Tìm kiếm</button>--}}
            </div>


        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
@endsection
@push('scripts_foot')
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
<script src="{{asset('/ckfinder/ckfinder.js')}}"></script>
@endpush

@push('scripts')

<script type="text/javascript">
    var map;
    var markers = [];
    var polygonArray = [];
    function BrowseServer() {
        var finder = new CKFinder();
        //finder.basePath = '../';

        finder.selectActionFunction = SetFileField;
        finder.popup();
    }
    function SetFileField(fileUrl) {
        document.getElementById('Image').value = fileUrl;
        document.getElementById('imageAvatar').style.display = 'block';
        document.getElementById('imageAvatar').src = fileUrl;
    }

    $(document).ready(function () {

        $('.users').select2();
        $(".places").select2({
            'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
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
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
        });

        map.addListener('click', function (e) {

            var ll = {lat: e.latLng.lat(), lng: e.latLng.lng()};
            map.removeMarkers();

            map.addMarker({
                lat: ll.lat,
                lng: ll.lng,
                title: 'Lima'
            });
            $('#lat').val(ll.lat);
            $('#lng').val(ll.lng);

        });
        @if(isset($agent))

        map.removeMarkers();
        var image = {
            url: "{{$agent->icon}}", // image is 512 x 512
            size: new google.maps.Size(22, 32)
        };
        map.addMarker({
            lat: "{{$agent->lat}}",
            lng: "{{$agent->lng}}",
            @if($agent->icon)
                icon:image
            @endif
        });
        map.setCenter("{{$agent->lat}}", "{{$agent->lng}}");
        @endif
        $('#geocoding_form').submit(function(e){
            e.preventDefault();
            map.removeMarkers();
            GMaps.geocode({
                address: $('#address').val().trim(),
                callback: function(results, status){
                    if(status=='OK'){
                        var latlng = results[0].geometry.location;
                        map.setCenter(latlng.lat(), latlng.lng());
                        map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng()
                        });
                        $('#lat').val(latlng.lat());
                        $('#lng').val(latlng.lng());
                    }
                }
            });
        });


        $('#locations').on("select2:select", function (e) {
            var id = e.params.data.id;
            var coordinates = e.params.data.coordinates;
            var coordinate = JSON.parse(coordinates);
            if (coordinate) {
                var bounds = new google.maps.LatLngBounds();
                for (i = 0; i < coordinate.length; i++) {
                    var c = coordinate[i];
                    bounds.extend(new google.maps.LatLng(c[0], c[1]));
                }
                var path = coordinate;
                map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                var infoWindow = new google.maps.InfoWindow({
                    content: 'you clicked a polyline'
                });
                polygon = map.drawPolygon({
                    paths: path,
                    strokeColor: '#333',
                    strokeOpacity: 0.5,
                    strokeWeight: 1,
                    fillColor: '#333',
                    fillOpacity: 0.6,
                    mouseover: function (e) {
                        var ll = {lat: e.latLng.lat(), lng: e.latLng.lng()};
                        map.removeMarkers();

                        map.addMarker({
                            lat: ll.lat,
                            lng: ll.lng,
                            title: 'Lima'
                        });
                        $('#lat').val(ll.lat);
                        $('#lng').val(ll.lng);
                    },
                    mouseout: function (e) {
                        if (infoWindow) {
                            infoWindow.close();
                        }
                    }
                });

                polygonArray[id] = polygon;
            }
        });

        $('#locations').on("select2:unselect", function (e) {

            var id = e.params.data.id;
            var coordinates = e.params.data.coordinates;
            if(coordinates == undefined){
                var coordinates = e.params.data.element.attributes.getNamedItem('data-coordinates').value;
            }
            var coordinate = JSON.parse(coordinates);
            if (coordinate) {
                map.removePolygon(polygonArray[id]);
            }
        });

    });
</script>
@endpush
