@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ trans('home.list_agency') }}</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Admin::map@addAgency')}}" class="btn btn-primary"><i class="icon-add"></i> {{ trans('home.create_agency') }}</a>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <form action="">
            <div class="col-xs-6 col-xs-offset-3">

                <input type="text" name="q" class="form-control" value="{{Request::input('q')}}"
                       placeholder="Nhập tên để tìm kiếm"/>

            </div>
            <button type="submit" class="btn btn-primary">{{ trans('home.search') }}</button>
        </form>
    </div>


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
                    <th>{{ trans('home.manager') }}</th>
                    <th>{{ trans('home.place') }}</th>
                    <th>{{ trans('home.action') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($agents as $agent)
                        <tr role="row" id="">
                            <td>{{$agent->name}}</td>
                            <td>{!! $agent->user ? $agent->user->email : '' !!}</td>
                            <td>{!! $agent->area ? $agent->area->name : '' !!}</td>
                            <td><a href="{{route('Admin::map@agentDetail',[$agent->id])}}">
                                    <button type="button" class="btn btn-info btn-xs">{{ trans('home.show') }}</button></a>
                                <a href="{{route('Admin::map@editAgent',[$agent->id])}}">
                                    <button type="button" class="btn btn-warning btn-xs">{{ trans('home.edit') }}</button></a>
                                <a onclick="return xoaCat();" href="{{route('Admin::map@agentDelete',[$agent->id])}}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a>
                            </td>
                        </tr>
                    @endforeach
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


</script>
@endpush
