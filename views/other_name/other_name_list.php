<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$other_names = OtherNameList::all();

$other_name = null;
if (get('action') === 'update') {
    $other_name = OtherNameList::find(get('id'));
}

$page = 'other_name_list';
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Other Name</strong> List</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_other_name') ?>
                    <?php displayFlashMessage('delete_other_name') ?>
                    <?php displayFlashMessage('update_other_name') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#other_nameModal">
                                Add New Other Name
                            </button>
                            <br /><br /><br />
                            <table id="other_nameTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($other_names as $other_name): ?>
                                        <tr>
                                            <td><?= $other_name->other_name ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $other_name->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/other_name_list_controller.php?action=delete&id=<?= $other_name->id ?>">
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
    <div class="modal fade" id="other_nameModal" tabindex="-1" role="dialog" aria-labelledby="other_nameModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="other_nameModalLabel">
                        New Other Name
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/other_name_list_controller.php" id="othernameForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="other_nameId" value="" />
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="other_name">Other Name</label>
                                    <input type="text" class="form-control" id="other_name" name="other_name"
                                        placeholder="Enter Other Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="other_name_code">Other Name Code</label>
                                    <input type="text" class="form-control" id="other_name_code" name="other_name_code"
                                        placeholder="Enter Other Name Code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_number">TIN Number</label>
                                    <input type="text" class="form-control" id="account_number" name="account_number"
                                        placeholder="Enter Account Number" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="other_name_address">Other Name Address</label>
                                    <input type="text" class="form-control" id="other_name_address"
                                        name="other_name_address" placeholder="Enter Other Name Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contactnumber">Contact #</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                        placeholder="Enter Contact #">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="terms">Terms</label>
                                    <input type="text" class="form-control" id="terms" name="terms">
                                </div>
                            </div>
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
            $("#other_nameTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#other_nameTable_wrapper .col-md-6:eq(0)');
        });
    </script>