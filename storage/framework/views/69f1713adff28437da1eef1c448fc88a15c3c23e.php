<?php $__env->startSection('content'); ?>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Danh sách các vùng</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="<?php echo e(route('Admin::map@addMapUser')); ?>" class="btn btn-primary"><i class="icon-add"></i> Thêm
                        vùng kinh doanh</a>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <form action="">
            <div class="col-xs-6 col-xs-offset-3">

                <input type="text" name="q" class="form-control" value="<?php echo e(Request::input('q')); ?>"
                       placeholder="Nhập tên để tìm kiếm"/>

            </div>
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
    </div>


    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

        <div class="content-wrapper">
            <?php echo $__env->make('admin.flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="users-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Màu của border</th>
                    <th>Màu nền</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <tr role="row" id="">
                        <td><?php echo e($area->name); ?></td>
                        <td><?php echo e($area->border_color); ?></td>
                        <td><?php echo e($area->background_color); ?></td>

                        <td><a href="<?php echo e(route('Admin::map@mapUserDetail',[$area->id])); ?>">
                                <button type="button" class="btn btn-info btn-xs">Chi tiết</button></a>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                </tbody>
            </table>
        </div>
        <!-- /main content -->
    </div>

    <!-- /page content -->

    <!-- /page container -->


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function xoaCat() {
        var conf = confirm("Bạn chắc chắn muốn xoá?");
        return conf;
    }


</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>