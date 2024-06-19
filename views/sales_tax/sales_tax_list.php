<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$sales_taxes = SalesTax::all();
$accounts = ChartOfAccount::all();

$sales_tax = null;
if (get('action') === 'update') {
    $sales_tax = SalesTax::find(get('id'));
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

            <h1 class="h3 mb-3"><strong>Sales</strong> Tax</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_sales_tax') ?>
                    <?php displayFlashMessage('delete_sales_tax') ?>
                    <?php displayFlashMessage('update_sales_tax') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModal">
                                Add New Sales Tax
                            </button>
                            <br /><br /><br />
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>Sales Tax</th>
                                        <th>Sales Tax Rate (%)</th>
                                        <th>Description</th>
                                        <th>Account</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sales_taxes as $sales_tax): ?>
                                        <tr>
                                            <td><?= $sales_tax->sales_tax_name ?></td>
                                            <td><?= $sales_tax->sales_tax_rate ?></td>
                                            <td><?= $sales_tax->description ?></td>
                                            <td><?= $sales_tax->sales_tax_account_id ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $sales_tax->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/sales_tax_controller.php?action=delete&id=<?= $sales_tax->id ?>">
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
                        New Sales Tax
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/sales_tax_controller.php" id="salesTaxForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="termId" value="" />
                        <div class="form-group">
                            <label for="sales_tax_name">Sales Tax</label>
                            <input type="text" class="form-control" id="sales_tax_name" name="sales_tax_name"
                                placeholder="Enter sales tax here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="sales_tax_rate">Sales Tax Rate (%)</label>
                            <input type="number" class="form-control" id="sales_tax_rate" name="sales_tax_rate"
                                placeholder="Enter sales tax rate here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Enter description here" required="true" />
                        </div>
                        <!-- Set the retrieved COGS account types in your dropdown -->
                        <div class="form-group">
                            <label for="sales_tax_account_id">TAX ACCOUNT</label>
                            <select class="form-control" id="sales_tax_account_id" name="sales_tax_account_id">
                                <!-- <option value="">-- Select COGS Account --</option> -->
                                <?php foreach ($accounts as $account): ?>

                                    <option value="<?= $account->id ?>"><?= $account->description ?></option>

                                <?php endforeach; ?>
                            </select>
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
            $("#table").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
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