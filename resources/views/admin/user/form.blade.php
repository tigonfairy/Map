@extends('admin')

@section('content')
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>
        <a href="" class="btn btn-link">
          <i class="icon-arrow-left8"></i>
        </a>
        {{ isset($user) ? 'Sửa Thành viên ' : 'Thêm Thành viên' }}
      </h2>
    </div>
  </div>
</div>
<!-- /page header -->

<!-- Page container -->
<div class="page-container">
  <!-- Page content -->

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
              <form method="POST" action="{{ isset($user) ? route('Admin::user@update', [$user->id] ): route('Admin::user@store') }}">
                {{ csrf_field() }}
                @if (isset($user))
                  <input type="hidden" name="_method" value="PUT">
                  @endif
                          <!---------- Name------------>
                  {{--<div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">--}}
                    {{--<label for="name" class="control-label text-semibold">Tên</label>--}}
                    {{--<i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>--}}
                    {{--<input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$user->name }}" />--}}
                    {{--@if ($errors->has('name'))--}}
                      {{--<div class="form-control-feedback">--}}
                        {{--<i class="icon-notification2"></i>--}}
                      {{--</div>--}}
                      {{--<div class="help-block">{{ $errors->first('name') }}</div>--}}
                    {{--@endif--}}
                  {{--</div>--}}
                  <!------------------ Email--------------->
                  <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">Email</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" @if(isset($user))disabled @endif id="email" name="email" class="form-control" value="{{ old('email') ?: @$user->email }}" />
                    @if ($errors->has('email'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('email') }}</div>
                    @endif
                  </div>
                  <!------------------------- Password-------------------->

                    <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
                      <label for="passwrod" class="control-label text-semibold">Mật khẩu</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong> của Doanh nghiệp."></i>
                      <input type="password" id="password" name="password" class="form-control" />
                      @if ($errors->has('password'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('password') }}</div>
                      @endif
                    </div>

                    <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error has-feedback' : '' }}">
                      <label for="password-confirmation" class="control-label text-semibold">Xác nhận Mật khẩu</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập lại mật khẩu ở trên."></i>
                      <input type="password" id="password-confirmation" name="password_confirmation" class="form-control" />
                      @if ($errors->has('password_confirmation'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('password_confirmation') }}</div>
                      @endif
                    </div>

                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Group</th>
                            <th>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach($roles as $row)
                          <tr role="row" id="">
                            <td>{{$row->name}}</td>
                            <td>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" id="" name="role[]" value="{{$row->id}}" {{ isset($userRoles[$row->id]) ? ' checked="checked"' : ''  }}  class="js-checkbox">
                                  </label>
                                </div>
                            </td>
                          </tr>
                            @endforeach
                          </tbody>
                        </table>


                      </div>

                    </div>


                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Permission</th>
                            <th>True</th>
                            <th>False</th>
                            <th>Null</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach($permission as $row)
                            <tr role="row" id="">
                              <td>{{$row->id}}</td>
                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[{{$row->id}}]" {{ (isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 0) ? ' checked="checked"' : ''  }} class="js-radio" value="0">
                                  </label>
                                </div>
                              </td>

                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[{{$row->id}}]" {{ (isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 1) ? ' checked="checked"' : ''  }} class="js-radio" value="1">
                                  </label>
                                </div>
                              </td>

                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[{{$row->id}}]" {{ (isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 2) ? ' checked="checked"' : ''  }} class="js-radio" value="2">
                                  </label>
                                </div>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>


                      </div>

                    </div>
                    </div>


                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Cập nhật' : 'Thêm mới' }}</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /main content -->

</div>
<!-- /page container -->
@endsection

@push('js_files_foot')
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endpush

@push('scripts_foot')
<script>
  $(document).ready(function () {
    // Basic
    $(".js-select").select2();

    //
    // Select with icons
    //

    // Format icon
    function iconFormat(icon) {
      var originalOption = icon.element;
      if (!icon.id) { return icon.text; }
      var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

      return $icon;
    }

    // Initialize with options
    $(".select-icons").select2({
      templateResult: iconFormat,
      minimumResultsForSearch: Infinity,
      templateSelection: iconFormat,
      escapeMarkup: function(m) { return m; }
    });



    // Styled form components
    // ------------------------------

    // Checkboxes, radios
    $(".js-radio, .js-checkbox").uniform({ radioClass: "choice" });

    // File input
    $(".js-file").uniform({
      fileButtonClass: "action btn btn-default"
    });

    $(".js-tooltip, .js-help-icon").popover({
      container: "body",
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });

    // Toggle password inputs
    $(document).on('click', 'a#show-password-inputs', function (e) {
      e.preventDefault();

      $('#password-inputs').removeClass('hidden').prev().addClass('hidden');
    });

    $(document).on('click', 'a#hide-password-inputs', function (e) {
      e.preventDefault();

      $('#password-inputs').addClass('hidden').prev().removeClass('hidden');
    });

    @if ($errors->has('password'))
    $('a#show-password-inputs').trigger('click');
    @endif

  });
</script>
@endpush
