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

                </div>
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
                    <th>Id</th>
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
            pageLength: 20,
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
    } );


</script>
@endpush
