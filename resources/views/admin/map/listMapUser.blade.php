@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ trans('home.listBusinessArea')}}</h2>
            </div>
            @if(auth()->user()->roles()->first()->id == 1)
            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="{{route('Admin::map@addMapUser')}}" class="btn btn-primary"><i class="icon-add"></i> {{ trans('home.createBusinessArea')}}</a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <form action="">
            <div class="col-xs-6 col-xs-offset-3">

                <input type="text" name="q" class="form-control" value="{{Request::input('q')}}"
                       placeholder="{{trans('home.placeholderSearchName')}}"/>

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
                    <th>{{ trans('home.color'). ' '. trans('home.border')  }}</th>
                    <th>{{ trans('home.background') }}</th>
                    <th>{{ trans('home.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($areas as $area)
                    <tr role="row" id="">
                        <td>{{$area->name}}</td>
                        <td>{{$area->border_color}} <span style="display:inline-block;width: 20px;height:20px;background:{{$area->border_color}}"></span></td>
                        <td>{{$area->background_color}} <span style="display:inline-block;width: 20px;height:20px;background:{{$area->background_color}}"></span></td>
                        <td>
                            <a href="{{route('Admin::map@mapUserDetail',[$area->id])}}">
                                <button type="button" class="btn btn-info btn-xs">{{ trans('home.show') }}</button></a>
                            @if(auth()->user()->roles()->first()->id == 1)
                    <a href="{{route('Admin::map@editMapUser',[$area->id])}}">
                                <button type="button" class="btn btn-warning btn-xs">{{ trans('home.edit') }}</button></a>

                            <a onclick="return xoaCat();" href="{{route('Admin::map@mapUserDelete',[$area->id])}}" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> {{ trans('home.delete') }}</a>
                            @endif
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
