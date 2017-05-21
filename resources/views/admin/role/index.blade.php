@extends('admin')

@section('content')
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>Group</h2>
    </div>

    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="{{ route('Admin::role@add') }}" class="btn btn-primary"><i class="icon-add"></i> Thêm</a>

      </div>
    </div>
  </div>
</div>
<!-- /page header -->
<!-- Page container -->
<div class="page-container">

    <!-- Main content -->
    <div class="content-wrapper">

      @if (session('success'))
        <div class="alert bg-success alert-styled-left">
          <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
          {{ session('success') }}
        </div>
      @endif

          <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="roles-table" >
            <thead>
            <tr>
              <th>Id</th>
              <th>Name</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $row)
              <tr role="row" id="">
                <td>{{$row->id}}</td>
                <td>{{$row->name}}</td>
                <td><a href="{{ route('Admin::role@edit', [$row->id]) }}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a> <a onclick="return xoaCat();" href="{{ route('Admin::role@delete', [$row->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
              </tr>
            @endforeach
            </tbody>
          </table>
    </div>
    <!-- /main content -->
  </div>

<!-- /page container -->


@endsection

@push('scripts')
<script>
  function xoaCat(){
    var conf = confirm("Bạn chắc chắn muốn xoá?");
    return conf;
  }

  $(document).ready(function() {
      $('#roles-table').DataTable({
          "bInfo" : false,
          "columns":[
              {
                  "sortable": true
              },
              {
                  "sortable": true
              },
              {
                  "sortable": false
              },
          ]
      });
  } );

</script>
@endpush

