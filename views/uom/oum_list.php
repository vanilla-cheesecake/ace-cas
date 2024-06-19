<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$uoms = Uom::all();

$uom = null;
if (get('action') === 'update') {
    $uom = Uom::find(get('id'));
}

$page = 'uom_list';
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Uom</strong> List</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_uom') ?>
                    <?php displayFlashMessage('delete_uom') ?>
                    <?php displayFlashMessage('update_uom') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <!-- Button to trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#uomModal">
                                        Add New
                                    </button>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <form method="post" action="api/masterlist/uom_controller.php"
                                        enctype="multipart/form-data">
                                        <div class="form-group float-sm-right d-flex">
                                            <input type="file" class="form-control-file mr-1" id="excelFile"
                                                name="excelFile">
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </div>
                                    </form>
                                </div><!-- /.col -->
                            </div><!-- /.row -->

                            <table id="uomTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($uoms as $uom): ?>
                                        <tr>
                                            <td><?= $uom->name ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $uom->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/uom_controller.php?action=delete&id=<?= $uom->id ?>">
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
                </div>
            </div>
        </div>
    </main>



    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="uomModal" tabindex="-1" role="dialog" aria-labelledby="uomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uomModalLabel">
                        New Uom
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/uom_controller.php" id="uomForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="uomId" value="" />
                        <div class="form-group">
                            <label for="categoryName">Uom</label>
                            <input type="text" class="form-control" id="categoryName" name="name"
                                placeholder="Enter category name here" required="true" />
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


    <?php require 'views/templates/footer.php' ?>


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