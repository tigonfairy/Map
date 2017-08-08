@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ trans('home.list_agency') }}</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Admin::map@addAgency')}}" class="btn btn-primary"><i class="icon-add"></i> {{ trans('home.create_agency') }}</a>
                    <a href="#import-user" class="btn btn-info" data-toggle="modal" id="btn-system-product">Thêm đại lý từ Excel</a>
                    <a href="{{asset('user_example.xlsx')}}" class="btn btn-success"  id="btn-system-product">Mẫu</a>
                </div>
            </div>
        </div>
    </div>


    <div id="divLoading"></div>
    <div class="modal fade bs-modal-lg" id="import-user" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Thêm đại lý bằng Excel</h4>
                </div>
                <form method="POST" action="{{ route('Admin::map@importExcelAgent') }}"
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

    <div class="row">
        <form action="">
            <div class="col-xs-6 col-xs-offset-3">
                <input type="text" name="q" class="form-control" value="{{Request::input('q')}}"
                       placeholder="Nhập tên để tìm kiếm"/>

            </div>
            <button type="submit" class="btn btn-primary">{{ trans('home.search') }}</button>
        </form>
    </div>


    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">
            @include('admin.flash')
            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="users-table">
                <thead>
                <tr>
                    <th>{{ trans('home.name') }}</th>
                    <th>{{ trans('home.manager') }}</th>
                    <th>{{ trans('home.place') }}</th>
                    <th>{{ trans('home.action') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($agents as $agent)
                        <tr role="row" id="">
                            <td>{{$agent->name}}</td>
                            <td>{!! $agent->user ? $agent->user->email : '' !!}</td>
                            <td>{!! $agent->area ? $agent->area->name : '' !!}</td>
                            <td>
                                <a href="{{route('Admin::map@agentDetail',[$agent->id])}}">
                                    <button type="button" class="btn btn-info btn-xs">{{ trans('home.show') }}</button></a>
                                @if(auth()->user()->roles()->first()->id != 3)
                                <a href="{{route('Admin::map@editAgent',[$agent->id])}}">
                                    <button type="button" class="btn btn-warning btn-xs">{{ trans('home.edit') }}</button></a>
                                <a onclick="return xoaCat();" href="{{route('Admin::map@agentDelete',[$agent->id])}}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a>
                                    @endif
                            </td>
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
    function xoaCat() {
        var conf = confirm("Bạn chắc chắn muốn xoá?");
        return conf;
    }
    $("#import").on("click", function () {
        $("div#divLoading").addClass('show');
        var form = $('#import_form');
        var data = new FormData(form[0]);
        $.ajax({
            headers: {'X-CSRF-Token': $('input[name="_token"]').val()},
            url: $('#import_form').attr('action'),
            type: $('#import_form').attr('method'),
            data: data,
            processData: false,
            cache: false,
            contentType: false,
            dataType: 'JSON',
            success: function (res) {
                $("#file").text('');
                if (res.status == 'success') {
                    $("div#divLoading").removeClass('show');
                    window.location.reload();
                } else {
                    $.each(res.errors, function (index, value) {
                        $("div#divLoading").removeClass('show');
                        $("#" + index).text(value);
                    });
                }
            }
        });
    });

</script>
@endpush
