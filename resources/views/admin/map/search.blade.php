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
                                <select name="search_type" class="search_type form-control">
                                    <option value="">-- Chọn loại search --</option>
                                    <option value="1">Theo vùng</option>
                                    <option value="2">Theo giám sát vùng</option>
                                    <option value="3">Theo nhân viên kinh doanh</option>
                                    <option value="4">Theo đại lý</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="data_search" class="data_search form-control">
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
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
        });

        $( ".search_type" ).change(function() {
            var search_type = $(this).val();
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
            $.ajax({
                type: "GET",
                url: "{{ route('Admin::map@dataSearch') }}",
                data: $('#geocoding_form').serialize(),
                cache: false,
                success: function(data){

                }
            });
        });
        
        function getListAreas() {
            
        }

        function getListSaleAdmins() {

        }

        function getListSaleMans() {

        }

        function getListAgents() {

        }
    });
</script>
@endpush
