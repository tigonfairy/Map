@extends('admin')

@section('page_title', 'Trang không tồn tại')

@section('content')
  <!-- Page container -->
  <div class="page-container">
    <!-- Page content -->
    <div class="page-content">
      <!-- Main content -->
      <div class="content-wrapper">
        <div class="text-center content-group">
          <h1>Truy cập bị từ chối</h1>
          <h5>Bạn không có quyền vào trang này</h5>
          <ul class="list-inline">
            <li><a href="#" onclick="window.history.back();">Quay về trang trước</a></li>

          </ul>
        </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->
  </div>
  <!-- /page container -->
@endsection
