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
        {{ isset($product) ? 'Sửa Sản Phẩm ' : 'Thêm Sản Phẩm' }}
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
          @include('admin.flash')
          <div class="panel panel-flat">
            <div class="panel-body">
              <form method="POST" action="{{ isset($product) ? route('Admin::group_product@update', [$product->id] ): route('Admin::group_product@store') }}">
                {{ csrf_field() }}
                @if (isset($product))
                  <input type="hidden" name="_method" value="PUT">
                  @endif
                          <!---------- Name------------>
                  <div class="form-group {{ $errors->has('name_vn') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">{!! trans('home.nameVN') !!}</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên sản phẩm"></i>
                    <input type="text" id="name" name="name_vn" class="form-control" value="{{ old('name_vn') ?: @$product->name_vn }}" />
                    @if ($errors->has('name_vn'))
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block">{{ $errors->first('name_vn') }}</div>
                    @endif
                  </div>

                  <!---------- Name English ------------>
                  <div class="form-group {{ $errors->has('name_en') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">{!! trans('home.nameEng') !!}</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên sản phẩm"></i>
                      <input type="text" id="name_en" name="name_en" class="form-control" value="{{ old('name_en') ?: @$product->name_en }}" />
                      @if ($errors->has('name_en'))
                          <div class="form-control-feedback">
                              <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('name_en') }}</div>
                      @endif
                  </div>

                  <!---------- Code ------------>
                  <div class="form-group {{ $errors->has('code') ? 'has-error has-feedback' : '' }}">
                      <label for="name" class="control-label text-semibold">{!! trans('home.code') !!}</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên sản phẩm"></i>
                      <input type="text" id="name" name="code" class="form-control" value="{{ old('code') ?: @$product->code }}" />
                      @if ($errors->has('code'))
                          <div class="form-control-feedback">
                              <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('code') }}</div>
                      @endif
                  </div>

                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">{!! isset($product) ? trans('home.update') : trans('home.create')  !!}</button>
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

