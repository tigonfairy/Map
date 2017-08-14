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
                    <a href="#import-user" class="btn btn-info" data-toggle="modal" id="btn-system-product">Thêm thành viên từ Excel</a>
                    <a href="{{asset('user_example.xlsx')}}" class="btn btn-success"  id="btn-system-product">Mẫu</a>
                    <a href="{{route('Admin::user@export')}}" class="btn btn-info"  id="btn-system-product">Export Excel</a>
                </div>


                <div id="divLoading"></div>
                <div class="modal fade bs-modal-lg" id="import-user" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Thêm thành viên bằng Excel</h4>
                            </div>
                            <form method="POST" action="{{ route('Admin::user@importExcel') }}"
                                  enctype="multipart/form-data" id="import_form">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">File</label>
                                                <div class="col-md-8">
                                                    <input type="file" class="file-excel form-control" name="file">
                                                </div>
                                            </div>

                                        </div>
                                        <p id="file" style="color:red;"></p>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn green" id = "import">Import</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
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
                                <th>Code</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Manager</th>
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
                {data: 'code', name: 'code'},
                {data: 'email', name: 'email'},
                {data: 'position', name: 'position'},
                {data: 'manager', name: 'manager'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $("#import").on("click", function () {
            $("div#divLoading").addClass('show');
            var form = $('#import_form');
            var data = new FormData(form[0]);
            $.ajax({
                headers: { 'X-CSRF-Token': $('input[name="_token"]').val() },
                url: $('#import_form').attr('action'),
                type: $('#import_form').attr('method'),
                data: data,
                processData: false,
                cache:false,
                contentType:false,
                dataType: 'JSON',
                success: function (res){
                    $("#file").text('');
                    if (res.status == 'success'){
                        $("div#divLoading").removeClass('show');
                        window.location.reload();
                    } else {
                        $.each(res.errors,function(index, value) {
                            $("div#divLoading").removeClass('show');
                            $("#"+index).text(value);
                        });
                    }
                }
            });
        });

    } );

</script>
@endpush
