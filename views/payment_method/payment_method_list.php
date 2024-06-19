<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$payment_methods = PaymentMethod::all();

$payment_method = null;
if (get('action') === 'update') {
    $payment_method = PaymentMethod::find(get('id'));
}

$page = 'payment_method';
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Payment</strong> Method</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_payment_method') ?>
                    <?php displayFlashMessage('delete_payment_method') ?>
                    <?php displayFlashMessage('update_payment_method') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModal">
                                Add New Payment
                            </button>
                            <br /><br /><br />
                            <table id="termTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Payment Method</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payment_methods as $payment_method): ?>
                                        <tr>
                                            <td><?= $payment_method->payment_method_name ?></td>
                                            <td><?= $payment_method->description ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $payment_method->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/payment_method_controller.php?action=delete&id=<?= $payment_method->id ?>">
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
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">
                        New Term
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/payment_method_controller.php" id="paymentMethodForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="paymentMethodId" value="" />
                        <div class="form-group">
                            <label for="payment_method_name">Payment Method</label>
                            <input type="text" class="form-control" id="payment_method_name" name="payment_method_name"
                                placeholder="Enter payment method here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Enter description here" required="true" />
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