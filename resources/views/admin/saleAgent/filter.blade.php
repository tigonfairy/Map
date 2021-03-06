@extends('admin')
@section('content')
    <style>
        #matrixData {
            width: 100%;
            overflow-x:auto !important;
        }
    </style>
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

        <div class="portlet light filter"  style="display: none;">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Lọc</span>
                </div>
                <div class="portlet-body">
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-products" cellspacing="0" width="100%" id="data-table">
                            <thead>
                                <tr>
                                    <th>Phân loại</th>
                                    <th>Sản phẩm</th>
                                    <th>Sản lượng</th>
                                    <th>Dung lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="filter">

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <div id="matrixData"></div>
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
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));

            $(".endMonth").datepicker("option", "minDate", new Date(year, month, 1));
            $(".endMonth").datepicker("option", "maxDate",  new Date(year, 11, 1));
            $(".endMonth").datepicker('setDate', new Date(year, month, 1));
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

        // search
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
            } else if (search_type == 5) {
                getListNVKD(0);
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
                'placeholder': "{{'-- Chọn nhân viên kinh doanh --'}}",
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

        var listTotals = [];
        var listGroups = [];
        var listProducts = [];
        var listSelectProducts = [];
        $('#geocoding_form').submit(function (e) {
            e.preventDefault();
            var type_search = $("#type_search").val();

            $.ajax({
                type: "GET",
                url: "{{ route('Admin::saleAgent@dataFilter') }}",
                data: $('#geocoding_form').serialize(),
                cache: false,
                success: function (data) {
                    $(".filter").show();
                    $("#filter").html('<td><select class="type_filter form-control">' +
                        '<option value="">-- Chọn loại tìm kiếm --</option>' +
                    '<option value="1">Theo tổng</option>' +
                    '<option value="2">Theo nhóm</option>' +
                    '<option value="3">Theo mã sản phảm</option>' +
                    '</select>' +
                    '</td>' +
                    '<td id="">' +
                       '<select name="value_filter" class="value_filter form-control" id="locations">' +
                        '</select>' +
                    '</td>' +
                    '<td id="totalSales"></td>' +
                    '<td id="capacity"></td>');
                    listTotals = data.listTotals;
                    listGroups = data.listGroups;
                    listProducts = data.listProducts;

                    // gộp các mảng
                    listSelectProducts = [];
                    $.each(listTotals, function (index, value) {
                        listSelectProducts.push(value);
                    });
                    $.each(listGroups, function (index, value) {
                        listSelectProducts.push(value);
                    });
                    $.each(listProducts, function (index, value) {
                        listSelectProducts.push(value);
                    });


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
                    if (data) {
                        $('#matrixData').html('');
                        $('#matrixData').html(data);
                    }
                }
            });
        });

        // filter
        $(document).on("change", ".type_filter", function () {
            var search_type = $(this).val();
            $("#totalSales").text('');
            $("#capacity").text('');
            if (search_type == 1) {
                getValueFilter(listTotals);
            } else if (search_type == 2) {
                getValueFilter(listGroups);
            } else if (search_type == 3) {
                getValueFilter(listProducts);
            }

        });

        function getValueFilter(listProducts) {
            var string = '<option value="">-- Chọn giá trị lọc -- </option>';
            $.map(listProducts, function (product) {
                string += '<option  value="' + product.code + '">' + product.name + '</option>';
            });
            $(".value_filter").html(string);

        }


        $(document).on('change', '.value_filter', function () {
            var code = $(this).val();
            var data = $.grep(listSelectProducts, function (e) {
                return e.code == code;
            });
            var item = data[0];
            $("#totalSales").text(numberWithCommas(item.totalSales));
            $("#capacity").text(numberWithCommas(item.capacity));
        });

        function numberWithCommas(x) {
            var parts = x.toString().split(",");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(",");
        }
    });
</script>
@endpush