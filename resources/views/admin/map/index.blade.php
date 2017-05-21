@extends('admin')
@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Map</h2>
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
                <div class="col-md-6 col-md-offset-3">
                    <select id="province">
                        <option value="">Chọn tỉnh</option>
                        @foreach($provinces as $provine)
                            <option value="{{ $provine }}">{{ $provine }}</option>
                        @endforeach
                    </select>
                    <select id="district">
                        <option value="">Chọn huyện</option>
                    </select>
                    <button id="search">Tìm kiếm</button>
                </div>
            </div>

            <div class="panel panel-flat">
                <div class="table-responsive">
                    <div id="map"></div>
                </div>

            </div>
        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
@endsection

@push('scripts')
<script type="text/javascript">
    var map;
    $(document).ready(function () {

        $('#province').on('change', '', function (e) {
            var province = this.value;
            $.ajax({
                url: '{{ url('maps/province/districts') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    province: province
                },
                success: function (districts) {
                    $("#district").html('');
                    $.each(districts, function (key, district) {
                        $("#district").append('<option value="' + district + '">' + district + '</option>')
                    })
                },
                error: function () {

                }
            });
        });

        $("#search").click(function () {
            var province = $("#province").val();
            var district = $("#district").val();
            $.ajax({
                url: '{{ url('maps/province/district/coordinates') }}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}',
                    province: province,
                    district: district
                },
                success: function (coordinates) {
                    var coordinate = JSON.parse(coordinates[0]);
                    var middle = coordinate[Math.round((coordinate.length - 1) / 2)];
                    map = new GMaps({
                        div: '#map',
                        lat: middle[0],
                        lng: middle[1],
                        width: "100%",
                        height: '500px',
                        zoom: 9
                    });
                    var path = coordinate;

                    polygon = map.drawPolygon({
                        paths: path,
                        strokeColor: '#333',
                        strokeOpacity: 0.5,
                        strokeWeight: 1,
                        fillColor: '#ffcccc',
                        fillOpacity: 0.6,
                        mouseover: function () {
//                            this.infowindow.setContent(contentString);
//                            this.infowindow.open(map);

//                            this.setOptions({
//                                fillOpacity: 1,
//                                fillColor: '#333'
//                            });
                        }
                    });
                },
                error: function () {

                }
            });
        });
    });
</script>

@endpush
