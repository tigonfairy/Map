<?php $__env->startSection('content'); ?>

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Tạo vùng quản lý theo nhân viên</h2>
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
                    <?php if(session('success')): ?>
                        <div class="alert bg-success alert-styled-left">
                            <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                        class="sr-only">Close</span></button>
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <form method="POST" action="<?php echo e(route('Admin::map@addMapUserPost')); ?>">
                            <?php echo e(csrf_field()); ?>

                                <div class="form-group <?php echo e($errors->has('name') ? 'has-error has-feedback' : ''); ?>">
                                    <label for="name" class="control-label text-semibold">Tên</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của vùng"></i>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name') ?: @$permission->id); ?>" />
                                    <?php if($errors->has('name')): ?>
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block"><?php echo e($errors->first('name')); ?></div>
                                    <?php endif; ?>
                                </div>

                            <!---------- Manager ID------------>
                                <div class="form-group <?php echo e($errors->has('user_id') ? 'has-error has-feedback' : ''); ?>">
                                    <label for="name" class="control-label text-semibold">Nhân viên quản Lý</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                       data-content="Nhân viên quản Lý"></i>
                                    <select name="manager_id" class="users">
                                        <option value="">-- Chọn quản lý --</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <option value="<?php echo e($value->id); ?>"><?php echo e($value->email); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    </select>
                                    <?php if($errors->has('user_id')): ?>
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block"><?php echo e($errors->first('user_id')); ?></div>
                                    <?php endif; ?>
                                </div>

                                <!---------- Place ID------------>
                                <div class="form-group <?php echo e($errors->has('place') ? 'has-error has-feedback' : ''); ?>">
                                    <label for="name" class="control-label text-semibold">Vùng quản lý</label>
                                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon"
                                       data-content="Vùng quản lý"></i>
                                    <select name="place[]" id="locations" class="places" multiple style="width:100%">
                                        
                                        
                                            
                                                    
                                        
                                    </select>
                                    <?php if($errors->has('place')): ?>
                                        <div class="form-control-feedback">
                                            <i class="icon-notification2"></i>
                                        </div>
                                        <div class="help-block"><?php echo e($errors->first('place')); ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <div class="panel panel-flat">
                                        <div class="table-responsive">
                                            <div id="map"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Màu nền</label>
                                        <div class="col-md-6">
                                            <div class="input-group color colorpicker-default" data-color="#3865a8"
                                                 data-color-format="rgba">
                                                <input type="text" class="form-control" value="#3865a8"
                                                       name="background_color" >
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button"><i
                                                                style="background-color: #3865a8;"></i>&nbsp;</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Màu của border</label>
                                        <div class="col-md-6">
                                            <div class="input-group color colorpicker-default" data-color="#3865a8"
                                                 data-color-format="rgba">
                                                <input type="text" class="form-control" value="#3865a8"
                                                       name="border_color" >
                                                <span class="input-group-btn">
                                                    <button class="btn default" type="button"><i
                                                                style="background-color: #3865a8;"></i>&nbsp;</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    </div>
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
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts_foot'); ?>

<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

<script type="text/javascript">
    var map;
    var drawingManager;
    var shapes = [];
    var patch = [];
    var markers = [];
    var polygonArray = [];
    $(document).ready(function () {

        //load ajax selct2
        $(".places").select2({
            'placeholder' : 'Nhập từ khóa để tìm kiếm vị trí',
            ajax : {
                url : "<?php echo e(route('Admin::Api::area@getListAreas')); ?>",
                dataType:'json',
                delay:500,
                data: function (params) {

                    var queryParameters = {
                        q: params.term
                    }
                    return queryParameters;
                },
                processResults: function(data, page) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                slug: item.slug,
                                id: item.id,
                                coordinates:item.coordinates
                            }
                        })
                    };
                },
                dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                escapeMarkup: function(m) {
                    return m;
                }
            }
        });


        $(".users").select2();
        Array.prototype.insert = function (index, item) {
            this.splice(index, 0, item);
        };

        var opts = [];
        map = new GMaps({
            div: '#map',
            lat: 21.0277644,
            lng: 105.83415979999995,
            width: "100%",
            height: '500px',
            zoom: 11
        });
        $('#locations').on("select2:select", function (e) {
            var id = e.params.data.id;
            var coordinates = e.params.data.coordinates;
            var coordinate = JSON.parse(coordinates);
            if (coordinate) {
                var bounds = new google.maps.LatLngBounds();
                for (i = 0; i < coordinate.length; i++) {
                    var c = coordinate[i];
                    bounds.extend(new google.maps.LatLng(c[0], c[1]));
                }
                var path = coordinate;
                map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
                var infoWindow = new google.maps.InfoWindow({
                    content: 'you clicked a polyline'
                });
                polygon = map.drawPolygon({
                    paths: path,
                    strokeColor: '#333',
                    strokeOpacity: 0.5,
                    strokeWeight: 1,
                    fillColor: '#333',
                    fillOpacity: 0.6,
                    mouseover: function (clickEvent) {
                        var position = clickEvent.latLng;
                        infoWindow.setPosition(position);
                        infoWindow.open(map.map);
                    },
                    mouseout: function (clickEvent) {
                        if (infoWindow) {
                            infoWindow.close();
                        }
                    }
                });

                polygonArray[id] = polygon;
            }
        });
        $('#locations').on("select2:unselect", function (e) {
            var id = e.params.data.id;
            var coordinates = e.params.data.coordinates;
            var coordinate = JSON.parse(coordinates);
            if (coordinate) {
                map.removePolygon(polygonArray[id]);
            }
        });
    });
</script>


<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>