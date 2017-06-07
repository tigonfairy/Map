@extends('admin')

@section('content')
    <style>
        #map {
            width: 800px;
            height: 300px;
        }
    </style>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Dashboard</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">

            @if (session('success'))
                <div class="alert bg-success alert-styled-left">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                class="sr-only">Close</span></button>
                    {{ session('success') }}
                </div>
            @endif



            </div>
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->

    <!-- /page container -->


@endsection


