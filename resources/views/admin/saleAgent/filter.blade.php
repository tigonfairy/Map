@extends('admin')
@section('content')
    <div class="row">
        <div class="portlet light ">
            <div class="row">
                <form method="post" id="geocoding_form">
                    <input type="hidden" name="type_search" value="" id="type_search"/>

                    @if($user->position != \App\Models\User::NVKD)
                        <div class="col-md-2">
                            <select class="search_type form-control" name="type_data_search">
                                <option value="">-- Chọn loại {{ trans('home.search') }} --</option>
                                <option value="1">Theo đại lý</option>
                                <option value="5">Theo nhân viên kinh doanh</option>
                                @if($user->position != \App\Models\User::GSV)
                                    <option value="2">Theo giám sát vùng</option>
                                @endif
                                @if($user->position != \App\Models\User::TV && $user->position != \App\Models\User::GSV)
                                    <option value="3">Theo trưởng vùng</option>
                                @endif
                                @if(($user->position != \App\Models\User::GĐV && $user->position != \App\Models\User::GSV && $user->position != \App\Models\User::TV))
                                    <option value="4">Theo giám đốc vùng</option>
                                @endif
                            </select>

                        </div>

                        <div class="col-md-2">
                            <select name="data_search" class="data_search form-control" id="locations"
                                    style="width:100%">
                            </select>
                        </div>

                    @endif

                        <div class="col-md-2">


                                <input type="text" name="startMonth"  class="form-control startMonth" value="" placeholder="Thời gian bắt đầu" />
                                <span id="startMonth" class="error-import" style="color:red;"></span>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-md-2">


                                <input type="text" name="endMonth"  class="form-control endMonth" value="" placeholder="Thời gian kết thúc"/>
                                <span id="endMonth" class="error-import" style="color:red;"></span>

                            <div class="clearfix"></div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info">{{ trans('home.search') }}</button>
                        </div>


                </form>

                <div class="clearfix"></div>
            </div>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Bảng doanh số</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="portlet-body">
                    <div id="tableData"></div>
                </div>
            </div>
        </div>


        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Matrix</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="portlet-body">
                    <div id="maxTable"></div>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts_foot')
<script type="text/javascript">
    $('.startMonth').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm-yy',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            console.log(month);
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));

            $(".endMonth").datepicker("option", "minDate", new Date(year, month, 1));
            $(".endMonth").datepicker("option", "maxDate",  new Date(year, 11, 1));
        }
    });
    $('.endMonth').datepicker( {
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

    $(document).ready(function () {

        $(".search_type").change(function () {
            var search_type = $(this).val();
            if (search_type == 1) {
                getListAgents(0);
            } else if (search_type == 2) {
                getListGSV(0);
            } else if (search_type == 3) {
                getListTV(0);
            } else if (search_type == 4) {
                getListGDV(0);
            }
        });

        function getListAgents(type) {
            if(type == 0) {
                $("#type_search").val('agents');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.agency') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getListAgents')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) {
                        return m;
                    }
                }
            });
        }

        function getListGSV(type) {
            if(type == 0) {
                $("#type_search").val('gsv');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getGSV')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) {
                        return m;
                    }
                }
            });
        }

        function getListTV(type) {
            if(type == 0) {
                $("#type_search").val('tv');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getListTV')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) {
                        return m;
                    }
                }
            });
        }

        function getListGDV(type) {
            if(type == 0) {
                $("#type_search").val('gdv');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getListGDV')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) {
                        return m;
                    }
                }
            });
        }
        function getListNVKD(type) {

            if(type == 0) {
                $("#type_search").val('nvkd');
                var that = $(".data_search");
            } else {
                var that = $('.dataExport');
            }

            that.select2({
                'placeholder': "{{'-- '. trans('home.select'). ' '. trans('home.manager') .' --'}}",
                ajax: {
                    url: "{{route('Admin::Api::sale@getListNVKD')}}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data, page) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) {
                        return m;
                    }
                }
            });
        }

        $('#geocoding_form').submit(function (e) {
            e.preventDefault();
            var type_search = $("#type_search").val();

            $.ajax({
                type: "GET",
                url: "{{ route('Admin::saleAgent@dataFilter') }}",
                data: $('#geocoding_form').serialize(),
                cache: false,
                success: function (data) {

                    if (data.table) {
                        $('#tableData').html('');
                        $('#tableData').html(data.table);
                    }
                }
            });

            $.ajax({
                type: "GET",
                url: "{{ route('Admin::saleAgent@matrixFilter') }}",
                data: $('#geocoding_form').serialize(),
                cache: false,
                success: function (data) {

                    if (data.table) {
                        $('#maxTable').html('');
                        $('#maxTable').html(data.table);
                    }
                }
            });
        });
    });
</script>
@endpush