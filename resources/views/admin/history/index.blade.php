@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Lịch sử hệ thống</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">

            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="logs-table">
                <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Người dùng</th>
                    <th>Hành động</th>
                    <th>Nội dung</th>
                    <th>Chi tiết</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th ><input class="form-control input-daterange-datepicker" type="text" name="db_updated_at"
                                value="" placeholder="Từ ngày - Đến ngày" style="width: 200px;"/></th>
                    <th></th>
                    <th></th>
                    <th></th>
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
    function format ( d ) {
        if (d.data) {
            return 'Data: '+ d.data
        } else {
            return 'Data Current: '+ d.current_data
        }

    }
    $(document).ready(function() {
        var dt = $("#logs-table").DataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            pageLength: 20,
            'searching': false,
            ajax: {
                url: '{!! route('Admin::log@datatables') !!}',
                data: function (d) {
                    d.updated_at = $('input[name=db_updated_at]').val();
                }
            },
            columns: [
                {data: 'updated_at', name: 'updated_at'},
                {data: 'user', name: 'user'},
                {data: 'action', name: 'action'},
                {data: 'content', name: 'content'},
                {data: 'detail', name: 'detail', orderable: false, searchable: false, class: "details-control"}
            ]
        });

        var detailRows = [];

        $('#logs-table tbody').on( 'click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dt.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows );

            if ( row.child.isShown() ) {
                tr.removeClass( 'details' );
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice( idx, 1 );
            }
            else {
                tr.addClass( 'details' );
                row.child( format( row.data() ) ).show();

                // Add to the 'open' array
                if ( idx === -1 ) {
                    detailRows.push( tr.attr('id') );
                }
            }
        } );

        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on( 'draw', function () {
            $.each( detailRows, function ( i, id ) {
                $('#'+id+' td.details-control').trigger( 'click' );
            } );
        } );

        dt.columns().every( function () {
            $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker){
                var startDate = picker.startDate;
                var endDate = picker.endDate;
                $('.input-daterange-datepicker').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
                dt.draw();
            } );
        } );

        $('.input-daterange-datepicker').daterangepicker({
            autoUpdateInput: false,
            dateLimit: {
                days: 60
            },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment()],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'left',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-default',
            cancelClass: 'btn-white',
            separator: ' to ',
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Submit',
                cancelLabel: 'Clear',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        });

        $('.input-daterange-datepicker').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('.input-daterange-datepicker').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });


    } );

</script>
@endpush
