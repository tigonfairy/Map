@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ trans('home.search') }}</h2>
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
                <div class="col-md-12">

                    <form method="post" id="geocoding_form">
                        <div class="row">
                            <div class="col-md-2">
                                <select class="search_type form-control">
                                    <option value="">-- Chọn loại search --</option>
                                    <option value="1">Theo vùng</option>
                                    <option value="2">Theo giám sát vùng</option>
                                    <option value="3">Theo nhân viên kinh doanh</option>
                                    <option value="4">Theo đại lý</option>
                                </select>
                            </div>
                                <input type="hidden" name="type_search" value="" id="type_search"/>
                            <div class="col-md-2">
                                <select name="data_search" class="data_search" id="locations" style="width:100%">
                                </select>
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
        $(".data_search").select2({
            allowClear: true,
        });

        $( ".search_type" ).change(function() {
            var search_type = $(this).val();
            $(".data_search").empty();
            if (search_type == 1) {
                getListAreas();
            } else if (search_type == 2) {
                getListSaleAdmins();
            } else if (search_type == 3) {
                getListSaleMans();
            } else if (search_type == 4) {
                getListAgents();
            }
        });
        
        $('#geocoding_form').submit(function(e){
            e.preventDefault();
            var type_search = $("#type_search").val();
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
                        zoom: 11
                    });

                    if (type_search == 'areas' ) {
                        showDataAreas(data);
                    }
                    if (type_search == 'sale_admins' || type_search == 'sale_mans') {
                        showDataSales(data);
                    }
                    if (type_search == 'agents') {
                        showDataAgents(data);
                    }
                }
            });
        });
        
        function getListAreas() {
            $("#type_search").val('areas');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.place') .' --'}}",
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
        }

        function showDataAreas(data) {
            var polygonArray = [];
            $.map(data.locations, function (item) {
                var c = item.coordinates;
                var coordinate = JSON.parse(c);
                var border_color = '#333';
                var background_color = '#333';
                if(data.area.border_color){
                    border_color = data.area.border_color;
                }
                if(data.area.background_color){
                    background_color = data.area.background_color;
                }
                if (coordinate) {
                    var bounds = new google.maps.LatLngBounds();
                    for (i = 0; i < coordinate.length; i++) {
                        var c = coordinate[i];
                        bounds.extend(new google.maps.LatLng(c[0], c[1]));
                    }
                    var path = coordinate;
                    map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                    var infoWindow = new google.maps.InfoWindow({
                        content: "<p>" + item.name + "</p>"
                    });
                    polygon = map.drawPolygon({
                        paths: path,
                        strokeColor: border_color,
                        strokeOpacity: 1,
                        strokeWeight: 1,
                        fillColor: background_color,
                        fillOpacity: 0.4,
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
                    polygonArray[item.id] = polygon;
                }
            });

            $.map(data.agents, function (item) {
                var contentString = '<div id="content">' +
                    '<p id="name">' + item.name + '</p>' +
                    '</div>';

                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });

                map.addMarker({
                    lat: item.lat,
                    lng: item.lng,
                    title:  item.name,
                    click: function (e) {
                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                        infoWindow.open(map.map);
                    }
                });
            });
        }

        function getListSaleAdmins() {
            $("#type_search").val('sale_admins');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getListAdmins')}}",
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
                                    id: item.id,
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
        }

        function getListSaleMans() {
            $("#type_search").val('sale_mans');
            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getListmans')}}",
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
                                    id: item.id,
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
        }

        function showDataSales(data) {
            var polygonArray = [];
            $.map(data.locations, function (location) {
                $.map(location, function (item) {
                    var c = item.coordinates;
                    var coordinate = JSON.parse(c);
                    var border_color = '#333';
                    var background_color = '#333';

                    if (coordinate) {
                        var bounds = new google.maps.LatLngBounds();
                        for (i = 0; i < coordinate.length; i++) {
                            var c = coordinate[i];
                            bounds.extend(new google.maps.LatLng(c[0], c[1]));
                        }
                        var path = coordinate;
                        map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                        var infoWindow = new google.maps.InfoWindow({
                            content: "<p>" + item.name + "</p>"
                        });
                        polygon = map.drawPolygon({
                            paths: path,
                            strokeColor: border_color,
                            strokeOpacity: 1,
                            strokeWeight: 1,
                            fillColor: background_color,
                            fillOpacity: 0.4,
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
                        polygonArray[item.id] = polygon;
                    }
                });
            });

            $.map(data.agents, function (item) {

                var contentString = '<div id="content">' +
                    '<p id="name">' + item.name + '</p>' +
                    '</div>';

                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });

                map.addMarker({
                    lat: item.lat,
                    lng: item.lng,
                    title:  item.name,
                    click: function (e) {
                        infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                        infoWindow.open(map.map);
                    }
                });
            });
        }

        function getListAgents() {
            $("#type_search").val('agents');

            $(".data_search").select2({
                'placeholder' : "{{'-- '. trans('home.select'). ' '. trans('home.agency') .' --'}}",
                ajax : {
                    url : "{{route('Admin::Api::sale@getListAgents')}}",
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
                            results: $.map(data.data, function (item) {

                                return {
                                    text: item.name,
                                    id: item.id,
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
        }

        function showDataAgents(data) {
            var polygonArray = [];
            $.map(data.locations, function (item) {
                var c = item.coordinates;
                var coordinate = JSON.parse(c);
                var border_color = '#333';
                var background_color = '#333';
                if(data.area.border_color){
                    border_color = data.area.border_color;
                }
                if(data.area.background_color){
                    background_color = data.area.background_color;
                }
                if (coordinate) {
                    var bounds = new google.maps.LatLngBounds();
                    for (i = 0; i < coordinate.length; i++) {
                        var c = coordinate[i];
                        bounds.extend(new google.maps.LatLng(c[0], c[1]));
                    }
                    var path = coordinate;
                    map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                    var infoWindow = new google.maps.InfoWindow({
                        content: "<p>" + item.name + "</p>"
                    });
                    polygon = map.drawPolygon({
                        paths: path,
                        strokeColor: border_color,
                        strokeOpacity: 1,
                        strokeWeight: 1,
                        fillColor: background_color,
                        fillOpacity: 0.4,
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
                    polygonArray[item.id] = polygon;
                }
            });


            var contentString = '<div id="content">' +
                    '<p id="name">' + data.agents.name + '</p>' +
                    '</div>';

            var infoWindow = new google.maps.InfoWindow({
                    content: contentString
            });

            map.addMarker({
                lat:  data.agents.lat,
                lng:  data.agents.lng,
                title:   data.agents.name,
                click: function (e) {
                    infoWindow.setPosition({lat: e.position.lat(), lng: e.position.lng()});
                    infoWindow.open(map.map);
                }
            });
        }

    });
</script>
@endpush
