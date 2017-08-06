<?php $__env->startSection('content'); ?>

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Tạo đại lý</h2>
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
                <div class="col-md-7">

                    <form method="post" id="geocoding_form">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" id="address" name="address" placeholder="Nhập vị trí" class="form-control">
                                <?php if($errors->has('lat') or $errors->has('lng')): ?>
                                    <div class="form-control-feedback">
                                        <i class="icon-notification2"></i>
                                    </div>
                                    <div class="help-block"><?php echo e($errors->first('lng')); ?></div>
                                <?php endif; ?>

                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info">Search</button>
                            </div>
                        </div>
                    </form>
                    <div class="panel panel-flat">
                        <div class="table-responsive">
                            <div id="map"></div>
                        </div>

                    </div>
                </div>
                <div class="col-md-5">
                    <form action="<?php echo e(route('Admin::map@addMapAgencyPost')); ?>" method="POST">
                        <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Tên vùng địa lý</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control name" name="name" placeholder="Nhập tên vùng địa lý">
                        </div>
                        <?php if($errors->has('name')): ?>
                            <div class="form-control-feedback">
                                <i class="icon-notification2"></i>
                            </div>
                            <div class="help-block"><?php echo e($errors->first('name')); ?></div>
                        <?php endif; ?>
                        <input type="hidden" class="form-control " id="lat" name="lat" >
                        <input type="hidden" class="form-control " id="lng" name="lng" >
                        <div class="clearfix"></div>
                    </div>

                        <div class="form-group <?php echo e($errors->has('user_id') ? 'has-error has-feedback' : ''); ?>">
                            <label for="name" class="control-label text-semibold col-md-3">Nhân viên quản Lý</label>
                            <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                               data-content="Nhân viên quản Lý"></i>
                            <div class="col-md-9">

                            <select name="manager_id" class="users form-control">
                                <option value="">-- Chọn quản lý --</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <option value="<?php echo e($value->id); ?>"><?php echo e($value->email); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </select>
                            <?php if($errors->has('manager_id')): ?>
                                <div class="form-control-feedback">
                                    <i class="icon-notification2"></i>
                                </div>
                                <div class="help-block"><?php echo e($errors->first('manager_id')); ?></div>
                            <?php endif; ?>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row btn-submit-add-map">
                            <button type="submit" class="btn btn-info">Tạo</button>
                        </div>

                    </form>
                </div>

                
            </div>


        </div>
        <!-- /main content -->
    </div>

    <!-- /page container -->
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts_foot'); ?>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

<script type="text/javascript">
    var map;
    var markers = [];

    $(document).ready(function () {
        $('.users').select2();
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
        });

        map.addListener('click', function (e) {

            var ll = {lat: e.latLng.lat(), lng: e.latLng.lng()};
            map.removeMarkers();
            markers = [];
            map.addMarker({
                lat: ll.lat,
                lng: ll.lng,
                title: 'Lima',
                click: function(e) {
                    alert('You clicked in this marker');
                }
            });
            $('#lat').val(ll.lat);
            $('#lng').val(ll.lng);

        });
        $('#geocoding_form').submit(function(e){
            e.preventDefault();
            map.removeMarkers();
            GMaps.geocode({
                address: $('#address').val().trim(),
                callback: function(results, status){
                    if(status=='OK'){
                        var latlng = results[0].geometry.location;
                        map.setCenter(latlng.lat(), latlng.lng());
                        map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng()
                        });
                        $('#lat').val(latlng.lat());
                        $('#lng').val(latlng.lng());
                    }
                }
            });
        });

    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>