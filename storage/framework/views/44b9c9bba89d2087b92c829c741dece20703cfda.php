<?php $__env->startSection('content'); ?>
  <!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h2>

            <i class="icon-arrow-left8"></i>
          </a>
          <?php echo e(isset($role) ? 'Sửa  ' : 'Thêm '); ?>

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
            <?php if(session('success')): ?>
              <div class="alert bg-success alert-styled-left">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                <?php echo e(session('success')); ?>

              </div>
            <?php endif; ?>
            <div class="panel panel-flat">
              <div class="panel-body">
                <form method="POST" enctype="multipart/form-data" action="<?php echo e(isset($role) ? route('Admin::role@update', [$role->id] ): route('Admin::role@store')); ?>">
                  <?php echo e(csrf_field()); ?>

                  <?php if(isset($role)): ?>
                    <input type="hidden" name="_method" value="PUT">
                  <?php endif; ?>
                  <!---------- Name------------>
                    <div class="form-group <?php echo e($errors->has('name') ? 'has-error has-feedback' : ''); ?>">
                      <label for="name" class="control-label text-semibold">Name</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                      <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name') ?: @$role->name); ?>" />
                      <?php if($errors->has('name')): ?>
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block"><?php echo e($errors->first('name')); ?></div>
                      <?php endif; ?>
                    </div>

                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Permission</th>
                            <th>True</th>
                            <th>False</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <tr role="row" id="">
                              <td><?php echo e($row->id); ?></td>
                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[<?php echo e($row->id); ?>]" class="js-radio"  <?php echo e((isset($rolePermissions[$row->id]) and $rolePermissions[$row->id]->pivot->value == 0) ? ' checked="checked"' : ''); ?> class="js-radio" value="0">
                                  </label>
                                </div>
                              </td>

                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[<?php echo e($row->id); ?>]" class="js-radio" <?php echo e((isset($rolePermissions[$row->id]) and $rolePermissions[$row->id]->pivot->value == 1) ? ' checked="checked"' : ''); ?> class="js-radio" value="1">
                                  </label>
                                </div>
                              </td>

                            </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                          </tbody>
                        </table>


                      </div>

                    </div>


                    <div class="text-right">
                      <button type="submit" class="btn btn-primary"><?php echo e(isset($role) ? 'Cập nhật' : 'Thêm mới'); ?></button>
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /main content -->
    </div>
    <!-- /page content -->

<!-- /page container -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js_files_foot'); ?>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts_foot'); ?>
<script>
  // Replace the <textarea id="editor1"> with a CKEditor
  // instance, using default configuration.
  CKEDITOR.replace( 'editor1' );
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>