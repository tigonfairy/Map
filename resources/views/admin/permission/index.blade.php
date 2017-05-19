@extends('admin')

@section('content')
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>Permission</h2>
    </div>

    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="{{ route('Admin::permission@add') }}" class="btn btn-primary"><i class="icon-add"></i> Thêm</a>

      </div>
    </div>
  </div>
</div>
<!-- /page header -->
<!-- Page container -->
<div class="page-container">
  <!-- Page content -->

    <!-- Main content -->
    <div class="content-wrapper">
      @if (session('success'))
        <div class="alert bg-success alert-styled-left">
          <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
          {{ session('success') }}
        </div>
      @endif
          <table  class="table table-striped table-bordered" cellspacing="0" width="100%" id="permissions-table">
            <thead>
            <tr>
              <th>Id</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($permissions as $row)
              <tr role="row" id="">
                <td>{{$row->id}}</td>
                <td>{{$row->description}}</td>
                <td><a href="{{ route('Admin::permission@edit', [$row->id]) }}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a> <a onclick="return xoaCat();" href="{{ route('Admin::permission@delete', [$row->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
              </tr>
            @endforeach
            </tbody>
          </table>

    </div>
    <!-- /main content -->
  </div>
  <!-- /page content -->

<!-- /page container -->


@endsection

@push('scripts')
<script>
  function xoaCat(){
    var conf = confirm("Bạn chắc chắn muốn xoá?");
    return conf;
  }
  $(document).ready(function() {
      $('#permissions-table').DataTable({
          "bInfo" : false,
          "columns":[
              {
                  "sortable": true
              },
              {
                  "sortable": false
              },
              {
                  "sortable": false
              },
          ]
      });
  } );


</script>
@endpush
