<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$customers = Customer::all();

$customer = null;
if (get('action') === 'update') {
    $customer = Customer::find(get('id'));
}

$page = 'customer_list';
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Customer</strong> List</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_customer') ?>
                    <?php displayFlashMessage('delete_customer') ?>
                    <?php displayFlashMessage('update_customer') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#vendorModal">
                                Add New Customer
                            </button>
                            <br /><br /><br />
                            <table id="customerTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $customer): ?>
                                        <tr>
                                            <td><?= $customer->customer_name ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $customer->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/customer_controller.php?action=delete&id=<?= $customer->id ?>">
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
                        New Customer
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/customer_controller.php" id="vendorForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="customerId" value="" />
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="customer_name">Customer Name</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                        placeholder="Enter Customer Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_code">Customer Code</label>
                                    <input type="text" class="form-control" id="customer_code" name="customer_code"
                                        placeholder="Enter Vendor Code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_business_style">Business Style</label>
                                    <input type="text" class="form-control" id="customer_business_style"
                                        name="customer_business_style" placeholder="Enter Business Style">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="customer_address">Customer Address</label>
                                    <input type="text" class="form-control" id="customer_address"
                                        name="customer_address" placeholder="Enter Customer Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_number">Contact #</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                        placeholder="Enter Contact #" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_email">Email</label>
                                    <input type="text" class="form-control" id="customer_email" name="customer_email"
                                        placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_tin">TIN</label>
                                    <input type="text" class="form-control" id="customer_tin" name="customer_tin"
                                        placeholder="Enter Tin">
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
            $("#customerTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#customerTable_wrapper .col-md-6:eq(0)');
        });
    </script>