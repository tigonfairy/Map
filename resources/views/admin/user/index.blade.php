@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Thành viên</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Admin::user@add')}}" class="btn btn-primary"><i class="icon-add"></i> Thêm thành viên</a>

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
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="users-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Email</th>
                                <th>Group</th>
                                <th>Created_At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--@foreach($users as $row)--}}
                                {{--<tr role="row" id="">--}}
                                    {{--<td>{{$row->id}}</td>--}}

                                    {{--<td>{{$row->email}}</td>--}}
                                    {{--<td>@foreach ($row->roles as $role)--}}
                                            {{--{{$role->name}}--}}
                                        {{--@endforeach--}}
                                    {{--</td>--}}
                                    {{--<td>{{$row->created_at}}</td>--}}
                                    {{--<td><a href="{{route('Admin::user@edit',[$row->id])}}"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a>--}}
                                        {{--<a onclick="return xoaCat();" href="{{ route('Admin::user@delete', [$row->id]) }}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
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
        var datatable = $("#users-table").DataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            "pageLength": 10,
            ajax: {
                url: '{!! route('Admin::user@datatables') !!}',
                data: function (d) {
                    //
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'email', name: 'email'},
                {data: 'group', name: 'group'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

    } );

</script>
@endpush
