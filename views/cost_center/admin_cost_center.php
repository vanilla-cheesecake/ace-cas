<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$cost_centers = CostCenter::all();

$cost_center = null;
if (get('action') === 'update') {
    $cost_center = CostCenter::find(get('id'));
}

$page = 'cost_center';
?>
<?php require 'templates/header.php' ?>
<?php require 'templates/navbar.php' ?>
<?php require 'templates/sidebar.php' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br><br><br>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 display-1">Cost Center</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Masterlist</a></li>
                        <li class="breadcrumb-item active">Cost Center</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <?php displayFlashMessage('add_cost_center') ?>
        <?php displayFlashMessage('delete_cost_center') ?>
        <?php displayFlashMessage('update_cost_center') ?>
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uomModal">
                            Add New
                        </button>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <form method="post" action="api/masterlist/cost_center_controller.php"
                            enctype="multipart/form-data">
                            <div class="form-group float-sm-right d-flex">
                                <input type="file" class="form-control-file mr-1" id="excelFile" name="excelFile">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <table id="uomTable" class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Particular</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cost_centers as $cost_center): ?>
                            <tr>
                                <td><?= $cost_center->code ?></td>
                                <td><?= $cost_center->particular ?></td>
                                <td>
                                    <a class="text-primary" href="?action=update&id=<?= $cost_center->id ?>">
                                        <i class="fas fa-edit"></i> Update
                                    </a>
                                    <a class="text-danger ml-2"
                                        href="api/masterlist/cost_center_controller.php?action=delete&id=<?= $cost_center->id ?>">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

<!-- Modal -->
<div class="modal fade" id="uomModal" tabindex="-1" role="dialog" aria-labelledby="uomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uomModalLabel">
                    New Cost Center
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="api/masterlist/cost_center_controller.php" id="uomForm">
                    <input type="hidden" name="action" id="modalAction" value="add" />
                    <input type="hidden" name="id" id="uomId" value="" />
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter code here"
                            required="true" />
                    </div>
                    <div class="form-group">
                        <label for="particular">Particular</label>
                        <input type="text" class="form-control" id="particular" name="particular"
                            placeholder="Enter particular here" required="true" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'templates/footer.php' ?>
<script>
    $(function () {
        $("#uomTable").DataTable({
            "responsive": true,
            "lengthChange": true, // Allow changing the number of rows displayed
            "lengthMenu": [10, 20, 50, 100], // Define the options
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#uomTable_wrapper .col-md-6:eq(0)');

        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>