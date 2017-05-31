@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Tạo vùng quản lý theo nhân viên</h2>
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
                <div class="col-md-offset-2 col-md-8">
                    @if (session('success'))
                        <div class="alert bg-success alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                        class="sr-only">Close</span></button>
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <form method="POST" action="{{route('Admin::map@addMapUserPost')}}">
                            {{ csrf_field() }}
                                <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Tên</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của vùng"></i>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$permission->id }}" />
                                    @if ($errors->has('name'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            <!---------- Manager ID------------>
                                <div class="form-group {{ $errors->has('user_id') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Nhân viên quản Lý</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                       data-content="Nhân viên quản Lý"></i>
                                    <select name="manager_id" class="users">
                                        <option value="">-- Chọn quản lý --</option>
                                        @foreach($users as $key => $value)
                                            <option value="{{$value->id}}">{{ $value->email }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('user_id'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('user_id') }}</div>
                                    @endif
                                </div>

                                <!---------- Place ID------------>
                                <div class="form-group {{ $errors->has('place') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Vùng quản lý</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                       data-content="Vùng quản lý"></i>
                                    <select name="place[]" id="locations" class="places" multiple style="width:100%">
                                        <option value="">-- Chọn vùng quản lý --</option>
                                        @foreach($places as $key => $value)
                                            <option data-coordinate="{{ $value->coordinates }}" id="{{$value->id}}"
                                                    value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('place'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('place') }}</div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="panel panel-flat">
                                        <div class="table-responsive">
                                            <div id="map"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Màu nền</label>
                                        <div class="col-md-6">
                                            <div class="input-group color colorpicker-default" data-color="#3865a8"
                                                 data-color-format="rgba">
                                                <input type="text" class="form-control" value="#3865a8"
                                                       name="background_color" >
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button"><i
                                                                style="background-color: #3865a8;"></i>&nbsp;</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Màu của border</label>
                                        <div class="col-md-6">
                                            <div class="input-group color colorpicker-default" data-color="#3865a8"
                                                 data-color-format="rgba">
                                                <input type="text" class="form-control" value="#3865a8"
                                                       name="border_color" >
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button"><i
                                                                style="background-color: #3865a8;"></i>&nbsp;</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
@endsection
@push('scripts_foot')

<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
@endpush

@push('scripts')

<script type="text/javascript">
    var map;
    var drawingManager;
    var shapes = [];
    var patch = [];
    var markers = [];
    var polygonArray = [];
    $(document).ready(function () {
        Array.prototype.insert = function (index, item) {
            this.splice(index, 0, item);
        };
        $(".users").select2();
        var places = $(".places").select2({});
        var opts = [];
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
        });
        $('#locations').on("select2:select", function (e) {
            var id = e.params.data.element.attributes.getNamedItem('id').value;
            var coordinates = e.params.data.element.attributes.getNamedItem('data-coordinate').value;
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

                polygonArray[id] = polygon;
            }
        });
        $('#locations').on("select2:unselect", function (e) {
            var id = e.params.data.element.attributes.getNamedItem('id').value;
            var coordinates = e.params.data.element.attributes.getNamedItem('data-coordinate').value;
            var coordinate = JSON.parse(coordinates);
            if (coordinate) {
                map.removePolygon(polygonArray[id]);
            }
        });
    });
</script>


@endpush