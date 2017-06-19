@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ trans('home.managerMap') }}</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Admin::map@addMap')}}" class="btn btn-primary"><i class="icon-add"></i>{{ trans('home.addLocation')}}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">
            @if (session('success'))
                <div class="alert bg-success alert-styled-left">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                    {{ session('success') }}
                </div>
            @endif
            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="maps-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>{{ trans('home.name') }}</th>
                    <th>Created_At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
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
        var datatable = $("#maps-table").DataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            "pageLength": 10,
            ajax: {
                url: '{!! route('Admin::map@datatables') !!}',
                data: function (d) {
                    //
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name', searchable: true},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

    } );

</script>
@endpush
