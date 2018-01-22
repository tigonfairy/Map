@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ trans('home.listBusinessArea')}}</h2>
            </div>
            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Admin::map@addMapUser')}}" class="btn btn-primary"><i class="icon-add"></i> {{ trans('home.createBusinessArea')}}</a>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="row">--}}
        {{--<form action="">--}}
            {{--<div class="col-xs-6 col-xs-offset-3">--}}

                {{--<input type="text" name="q" class="form-control" value="{{Request::input('q')}}"--}}
                       {{--placeholder="{{trans('home.placeholderSearchName')}}"/>--}}

            {{--</div>--}}
            {{--<button type="submit" class="btn btn-primary">{{ trans('home.search') }}</button>--}}
        {{--</form>--}}
    {{--</div>--}}


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
                    <th>{{ trans('home.color'). ' '. trans('home.border')  }}</th>
                    <th>{{ trans('home.background') }}</th>
                    <th>{{ trans('home.action') }}</th>
                </tr>
                </thead>
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
    $(document).on('ready',function() {
        var datatable = $("#users-table").DataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
//            searching: false,
            "pageLength": 10,
            ajax: {
                url: '{!! route('Admin::map@listMapUser.data') !!}',
                data: function (d) {
                    //
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'border', name: 'border', orderable: false, searchable: false},
                {data: 'background', name: 'background', orderable: false, searchable: false},

                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],

            initComplete: function () {
                this.api().columns().every(function () {


                    var column = this;
                    var input = document.createElement("input");
                    input.className = "form-control form-filter input-sm";
                    $(input).appendTo($(column.header()))
                        .on('keyup', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });
    function xoaCat() {
        var conf = confirm("Bạn chắc chắn muốn xoá?");
        return conf;
    }


</script>
@endpush
