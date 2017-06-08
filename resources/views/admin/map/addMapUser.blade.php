@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{(isset($area)? 'Sửa vùng quản lý :'.$area->name : ' Tạo vùng quản lý theo nhân viên')}}</h2>
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
                    @include('admin.flash')
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <form method="POST"
                                  @if(isset($area))
                                  action="{{route('Admin::map@editMapUser',['id' => $area->id])}}"
                                          @else
                                  action="{{route('Admin::map@addMapUserPost')}}"
                                          @endif


                            >
                            {{ csrf_field() }}
                                <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Tên</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của vùng"></i>
                                    <input type="text" id="name" name="name" class="form-control" value="{{(isset($area) ? @$area->name : old('name'))}}" />
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
                                            <option value="{{$value->id}}"  @if( isset($area) and $area->manager_id == $value->id) selected  @elseif(old('manager_id') == $value->id) selected @endif >{{ $value->email }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('manager_id'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('manager_id') }}</div>
                                    @endif
                                </div>

                                <!---------- Place ID------------>
                                <div class="form-group {{ $errors->has('place') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Vùng quản lý</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                       data-content="Vùng quản lý"></i>

                                    <select name="place[]" id="locations" class="places" multiple style="width:100%">
                                        @if(isset($areaAddress) )
                                            @foreach($areaAddress as $address)
                                                <option data-coordinates="{{ $address->coordinates }}" id="{{$address->id}}"
                                                        value="{{ $address->id }}" selected>{{ $address->name }}</option>
                                                @endforeach
                                            @endif
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
                                            <div class="input-group color colorpicker-default" data-color="{{isset($area) ? $area->background_color : '#3865a8'}}"
                                                 data-color-format="rgba">
                                                <input type="text" class="form-control" value="{{isset($area) ? $area->background_color : '#3865a8'}}"
                                                       name="background_color" >
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button"><i
                                                                style="background-color: {{isset($area) ? $area->background_color : '#3865a8'}};"></i>&nbsp;</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Màu của border</label>
                                        <div class="col-md-6">
                                            <div class="input-group color colorpicker-default" data-color="{{isset($area) ? $area->border_color : '#3865a8'}}"
                                                 data-color-format="rgba">
                                                <input type="text" class="form-control" value="{{isset($area) ? $area->border_color : '#3865a8'}}"
                                                       name="border_color" >
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button"><i
                                                                style="background-color: {{isset($area) ? $area->border_color : '#3865a8'}};"></i>&nbsp;</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">{{isset($area) ? 'Cập nhật' : 'Thêm mới'}}</button>
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
    var polygonArray = [];
    $(document).ready(function () {

        //load ajax selct2
        $(".places").select2({
            'placeholder' : 'Nhập từ khóa để tìm kiếm vị trí',
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


        $(".users").select2();
        Array.prototype.insert = function (index, item) {
            this.splice(index, 0, item);
        };

        var opts = [];
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
        });
        @if(isset($areaAddress))
            @foreach($areaAddress as $address)
        var coordinate = JSON.parse("{{$address->coordinates}}");
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

            polygonArray["{{$address->id}}"] = polygon;
        }
            @endforeach
    @endif

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