@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Tạo đại lý</h2>
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
                                <input type="text" id="address" name="address" placeholder="Nhập vị trí" class="form-control">
                                @if ($errors->has('lat') or $errors->has('lng'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('lng') }}</div>
                                @endif

                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info">Search</button>
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
                    <form action="{{route('Admin::map@addMapAgencyPost')}}" method="POST">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tên đại lý</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control name" name="name" value="{{old('name')}}" placeholder="Nhập tên đại lý">
                        </div>
                        @if ($errors->has('name'))
                            <div class="form-control-feedback">
                                <i class="icon-notification2"></i>
                            </div>
                            <div class="help-block">{{ $errors->first('name') }}</div>
                        @endif
                        <input type="hidden" class="form-control " id="lat" name="lat" value="{{old('lat')}}">
                        <input type="hidden" class="form-control " id="lng" name="lng" value="{{old('lng')}}">
                        <div class="clearfix"></div>
                    </div>

                        <div class="form-group {{ $errors->has('user_id') ? 'has-error has-feedback' : '' }}">
                            <label for="name" class="control-label text-semibold col-md-3">Nhân viên quản Lý</label>
                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Nhân viên quản Lý"></i>
                            <div class="col-md-9">

                            <select name="manager_id" class="users form-control">
                                <option value="">-- Chọn quản lý --</option>
                                @foreach($users as $key => $value)
                                    <option value="{{$value->id}}" @if(old('manager_id') == $value->id) selected @endif>{{ $value->email}}</option>
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

                        <div class="form-group {{ $errors->has('user_id') ? 'has-error has-feedback' : '' }}">
                            <label for="name" class="control-label text-semibold col-md-3">Trực thuộc vùng</label>
                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Nhân viên quản Lý"></i>
                            <div class="col-md-9">

                                <select name="area_id" class="areas form-control">
                                    <option value="">-- Chọn vùng trực thuộc --</option>
                                    @foreach($areas as $key => $value)
                                        <option value="{{$value->id}}" @if(old('area_id') == $value->id) selected @endif>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('area_id'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('area_id') }}</div>
                                @endif

                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="row btn-submit-add-map">
                            <button type="submit" class="btn btn-info">Tạo</button>
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
@endpush

@push('scripts')

<script type="text/javascript">
    var map;
    var markers = [];

    $(document).ready(function () {
        $('.users').select2();
        $('.areas').select2();
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
            markers = [];
            map.addMarker({
                lat: ll.lat,
                lng: ll.lng,
                title: 'Lima',
            });
            $('#lat').val(ll.lat);
            $('#lng').val(ll.lng);

        });
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

    });
</script>
@endpush
