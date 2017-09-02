@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ isset($saleAgent) ? trans('saleAgent.editDataAgent') : trans('saleAgent.addDataAgent') }}</h2>
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
                            <form method="POST" action="{{ isset($saleAgent) ? route('Admin::saleAgent@update', [$saleAgent[0]->agent_id] ): route('Admin::saleAgent@store') }}">
                            {{ csrf_field() }}
                                @if (isset($saleAgent))
                                    <input type="hidden" name="_method" value="PUT">
                            @endif
                            <!---------- Agent ID------------>
                                <div class="form-group {{ $errors->has('agent_id') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">{{trans('saleAgent.agent')}}</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Đại lý"></i>
                                    <select name="agent_id" class="agents" {{ isset($saleAgent) ?  "disabled=disabled" : "" }}>
                                        <option value="">-- {{trans('home.select')}} --</option>
                                        @foreach($agents as $key => $value)
                                            <option value="{{ $value->id }}" {{ $value->id == @$saleAgent[0]->agent_id ? "selected=selected" : ""}}>{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('agent_id'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('agent_id') }}</div>
                                    @endif
                                </div>

                                <!---------- Thời gian ------------>
                                <div class="form-group {{ $errors->has('month') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">{{trans('saleAgent.time')}}</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thời gian"></i>
                                    <input type="text" id="month" name="month" class="form-control monthPicker" value="{{ old('month') ?: @$saleAgent[0]->month }}" @if(isset($saleAgent)) disabled @endif />
                                    @if(isset($saleAgent))
                                        <input type="hidden" name="month" value="{{@$saleAgent[0]->month}}">
                                        @endif
                                    @if ($errors->has('month'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('month') }}</div>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('capacity') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">{{trans('saleAgent.areaCapacity')}}</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thời gian"></i>
                                    <input type="number" id="capacity" name="capacity" class="form-control" value="{{ old('capacity') ?: @$saleAgent[0]->capacity }}" />
                                    @if ($errors->has('capacity'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('capacity') }}</div>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('sales_plan') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">{{trans('saleAgent.salePlan')}}</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Thời gian"></i>
                                    <input type="number" id="sales_plan" name="sales_plan" class="form-control" value="{{ old('sales_plan') ?: @$saleAgent[0]->sales_plan }}" />
                                    @if ($errors->has('sales_plan'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('sales_plan') }}</div>
                                    @endif
                                </div>


                                <!---------- Type of Product - Doanh số kế hoạch - Doanh số thực tế ------------>
                                <a href="#add-product" class="btn btn-info" data-toggle="modal">{{trans('saleAgent.add')}}</a>

                                <div class="form-group">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <th>Code</th>
                                        <th>{{trans('saleAgent.nameProduct')}}</th>
                                        <th>{{trans('saleAgent.saleReal')}}</th>
                                        </thead>

                                        <tbody>
                                        @foreach($products as $key => $value)
                                            <tr>
                                                <input type="hidden" name="product_id[]" value="{{ $value->id }}" />
                                                <td>{{ $value->code }}</td>
                                                <td>{{  $value->name_vn}}</td>
                                                <td><input type="text"  id="sales_real" name="sales_real[]" class="form-control" value="{{ @$saleAgent[$key]->product_id == $value->id ? @$saleAgent[$key]->sales_real : 0 }}" /></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{ isset($saleAgent) ? trans('saleAgent.update') : trans('saleAgent.add') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /main content -->
        </div>


        <div class="modal fade bs-modal-lg" id="add-product" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">{{trans('saleAgent.addProduct')}}</h4>
                    </div>
                    <form method="POST" action="{{ route('Admin::saleAgent@importExcelDataAgent') }}"
                          enctype="multipart/form-data" id="import_form">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-10" style="margin-bottom:10px">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">{{trans('saleAgent.product')}}</label>
                                        <div class="col-md-8">
                                            <select  class="products form-control" style="width:100%">
                                                @foreach($products as $product)
                                                <option value="{{$product->id}}" data-code="{{$product->code}}" data-name="{{$product->name}}">{{$product->name}}-{{$product->code}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{trans('saleAgent.close')}}</button>
                            <button type="button" class="btn green" id="import">{{trans('saleAgent.add')}}</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- /page container -->
        @endsection
        @push('scripts')
        <script type="text/javascript">
            var map;
            var markers = [];

            $(document).ready(function () {
                $('.agents').select2();
                $('.products').select2();

                $('.monthPicker').datepicker( {
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'mm-yy',
                    onClose: function(dateText, inst) {
                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, month, 1));
                    }
                });
            });
        </script>
    @endpush
