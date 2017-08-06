<?php $__env->startSection('content'); ?>
        <!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h2>
        <a href="" class="btn btn-link">
          <i class="icon-arrow-left8"></i>
        </a>
        <?php echo e(isset($user) ? 'Sửa Thành viên ' : 'Thêm Thành viên'); ?>

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
          <?php if(session('success')): ?>
            <div class="alert bg-success alert-styled-left">
              <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
              <?php echo e(session('success')); ?>

            </div>
          <?php endif; ?>
          <div class="panel panel-flat">
            <div class="panel-body">
              <form method="POST" action="<?php echo e(isset($user) ? route('Admin::user@update', [$user->id] ): route('Admin::user@store')); ?>">
                <?php echo e(csrf_field()); ?>

                <?php if(isset($user)): ?>
                  <input type="hidden" name="_method" value="PUT">
                  <?php endif; ?>
                          <!---------- Name------------>
                  <div class="form-group <?php echo e($errors->has('name') ? 'has-error has-feedback' : ''); ?>">
                    <label for="name" class="control-label text-semibold">Họ và tên</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Họ và tên"></i>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name') ?: @$user->name); ?>" />
                    <?php if($errors->has('name')): ?>
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block"><?php echo e($errors->first('name')); ?></div>
                    <?php endif; ?>
                  </div>

                <!---------- Code------------>
                <div class="form-group <?php echo e($errors->has('code') ? 'has-error has-feedback' : ''); ?>">
                  <label for="name" class="control-label text-semibold">Mã nhân viên</label>
                  <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mã nhân viên"></i>
                  <input type="text" id="code" name="code" class="form-control" value="<?php echo e(old('code') ?: @$user->code); ?>" />
                  <?php if($errors->has('code')): ?>
                    <div class="form-control-feedback">
                      <i class="icon-notification2"></i>
                    </div>
                    <div class="help-block"><?php echo e($errors->first('code')); ?></div>
                  <?php endif; ?>
                </div>

                <!---------- Manager ID------------>
                <div class="form-group <?php echo e($errors->has('manager_id') ? 'has-error has-feedback' : ''); ?>">
                  <label for="name" class="control-label text-semibold">Người Quản Lý</label>
                  <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Người Quản Lý"></i>
                  <select name="manager_id" class="form-control">
                    <option value="">-- Chọn quản lý --</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                      <option value="<?php echo e($id); ?>" <?php echo e($id == @$user->manager_id ? "selected=selected" : ""); ?>><?php echo e($name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                  </select>
                  <?php if($errors->has('manager_id')): ?>
                    <div class="form-control-feedback">
                      <i class="icon-notification2"></i>
                    </div>
                    <div class="help-block"><?php echo e($errors->first('manager_id')); ?></div>
                  <?php endif; ?>
                </div>

                <!---------- Position ------------>
                <div class="form-group <?php echo e($errors->has('code') ? 'has-error has-feedback' : ''); ?>">
                  <label for="name" class="control-label text-semibold">Vị trí</label>
                  <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Vị trí"></i>
                  <input type="text" id="position" name="position" class="form-control" value="<?php echo e(old('code') ?: @$user->position); ?>" />
                  <?php if($errors->has('position')): ?>
                    <div class="form-control-feedback">
                      <i class="icon-notification2"></i>
                    </div>
                    <div class="help-block"><?php echo e($errors->first('position')); ?></div>
                  <?php endif; ?>
                </div>

                  <!------------------ Email--------------->
                  <div class="form-group <?php echo e($errors->has('email') ? 'has-error has-feedback' : ''); ?>">
                    <label for="name" class="control-label text-semibold">Email</label>
                    <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Tên của Doanh nghiệp"></i>
                    <input type="text" <?php if(isset($user)): ?>disabled <?php endif; ?> id="email" name="email" class="form-control" value="<?php echo e(old('email') ?: @$user->email); ?>" />
                    <?php if($errors->has('email')): ?>
                      <div class="form-control-feedback">
                        <i class="icon-notification2"></i>
                      </div>
                      <div class="help-block"><?php echo e($errors->first('email')); ?></div>
                    <?php endif; ?>
                  </div>
                  <!------------------------- Password-------------------->

                    <div class="form-group <?php echo e($errors->has('password') ? 'has-error has-feedback' : ''); ?>">
                      <label for="passwrod" class="control-label text-semibold">Mật khẩu</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Mật khẩu đăng nhập vào hệ thống <strong>iCheck cho doanh nghiệp</strong> của Doanh nghiệp."></i>
                      <input type="password" id="password" name="password" class="form-control" />
                      <?php if($errors->has('password')): ?>
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block"><?php echo e($errors->first('password')); ?></div>
                      <?php endif; ?>
                    </div>

                    <div class="form-group <?php echo e($errors->has('password_confirmation') ? 'has-error has-feedback' : ''); ?>">
                      <label for="password-confirmation" class="control-label text-semibold">Xác nhận Mật khẩu</label>
                      <i class="icon-question4 text-muted text-size-mini cursor-pointer js-help-icon" data-content="Nhập lại mật khẩu ở trên."></i>
                      <input type="password" id="password-confirmation" name="password_confirmation" class="form-control" />
                      <?php if($errors->has('password_confirmation')): ?>
                        <div class="form-control-feedback">
                          <i class="icon-notification2"></i>
                        </div>
                        <div class="help-block"><?php echo e($errors->first('password_confirmation')); ?></div>
                      <?php endif; ?>
                    </div>

                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Group</th>
                            <th>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                          <tr role="row" id="">
                            <td><?php echo e($row->name); ?></td>
                            <td>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" id="" name="role[]" value="<?php echo e($row->id); ?>" <?php echo e(isset($userRoles[$row->id]) ? ' checked="checked"' : ''); ?>  class="js-checkbox">
                                  </label>
                                </div>
                            </td>
                          </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                          </tbody>
                        </table>


                      </div>

                    </div>


                    <div class="panel panel-flat">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                          <tr>
                            <th>Permission</th>
                            <th>True</th>
                            <th>False</th>
                            <th>Null</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <tr role="row" id="">
                              <td><?php echo e($row->id); ?></td>
                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[<?php echo e($row->id); ?>]" <?php echo e((isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 0) ? ' checked="checked"' : ''); ?> class="js-radio" value="0">
                                  </label>
                                </div>
                              </td>

                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[<?php echo e($row->id); ?>]" <?php echo e((isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 1) ? ' checked="checked"' : ''); ?> class="js-radio" value="1">
                                  </label>
                                </div>
                              </td>

                              <td>
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" name="status[<?php echo e($row->id); ?>]" <?php echo e((isset($userPermissions[$row->id]) and $userPermissions[$row->id]->pivot->value == 2) ? ' checked="checked"' : ''); ?> class="js-radio" value="2">
                                  </label>
                                </div>
                              </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                          </tbody>
                        </table>


                      </div>

                    </div>
                    </div>


                  <div class="text-right">
                    <button type="submit" class="btn btn-primary"><?php echo e(isset($user) ? 'Cập nhật' : 'Thêm mới'); ?></button>
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

<?php $__env->startPush('js_files_foot'); ?>
<script type="text/javascript" src="<?php echo e(asset('assets/js/plugins/forms/selects/select2.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/plugins/forms/styling/uniform.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts_foot'); ?>
<script>
  $(document).ready(function () {
    // Basic
    $(".js-select").select2();

    //
    // Select with icons
    //

    // Format icon
    function iconFormat(icon) {
      var originalOption = icon.element;
      if (!icon.id) { return icon.text; }
      var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

      return $icon;
    }

    // Initialize with options
    $(".select-icons").select2({
      templateResult: iconFormat,
      minimumResultsForSearch: Infinity,
      templateSelection: iconFormat,
      escapeMarkup: function(m) { return m; }
    });



    // Styled form components
    // ------------------------------

    // Checkboxes, radios
    $(".js-radio, .js-checkbox").uniform({ radioClass: "choice" });

    // File input
    $(".js-file").uniform({
      fileButtonClass: "action btn btn-default"
    });

    $(".js-tooltip, .js-help-icon").popover({
      container: "body",
      html: true,
      trigger: "hover",
      delay: { "hide": 1000 }
    });

    // Toggle password inputs
    $(document).on('click', 'a#show-password-inputs', function (e) {
      e.preventDefault();

      $('#password-inputs').removeClass('hidden').prev().addClass('hidden');
    });

    $(document).on('click', 'a#hide-password-inputs', function (e) {
      e.preventDefault();

      $('#password-inputs').addClass('hidden').prev().removeClass('hidden');
    });

    <?php if($errors->has('password')): ?>
    $('a#show-password-inputs').trigger('click');
    <?php endif; ?>

  });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>