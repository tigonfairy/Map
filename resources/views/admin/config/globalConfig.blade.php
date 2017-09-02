@extends('admin')
@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{  trans('home.configInterface') }}</h2>
                <a href="{{route('Admin::config@globalEnable')}}"><button class="btn btn-primary">{{trans('home.globalEnable')}}</button></a>
            </div>

        </div>
    </div>
    @include('admin.flash')
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
                            <form method="POST" action="{{ route('Admin::config@globalConfig')}}">

                            {{ csrf_field() }}


                            <!---------- Agent ID------------>


                                <div class="form-group">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>

                                        <th>{{trans('config.position')}}</th>
                                        <th>{{trans('home.textColor')}}</th>
                                        <th>{{trans('home.fontSize')}}</th>

                                        </thead>

                                        <tbody>
                                        @foreach(\App\Models\User::$positionTexts as $key => $value)
                                            @php
                                                $textColor = null;

                                                         $fontSize = null;
                                                     $config = \App\Models\Config::where('position_id',$key)->first();
                                                     if($config) {
                                                         $textColor = $config->textColor;
                                                         $fontSize = $config->fontSize;
                                                     }
                                            @endphp

                                            <tr>
                                                <td>{{ $value }}</td>
                                                <td>
                                                    <div class="input-group color colorpicker-default form-control"
                                                         data-color="{{!empty($textColor) ? $textColor : '#3865a8'}}"
                                                         data-color-format="rgba">
                                                        <input type="text" class="form-control"
                                                               value="{{$textColor}}"
                                                               name="textColor[{{$key}}]">
                                                        <span class="input-group-btn">

                                                    <button class="btn default" type="button"><i
                                                                style="background-color: {{!empty($textColor) ? $textColor : '#3865a8'}};"></i>&nbsp;</button>

                                                </span>
                                                    </div>

                                                </td>
                                                <td>
                                                    <div class="input-group  form-control">

                                                        <input type="number" name="fontSize[{{$key}}]"
                                                               class="form-control" value="{{$fontSize}}"/>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
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


                $('.monthPicker').datepicker({

                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'mm-yy',
                    onClose: function (dateText, inst) {
                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, month, 1));
                    }
                });
            });
        </script>
    @endpush
