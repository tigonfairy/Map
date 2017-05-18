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
      <div class="container">
        <div class="col-md-6 col-md-offset-3">
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
    $(document).ready(function(){
        map = new GMaps({
            div: '#map',
            lat: -12.043333,
            lng: -77.028333,
            width : "100%",
            height : '500px'
        });
        var path = [
            [-12.040397656836609,-77.03373871559225],
            [-12.040248585302038,-77.03993927003302],
            [-12.050047116528843,-77.02448169303511],
            [-12.044804866577001,-77.02154422636042]
        ];

        polygon = map.drawPolygon({
            paths: path,
            strokeColor: '#BBD8E9',
            strokeOpacity: 1,
            strokeWeight: 3,
            fillColor: '#BBD8E9',
            fillOpacity: 0.6
        });
    });
</script>

@endpush
