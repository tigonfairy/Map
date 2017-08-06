<?php $__env->startSection('content'); ?>
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>Group</h2>
    </div>

    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="<?php echo e(route('Admin::role@add')); ?>" class="btn btn-primary"><i class="icon-add"></i> Thêm</a>

      </div>
    </div>
  </div>
</div>
<!-- /page header -->
<!-- Page container -->
<div class="page-container">

    <!-- Main content -->
    <div class="content-wrapper">

      <?php if(session('success')): ?>
        <div class="alert bg-success alert-styled-left">
          <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
          <?php echo e(session('success')); ?>

        </div>
      <?php endif; ?>

          <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="roles-table" >
            <thead>
            <tr>
              <th>Id</th>
              <th>Name</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
              <tr role="row" id="">
                <td><?php echo e($row->id); ?></td>
                <td><?php echo e($row->name); ?></td>
                <td><a href="<?php echo e(route('Admin::role@edit', [$row->id])); ?>"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#edit-pro">Edit</button></a> <a onclick="return xoaCat();" href="<?php echo e(route('Admin::role@delete', [$row->id])); ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Del</a></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </tbody>
          </table>
    </div>
    <!-- /main content -->
  </div>

<!-- /page container -->


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  function xoaCat(){
    var conf = confirm("Bạn chắc chắn muốn xoá?");
    return conf;
  }

  $(document).ready(function() {
      $('#roles-table').DataTable({
          "bInfo" : false,
          "columns":[
              {
                  "sortable": true
              },
              {
                  "sortable": true
              },
              {
                  "sortable": false
              },
          ]
      });
  } );

</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>