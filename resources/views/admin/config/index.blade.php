@extends('admin')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>{{ trans('home.config') }}</h2>
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
                        <form method="POST" action="{{  route('Admin::config@store') }}" enctype="multipart/form-data">
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



                            <div class="form-group {{ $errors->has('textColor') ? 'has-error has-feedback' : '' }}">
                                <label class="control-label col-md-3">Màu chữ</label>

                                    <div class="input-group color colorpicker-default form-control"
                                         data-color="{{isset($config['textColor']) ? $config['textColor'] : '#FF0000'}}"
                                         data-color-format="rgba">
                                        <input type="text" class="form-control"
                                               value="{{isset($config['textColor']) ? $config['textColor'] : '#FF0000'}}"
                                               name="textColor">
                                        <span class="input-group-btn">
                                                    <button class="btn default" type="button"><i
                                                                style="background-color: {{isset($area) ? $area->border_color : '#3865a8'}};"></i>&nbsp;</button>
                                                </span>
                                    </div>
                                @if ($errors->has('textColor'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('textColor') }}</div>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('fontSize') ? 'has-error has-feedback' : '' }}">
                                <label for="name" class="control-label text-semibold">Phông chữ hiển thị trên map</label>
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                <input type="number" id="fontSize" name="fontSize" class="form-control" value="{{ isset($config['fontSize']) ? $config['fontSize'] : 15 }}" />
                                @if ($errors->has('fontSize'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('fontSize') }}</div>
                                @endif
                            </div>


                            {{--set up đại lý--}}
                            <div class="form-group {{ $errors->has('agent_diamond') ? 'has-error has-feedback' : '' }}">
                                <label for="name" class="control-label text-semibold">Đại lý kim cương</label>
                                @if( isset($config['agent_diamond']) and ($config['agent_diamond']))
                                    <img src="{{$config['agent_diamond']}}" style="width: 100px;height: 100px" alt="">
                                @endif
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                <input type="file" id="fontSize" name="agent_diamond" class="form-control" />
                                @if ($errors->has('agent_diamond'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('agent_diamond') }}</div>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('agent_gold') ? 'has-error has-feedback' : '' }}">
                                <label for="name" class="control-label text-semibold">Đại lý vàng</label>
                                @if( isset($config['agent_gold']) and ($config['agent_gold']))
                                    <img src="{{$config['agent_gold']}}" style="width: 100px;height: 100px" alt="">
                                @endif
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                <input type="file"  name="agent_gold" class="form-control" />
                                @if ($errors->has('agent_gold'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('agent_gold') }}</div>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->has('agent_silver') ? 'has-error has-feedback' : '' }}">
                                <label for="name" class="control-label text-semibold">Đại lý bạc</label>
                                @if( isset($config['agent_silver']) and ($config['agent_silver']))
                                    <img src="{{$config['agent_silver']}}" style="width: 100px;height: 100px" alt="">
                                @endif
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                <input type="file"  name="agent_silver" class="form-control" />
                                @if ($errors->has('agent_silver'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('agent_silver') }}</div>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('agent_unclassified') ? 'has-error has-feedback' : '' }}">
                                <label for="name" class="control-label text-semibold">Đại lý chưa phân hạng</label>
                                @if( isset($config['agent_unclassified']) and ($config['agent_unclassified']))
                                    <img src="{{$config['agent_unclassified']}}" style="width: 100px;height: 100px" alt="">
                                @endif
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                <input type="file"  name="agent_unclassified" class="form-control" />
                                @if ($errors->has('agent_unclassified'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('agent_unclassified') }}</div>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('agent_rival') ? 'has-error has-feedback' : '' }}">
                                <label for="name" class="control-label text-semibold">Đại lý đối thủ</label>
                                @if( isset($config['agent_rival']) and ($config['agent_rival']))
                                    <img src="{{$config['agent_rival']}}" style="width: 100px;height: 100px" alt="">
                                @endif
                                <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content=""></i>
                                <input type="file"  name="agent_rival" class="form-control" />
                                @if ($errors->has('agent_rival'))
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block">{{ $errors->first('agent_rival') }}</div>
                                @endif
                            </div>




                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">{{ trans('home.update') }}</button>
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
