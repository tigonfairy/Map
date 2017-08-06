<?php $__env->startSection('content'); ?>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Chi tiết vùng kinh doanh : <?php echo e($area->name); ?></h2>
                <h4>Quản lý : <?php echo e($area->user->email); ?></h4>
            </div>
        </div>
    </div>

    <div class="page-container">
        <div class="content-wrapper">
            <?php echo $__env->make('admin.flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="row">
                <div class="col-xs-6">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts_foot'); ?>

<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key=AIzaSyDUMRn1pnBk97Zay94WiBbMgdVlBh_vwYs&libraries=drawing"></script>
<script type="text/javascript" src="/js/gmaps.js"></script>
<script type="text/javascript" src="/js/prettify.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    var polygonArray = [];
    map = new GMaps({
        div: '#map',
        lat: 21.0277644,
        lng: 105.83415979999995,
        width: "100%",
        height: '500px',
        zoom: 11
    });
    <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
    var c = "<?php echo e($location->coordinates); ?>";
    var coordinate = JSON.parse(c);
    <?php 
    $border_color = '#333';
    $background_color = '#333';
            if($area->border_color){
            $border_color = $area->border_color;
            }
     if($area->background_color){
            $background_color = $area->background_color;
            }
             ?>
    if (coordinate) {
        var bounds = new google.maps.LatLngBounds();
        for (i = 0; i < coordinate.length; i++) {
            var c = coordinate[i];
            bounds.extend(new google.maps.LatLng(c[0], c[1]));
        }
        var path = coordinate;
        map.setCenter(bounds.getCenter().lat(), bounds.getCenter().lng());
        var infoWindow<?php echo e($location->id); ?> = new google.maps.InfoWindow({
            content: "<p><?php echo e($location->name); ?></p>"
        });
        polygon = map.drawPolygon({
            paths: path,
            strokeColor: "<?php echo e($border_color); ?>",
            strokeOpacity: 1,
            strokeWeight: 1,
            fillColor: "<?php echo e($background_color); ?>",
            fillOpacity: 0.6,
            mouseover: function (clickEvent) {
                var position = clickEvent.latLng;
                infoWindow<?php echo e($location->id); ?>.setPosition(position);
                infoWindow<?php echo e($location->id); ?>.open(map.map);
            },
            mouseout: function (clickEvent) {
                if (infoWindow<?php echo e($location->id); ?>) {
                    infoWindow<?php echo e($location->id); ?>.close();
                }
            }
        });
        polygonArray["<?php echo e($location->id); ?>"] = polygon;
    }
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>