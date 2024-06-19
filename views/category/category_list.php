<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$categories = Category::all();

$category = null;
if (get('action') === 'update') {
    $category = Category::find(get('id'));
}

?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Category</strong> List</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_category') ?>
                    <?php displayFlashMessage('delete_category') ?>
                    <?php displayFlashMessage('update_category') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#categoryModal">
                                Add New Category
                            </button>
                            <br /><br /><br />
                            <table id="categoryTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td><?= $category->name ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $category->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/category_controller.php?action=delete&id=<?= $category->id ?>">
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
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">
                        New Category
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/category_controller.php" id="categoryForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="categoryId" value="" />
                        <div class="form-group">
                            <label for="categoryName">Category Name</label>
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
            $("#categoryTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#categoryTable_wrapper .col-md-6:eq(0)');
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