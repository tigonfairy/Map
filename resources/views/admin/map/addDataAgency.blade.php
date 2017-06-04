@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Thêm dữ liệu kinh doanh cho đại lý</h2>
            </div>

        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <!-- Main content -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    @include('admin.flash')
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <form method="POST" action="{{ route('Admin::map@addDataAgencyPost') }}">
                                {{ csrf_field() }}

                                <!---------- Agent ID------------>
                                <div class="form-group {{ $errors->has('agent_id') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Đại lý</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Đại lý"></i>
                                    <select name="agent_id" class="agents">
                                        <option value="">-- Chọn đại lý --</option>
                                        @foreach($agents as $key => $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('agent_id'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('agent_id') }}</div>
                                    @endif
                                </div>

                                <!---------- Type of Product ------------>
                                    <div class="form-group {{ $errors->has('product_id') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Sản phẩm</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Sản phẩm"></i>
                                        <select name="product_id" class="form-control">
                                            <option value="">-- Chọn nhóm sản phẩm --</option>
                                            @foreach($products as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('product_id'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('product_id') }}</div>
                                        @endif
                                    </div>

                                    <!---------- Thời gian ------------>
                                    <div class="form-group {{ $errors->has('month') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Thời gian</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thời gian"></i>
                                        <input type="text" id="month" name="month" class="form-control monthPicker" value="{{ old('month') }}" />
                                        @if ($errors->has('month'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('month') }}</div>
                                        @endif
                                    </div>

                                <!------------------ Doanh số kế hoạch--------------->
                                <div class="form-group {{ $errors->has('sales_plan') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">Doanh số kế hoạch</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Doanh số kế hoạch"></i>
                                    <input type="text"  id="sales_plan" name="sales_plan" class="form-control" value="{{ old('sales_plan') }}" />
                                    @if ($errors->has('sales_plan'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('sales_plan') }}</div>
                                    @endif
                                </div>

                                    <!------------------ Doanh số thực tế--------------->
                                    <div class="form-group {{ $errors->has('sales_real') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">Doanh số kế hoạch</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Doanh số kế hoạch"></i>
                                        <input type="text"  id="sales_plan" name="sales_real" class="form-control" value="{{ old('sales_real') }}" />
                                        @if ($errors->has('sales_real'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('sales_real') }}</div>
                                        @endif
                                    </div>


                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Cập nhật' : 'Thêm mới' }}</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
@endsection
@push('scripts')
<script type="text/javascript">
    var map;
    var markers = [];

    $(document).ready(function () {
        $('.agents').select2();

        $('.monthPicker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'mm-yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            },
            beforeShow : function(input, inst) {
                var datestr;
                if ((datestr = $(this).val()).length > 0) {
                    year = datestr.substring(datestr.length-4, datestr.length);
                    month = jQuery.inArray(datestr.substring(0, datestr.length-5), $(this).datepicker('option', 'monthNamesShort'));
                    $(this).datepicker('option', 'defaultDate', new Date(year, month, 1));
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            }
        });
    });
</script>
@endpush
