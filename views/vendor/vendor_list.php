<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

// ARRAY
$vendors = Vendor::all();

$vendor = null;
if (get('action') === 'update') {
    $vendor = Vendor::find(get('id'));
}

$page = 'vendor_list';
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Vendor</strong> List</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_vendor') ?>
                    <?php displayFlashMessage('delete_vendor') ?>
                    <?php displayFlashMessage('update_category') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#vendorModal">
                                Add New Vendor
                            </button>
                            <br /><br /><br />
                            <table id="vendorTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vendors as $vendor): ?>
                                        <tr>
                                            <td><?= $vendor->vendor_name ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $vendor->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/vendor_controller.php?action=delete&id=<?= $vendor->id ?>">
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
 <div class="modal fade" id="vendorModal" tabindex="-1" role="dialog" aria-labelledby="vendorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorModalLabel">
                        New Vendor
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/vendor_controller.php" id="vendorForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="vendorId" value="" />
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vendorname">Vendor Name</label>
                                    <input type="text" class="form-control" id="vendorname" name="vendorname"
                                        placeholder="Enter Vendor Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vendorcode">Vendor Code</label>
                                    <input type="text" class="form-control" id="vendorcode" name="vendorcode"
                                        placeholder="Enter Vendor Code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="accountnumber">TIN Number</label>
                                    <input type="text" class="form-control" id="accountnumber" name="accountnumber"
                                        placeholder="Enter Account Number" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vendoraddress">Vendor Address</label>
                                    <input type="text" class="form-control" id="vendoraddress" name="vendoraddress"
                                        placeholder="Enter Vendor Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contactnumber">Contact #</label>
                                    <input type="text" class="form-control" id="contactnumber" name="contactnumber"
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
            $("#vendorTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#vendorTable_wrapper .col-md-6:eq(0)');
        });
    </script>