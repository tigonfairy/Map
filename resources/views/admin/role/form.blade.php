@extends('admin')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>

            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($role) ? 'Sửa  ' : 'Thêm ' }}
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
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" action="{{ isset($role) ? route('Admin::role@update', [$role->id] ): route('Admin::role@store') }}">
                  {{ csrf_field() }}
                  @if (isset($role))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                  <!---------- Name------------>
                    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Name</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ?: @$role->name }}" />
                      @if ($errors->has('name'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('name') }}</div>
                      @endif
                    </div>

                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Permission</th>
                            <th>True</th>
                            <th>False</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach($permission as $row)
                            <tr role="row" id="">
                              <td>{{$row->id}}</td>
                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[{{$row->id}}]" class="js-radio"  {{ (isset($rolePermissions[$row->id]) and $rolePermissions[$row->id]->pivot->value == 0) ? ' checked="checked"' : ''  }} class="js-radio" value="0">
                                  </label>
                                </div>
                              </td>

                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[{{$row->id}}]" class="js-radio" {{ (isset($rolePermissions[$row->id]) and $rolePermissions[$row->id]->pivot->value == 1) ? ' checked="checked"' : ''  }} class="js-radio" value="1">
                                  </label>
                                </div>
                              </td>

                            </tr>
                          @endforeach
                          </tbody>
                        </table>


                      </div>

                    </div>


                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($role) ? 'Cập nhật' : 'Thêm mới' }}</button>
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