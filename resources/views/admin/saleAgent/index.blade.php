@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Danh sách dữ liệu của đại lý</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Admin::saleAgent@add')}}" class="btn btn-primary"><i class="icon-add"></i> Thêm dữ liệu cho đại lý</a>
                    <a href="#import-product" class="btn btn-info" data-toggle="modal" id="btn-system-product">Thêm doanh số từ Excel</a>
                    <a href="{{asset('data_agent.xlsx')}}" class="btn btn-success"  id="btn-system-product">Mẫu</a>
                </div>
            </div>

            <div id="divLoading"></div>
            <div class="modal fade bs-modal-lg" id="import-product" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Thêm doanh số cho đại lý bằng Excel</h4>
                        </div>
                        <form method="POST" action="{{ route('Admin::saleAgent@importExcelDataAgent') }}"
                              enctype="multipart/form-data" id="import_form">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-10" style="margin-bottom:10px">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Thời gian</label>
                                            <div class="col-md-8">
                                                <input type="text" name ="month" class="form-control monthPicker" value="" />
                                                <span id="month" class="error-import" style="color:red;"></span>
                                            </div>
                                        </div>
                                        </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">File</label>
                                            <div class="col-md-8">
                                                <input type="file" class="file-excel form-control" name="file">
                                                <span id="file" class="error-import" style="color:red;"></span>
                                            </div>
                                        </div>
                                    </div>

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

    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">
            @include('admin.flash')
            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="listData-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Đại lý</th>
                    <th>Thời gian</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th><input type="text" class="form-control" name="db_agent_name" placeholder=""/></th>
                    <th><input type="text" id="month" name = "db_month" class="form-control monthPicker" value="" /></th>
                    <th></th>
                </tr>
                </tfoot>
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
    function xoaCat() {
        var conf = confirm("Bạn chắc chắn muốn xoá?");
        return conf;
    }

    $(document).ready(function() {
        var dt = $("#listData-table").DataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            "pageLength": 10,
            'searching': false,
            ajax: {
                url: '{!! route('Admin::saleAgent@datatables') !!}',
                data: function (d) {
                    d.agent = $('input[name=db_agent_name]').val();
                    d.month = $('input[name=db_month]').val();
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'agent', name: 'agent'},
                {data: 'month', name: 'month'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        dt.columns().every( function () {
            $( 'input', this.footer() ).on( 'keyup change', function () {
                dt.draw();
            } );
        } );

        $('.monthPicker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'mm-yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
                dt.draw();
            }
        });


        $("#import").on("click", function () {
            $("div#divLoading").addClass('show');
            $('.error-import').text('');
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
