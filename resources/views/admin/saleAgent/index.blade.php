@extends('admin')

@section('content')
    <style>
        .blockMonth {
            margin-bottom: 20px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{trans('saleAgent.listDataAgent')}}</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Admin::saleAgent@add')}}" class="btn btn-primary"><i class="icon-add"></i> {{trans('saleAgent.addDataAgent')}}</a>
                    <a href="#import-product" class="btn btn-info" data-toggle="modal" id="btn-system-product">{{trans('saleAgent.saleExcel')}}</a>
                    <a href="{{asset('data_agent.xlsx')}}" class="btn btn-success"  id="btn-system-product">{{trans('saleAgent.example')}}</a>
                    <a href="#export-product" class="btn btn-info" data-toggle="modal">{{trans('saleAgent.exportCumulativeSale')}}</a>
                    <a href="#export-tien-do" class="btn btn-info" data-toggle="modal">{{trans('saleAgent.exportProcessSale')}}</a>
                </div>
            </div>

            <div id="divLoading"></div>
            <div class="modal fade bs-modal-lg" id="import-product" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">{{trans('saleAgent.saleExcel')}}</h4>
                        </div>
                        <form method="POST" action="{{ route('Admin::saleAgent@importExcelDataAgent') }}"
                              enctype="multipart/form-data" id="import_form">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-10" style="margin-bottom:10px">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">{{trans('saleAgent.time')}}</label>
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
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{trans('saleAgent.close')}}</button>
                                <button type="button" class="btn green" id="import">Import</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade bs-modal-lg" id="export-product" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">{{trans('saleAgent.exportCumulativeSale')}}</h4>
                        </div>
                        <form method="POST" action="{{ route('Admin::saleAgent@exportExcelDataAgent') }}"
                              enctype="multipart/form-data" id="import_form">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-10" style="margin-bottom:10px">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">{{trans('saleAgent.startTime')}}</label>
                                            <div class="col-md-8">
                                                <input type="text" name="startMonth"  class="form-control monthPicker startMonth-export" value="" />
                                                <span id="startMonth" class="error-import" style="color:red;"></span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">{{trans('saleAgent.endTime')}}</label>
                                            <div class="col-md-8">
                                                <input type="text" name="endMonth"  class="form-control monthPicker endMonth-export" value="" />
                                                <span id="endMonth" class="error-import" style="color:red;"></span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{trans('saleAgent.close')}}</button>
                                <button type="submit" class="btn green" id="export">Export</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>


            <div class="modal fade bs-modal-lg" id="export-tien-do" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">{{trans('saleAgent.exportProcessSale')}}</h4>
                        </div>
                        <form method="POST" action="{{ route('Admin::saleAgent@exportTienDo') }}"
                              enctype="multipart/form-data" id="import_form">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-10" style="margin-bottom:10px">
                                        <div class="form-group ">
                                            <label class="control-label col-md-3">{{trans('saleAgent.startTime')}}</label>
                                            <div class="col-md-8">
                                                <input type="text" name="startMonth"  class="form-control startMonthTD" value="" />
                                                <span id="startMonthTD" class="error-import" style="color:red;"></span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group ">
                                            <label class="control-label col-md-3">{{trans('saleAgent.endTime')}}</label>
                                            <div class="col-md-8">
                                                <input type="text" name="endMonth"  class="form-control endMonthTD" value="" />
                                                <span id="endMonthTD" class="error-import" style="color:red;"></span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group ">
                                            <label class="control-label col-md-3">{{trans('saleAgent.typeTime')}}</label>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="icheck-inline">
                                                            <label><input type="radio" name="type" class="icheck" id="theoquy" value="1" disabled>{{trans('saleAgent.precious')}}</label>
                                                            <label><input type="radio" name="type" class="icheck" id="nuanam" value="2" disabled> {{trans('saleAgent.halfYear')}} </label>
                                                            <label><input type="radio" name="type" class="icheck" id="canam" value="3" disabled>{{trans('saleAgent.year')}}</label>
                                                        </div>
                                                    </div>
                                                    <span id="typeTD" class="error-import" style="color:red;"></span>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{trans('saleAgent.close')}}</button>
                                <button type="submit" class="btn green" id="exportTD">Export</button>
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
        $('.startMonthTD').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'mm-yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                console.log(month);
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
                dt.draw();
                $(".endMonthTD").datepicker("option", "minDate", new Date(year, month, 1));
                $(".endMonthTD").datepicker("option", "maxDate",  new Date(year, 11, 1));
            }
        });
        $('.endMonthTD').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'mm-yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
                dt.draw();
                checkType();
            }
        });

        function checkType() {
            var startMonth = $('.startMonthTD').val();
            var endMonth = $('.endMonthTD').val();
            if(startMonth && endMonth) {
                var startYear = startMonth.substring(3,7);
                var endYear = endMonth.substring(3,7);
                startMonth = startMonth.substring(0,2);
                endMonth = endMonth.substring(0,2);
            }
            $('.icheck').prop("disabled", true);
            $('.icheck').removeAttr('checked');

            if(startYear != endYear) {
                $('#canam').prop('disabled',false);
                return;
            } else {
                if( (endMonth-startMonth) <= 3) {
                    if(endMonth <= 3 ||(3<=startMonth && endMonth <=6) || (6<startMonth && endMonth <=9) || (9 < startMonth && endMonth <=12)) {
                        $('.icheck').prop("disabled", false);
                        return;
                    }
                }
                if(3 < (endMonth - startMonth) <= 6) {
                    if(endMonth <=6 ||(6<startMonth && endMonth <= 12)) {
                        $('#nuanam').prop("disabled", false);
                        $('#canam').prop("disabled", false);
                        return;
                    }
                }
                $('#canam').prop("disabled", false);
            }

        }
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
        $('#export').on('click',function(e){
           var startMonth =  $('.startMonth-export').val();
           if(startMonth == '') {
               e.preventDefault();
               $('#startMonth').text('Vui lòng chọn tháng bắt đầu để export');
           }
            var endMonth =  $('.endMonth-export').val();
            if(endMonth == '') {
                e.preventDefault();
                $('#endMonth').text('Vui lòng chọn tháng kết thúc để export');
            }

        });
        $('#exportTD').on('click',function(e){
            var startMonth =  $('.startMonthTD').val();
            if(startMonth == '') {
                e.preventDefault();
                $('#startMonthTD').text('Vui lòng chọn tháng bắt đầu để export');
            }
            var endMonth =  $('.endMonthTD').val();
            if(endMonth == '') {
                e.preventDefault();
                $('#endMonthTD').text('Vui lòng chọn tháng kết thúc để export');
            }

            var radio = $('input[name=type]:checked').val();
            if(radio == undefined) {
                e.preventDefault();
                $('#typeTD').text('Vui lòng chọn loại để export');

            }
        });




    } );


</script>
@endpush
