<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$terms = Term::all();

$term = null;
if (get('action') === 'update') {
    $term = Term::find(get('id'));
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

            <h1 class="h3 mb-3"><strong>Terms</strong> List</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_uom') ?>
                    <?php displayFlashMessage('delete_uom') ?>
                    <?php displayFlashMessage('update_uom') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#termModal">
                                Add New Term
                            </button>
                            <br /><br /><br />
                            <table id="termTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Term Name</th>
                                        <th>Term Days Due</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($terms as $term): ?>
                                        <tr>
                                            <td><?= $term->term_name ?></td>
                                            <td><?= $term->term_days_due ?></td>
                                            <td><?= $term->description ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $term->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/term_controller.php?action=delete&id=<?= $term->id ?>">
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
    <div class="modal fade" id="termModal" tabindex="-1" role="dialog" aria-labelledby="termModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termModalLabel">
                        New Term
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/term_controller.php" id="termForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="termId" value="" />
                        <div class="form-group">
                            <label for="term_name">Term Name</label>
                            <input type="text" class="form-control" id="term_name" name="term_name"
                                placeholder="Enter term name here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="term_days_due">No. of days due</label>
                            <input type="number" class="form-control" id="term_days_due" name="term_days_due"
                                placeholder="Enter term days due here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Enter term name here" required="true" />
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
            $("#termTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#termTable_wrapper .col-md-6:eq(0)');
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