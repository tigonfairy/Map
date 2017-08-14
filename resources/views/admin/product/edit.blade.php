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
       Sửa Sản Phẩm
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
              <form method="POST" action="{{  route('Admin::product@update', [$product->id])}}">
                {{ csrf_field() }}
                @if (isset($product))
                  <input type="hidden" name="_method" value="PUT">
                  @endif
                          <!---------- Name------------>
                  <div class="form-group {{ $errors->has('name_vn') ? 'has-error has-feedback' : '' }}">
                    <label for="name" class="control-label text-semibold">{!! trans('home.nameVN') !!}</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên sản phẩm"></i>
                    <input type="text" id="name" name="name_vn" class="form-control" value="{{ old('name_vn') ?: @$product->name_vn }}"  />
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
                      <input type="text" id="name" name="name_en" class="form-control" value="{{ old('name_en') ?: @$product->name_en }}" />
                      @if ($errors->has('name_en'))
                          <div class="form-control-feedback">
                              <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('name_en') }}</div>
                      @endif
                  </div>

                  {{--<!---------- Code ------------>--}}
                  <!---------- parent_product ------------>
                  <div class="form-group {{ $errors->has('parent_id') ? 'has-error has-feedback' : '' }}">
                      <label for="parent_id" class="control-label text-semibold">{!! trans('home.parent_product') !!}</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên sản phẩm"></i>
                      <select class="form-control" name="parent_id">
                          <option value="0"> Chọn nhóm sản phẩm </option>
                          @foreach($group_products as $group_product)
                          <option value="{{ $group_product->id }}" {!! @$product->parent_id == $group_product->id ? 'selected=selected' : '' !!}>{{ $group_product->name }}</option>
                          @endforeach
                      </select>
                      @if ($errors->has('parent_id'))
                          <div class="form-control-feedback">
                              <i class="icon-notification2"></i>
                          </div>
                          <div class="help-block">{{ $errors->first('parent_id') }}</div>
                      @endif
                  </div>


                  {{--<div class="form-group {{ $errors->has('cbd') ? 'has-error has-feedback' : '' }}">--}}
                      {{--<label for="parent_id" class="control-label text-semibold">CBD</label>--}}
                      {{--<i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content='Mã sản phẩm CBD'></i>--}}
                      {{--<input type="text" id="cbd" name="cbd" class="form-control" value="{{ old('cbd') ? : @$product->cbd()->code }}" />--}}
                      {{--@if ($errors->has('cbd'))--}}
                          {{--<div class="form-control-feedback">--}}
                              {{--<i class="icon-notification2"></i>--}}
                          {{--</div>--}}
                          {{--<div class="help-block">{{ $errors->first('cbd') }}</div>--}}
                      {{--@endif--}}
                  {{--</div>--}}
                  {{--<div class="form-group {{ $errors->has('maxgreen') ? 'has-error has-feedback' : '' }}">--}}
                      {{--<label for="parent_id" class="control-label text-semibold">Maxgreen</label>--}}
                      {{--<i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content='Mã sản phẩm CBD'></i>--}}
                      {{--<input type="text" id="cbd" name="maxgreen" class="form-control" value="{{ old('maxgreen') ? : @$product->maxgreen()->code}}" />--}}
                      {{--@if ($errors->has('maxgreen'))--}}
                          {{--<div class="form-control-feedback">--}}
                              {{--<i class="icon-notification2"></i>--}}
                          {{--</div>--}}
                          {{--<div class="help-block">{{ $errors->first('maxgreen') }}</div>--}}
                      {{--@endif--}}
                  {{--</div>--}}
                  {{--<div class="form-group {{ $errors->has('maxgro') ? 'has-error has-feedback' : '' }}">--}}
                      {{--<label for="parent_id" class="control-label text-semibold">Maxgr0</label>--}}
                      {{--<i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content='Mã sản phẩm CBD'></i>--}}
                      {{--<input type="text" id="maxgro" name="maxgro" class="form-control" value="{{ old('maxgro') ? : @$product->maxgro()->code }}" />--}}
                      {{--@if ($errors->has('maxgro'))--}}
                          {{--<div class="form-control-feedback">--}}
                              {{--<i class="icon-notification2"></i>--}}
                          {{--</div>--}}
                          {{--<div class="help-block">{{ $errors->first('maxgro') }}</div>--}}
                      {{--@endif--}}
                  {{--</div>--}}






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

