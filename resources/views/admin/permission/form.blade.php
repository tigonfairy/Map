@extends('admin')

@section('content')
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>

            <i class="icon-arrow-left8"></i>
          </a>
          {{ isset($permission) ? 'Sửa  ' : 'Thêm ' }}
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
                <form method="POST" enctype="multipart/form-data" action="{{ isset($permission) ? route('Admin::permission@update', [$permission->id] ): route('Admin::permission@store') }}">
                  {{ csrf_field() }}
                  @if (isset($permission))
                    <input type="hidden" name="_method" value="PUT">
                  @endif
                  <!---------- ID------------>
                    <div class="form-group {{ $errors->has('id') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">ID</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="id" name="id" class="form-control" value="{{ old('id') ?: @$permission->id }}" />
                      @if ($errors->has('id'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('id') }}</div>
                      @endif
                    </div>
                    <!---------- Description------------>
                    <div class="form-group {{ $errors->has('description') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">Description</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="description" name="description" class="form-control" value="{{ old('description') ?: @$permission->description }}" />
                      @if ($errors->has('description'))
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block">{{ $errors->first('description') }}</div>
                      @endif
                    </div>


                    <div class="text-right">
                      <button type="submit" class="btn btn-primary">{{ isset($permission) ? 'Cập nhật' : 'Thêm mới' }}</button>
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
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
@endpush

@push('scripts_foot')
<script>
  // Replace the <textarea id="editor1"> with a CKEditor
  // instance, using default configuration.
  CKEDITOR.replace( 'editor1' );
</script>
@endpush