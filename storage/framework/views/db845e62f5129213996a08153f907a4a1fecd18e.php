<?php $__env->startSection('content'); ?>
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Thành viên</h2>
            </div>

            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="<?php echo e(route('Admin::user@add')); ?>" class="btn btn-primary"><i class="icon-add"></i> Thêm thành viên</a>

                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->

            <div class="content-wrapper">
                <?php if(session('success')): ?>
                    <div class="alert bg-success alert-styled-left">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="users-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Email</th>
                                <th>Group</th>
                                <th>Created_At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            
                                
                                    

                                    
                                    
                                            
                                        
                                    
                                    
                                    
                                        
                                
                            
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
    function xoaCat(){
        var conf = confirm("Bạn chắc chắn muốn xoá?");
        return conf;
    }

    $(document).ready(function() {
        var datatable = $("#users-table").DataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            pageLength: 20,
            ajax: {
                url: '<?php echo route('Admin::user@datatables'); ?>',
                data: function (d) {
                    //
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'email', name: 'email'},
                {data: 'group', name: 'group'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

    } );

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>