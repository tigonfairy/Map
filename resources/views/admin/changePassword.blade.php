@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <i class="icon-arrow-left8"></i>
                    {{trans('home.change_password')}}
                </h2>
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
                    @if (session('success'))
                        <div class="alert bg-success alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                            {{ session('success') }}
                        </div>
                    @endif
                        @if (session('error'))
                            <div class="alert bg-success alert-styled-left">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                {{ session('error') }}
                            </div>
                        @endif
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <form method="POST" enctype="multipart/form-data" action="{{route('Admin::changePassword')}}">
                                {{ csrf_field() }}
                            <!---------- Name------------>
                                <div class="form-group {{ $errors->has('old_password') ? 'has-error has-feedback' : '' }}">
                                    <label for="name" class="control-label text-semibold">{{trans('home.old_password')}}</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                    <input type="password" name="old_password" class="form-control" value="{{ old('old_password')}}" />
                                    @if ($errors->has('old_password'))
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block">{{ $errors->first('old_password') }}</div>
                                    @endif
                                </div>
                                    <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">{{trans('home.new_password')}}</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                        <input type="password" name="password" class="form-control" value="{{ old('password')}}" />
                                        @if ($errors->has('password'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error has-feedback' : '' }}">
                                        <label for="name" class="control-label text-semibold">{{trans('home.re_password')}}</label>
                                        <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                        <input type="password"  name="password_confirmation" class="form-control" value="{{ old('password_confirmation')}}" />
                                        @if ($errors->has('password_confirmation'))
                                            <div class="form-control-feedback">
                                                <i class="icon-notification2"></i>
                                            </div>
                                            <div class="help-block">{{ $errors->first('password_confirmation') }}</div>
                                        @endif
                                    </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">{{trans('home.edit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->

    <!-- /page container -->
@endsection

@push('js_files_foot')
    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush

@push('scripts_foot')
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'editor1' );
    </script>
@endpush