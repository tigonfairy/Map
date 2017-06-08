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
                <div class="col-md-12">

                    <form method="post" id="geocoding_form">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="area_id" class="areas form-control">
                                    <option value="">-- Chọn vùng trực thuộc --</option>
                                    @foreach($areas as $key => $value)
                                        <option value="{{$value->id}}" @if(old('area_id') == $value->id) selected @endif>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="agent_id" class="agents form-control">
                                    <option value="">-- Chọn đại lý --</option>
                                    @foreach($agents as $key => $value)
                                        <option value="{{$value->id}}">{{ $value->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="manager_id" class="users form-control">
                                    <option value="">-- Chọn quản lý --</option>
                                    @foreach($users as $key => $value)
                                        <option value="{{$value->id}}" @if(old('manager_id') == $value->id) selected @endif>{{ $value->email}}</option>
                                    @endforeach
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
        $('.agents').select2();
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
        });

        $('#geocoding_form').submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ route('Admin::map@dataSearch') }}",
                data: $('#geocoding_form').serialize(),
                cache: false,
                success: function(data){

                }
            });
        });

    });
</script>
@endpush
