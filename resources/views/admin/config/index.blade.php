@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Cấu hình hệ thống</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
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
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <form method="POST" action="{{  route('Admin::config@store') }}">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('repassword') ? 'has-error has-feedback' : '' }}">
                                <label for="name" class="control-label text-semibold">Số lần nhập sai mật khẩu</label>
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Số lần nhập sai mật khẩu"></i>
                                <input type="number" id="repassword" name="repassword" class="form-control" value="{{ isset($config['repassword']) ? $config['repassword'] : '' }}" />
                                @if ($errors->has('repassword'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('repassword') }}</div>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('recaptcha') ? 'has-error has-feedback' : '' }}">
                                <label for="name" class="control-label text-semibold">Có nhập captcha khi đăng nhập</label>
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Số lần nhập sai mật khẩu"></i>
                                <select name="recaptcha" class="form-control">
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="1" {{ (isset($config['recaptcha']) && $config['recaptcha'] == 1) ? 'selected=selected' : '' }}>Có</option>
                                    <option value="2" {{ (isset($config['recaptcha']) && $config['recaptcha'] == 2) ? 'selected=selected' : '' }}>Không</option>
                                </select>
                                @if ($errors->has('recaptcha'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('recaptcha') }}</div>
                                @endif
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /main content -->

    <!-- /page content -->

    <!-- /page container -->


@endsection
